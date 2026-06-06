<?php

namespace Modules\Damage\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Capital\Services\CapitalService;
use Modules\Damage\Models\Damage;
use Modules\Damage\Models\DamageItem;
use Modules\Product\Models\ProductBatch;
use Modules\Product\Services\ProductBatchService;

class DamageService
{
    protected CapitalService $capitalService;

    protected ProductBatchService $productBatchService;

    public function __construct(CapitalService $capitalService, ProductBatchService $productBatchService)
    {
        $this->capitalService = $capitalService;
        $this->productBatchService = $productBatchService;
    }

    public function getDamages(array $filters = [], int $perPage = 15)
    {
        $query = Damage::with([
            'shop' => fn ($query) => $query->withTrashed(),
            'creator' => fn ($query) => $query->withTrashed(),
            'items.product' => fn ($query) => $query->withTrashed(),
            'items.productBatch' => fn ($query) => $query->withTrashed(),
        ])->latest('damage_date');

        if (! empty($filters['shop_ids'])) {
            $query->whereIn('shop_id', $filters['shop_ids']);
        }

        if (! empty($filters['shop_id'])) {
            $query->where('shop_id', $filters['shop_id']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('damage_date', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('damage_date', '<=', $filters['date_to']);
        }

        return $query->paginate($perPage)->appends($filters);
    }

    public function getDamage(int $id): Damage
    {
        return Damage::with([
            'shop' => fn ($query) => $query->withTrashed(),
            'creator' => fn ($query) => $query->withTrashed(),
            'items.product' => fn ($query) => $query->withTrashed(),
            'items.productBatch' => fn ($query) => $query->withTrashed(),
        ])->findOrFail($id);
    }

    public function storeDamage(array $data): Damage
    {
        return DB::transaction(function () use ($data) {
            $damage = Damage::create([
                'shop_id' => (int) $data['shop_id'],
                'reference_no' => null,
                'damage_date' => $data['damage_date'],
                'total_quantity' => 0,
                'total_loss' => 0,
                'note' => $data['note'] ?? null,
                'created_by' => $data['created_by'] ?? null,
            ]);

            $damage->reference_no = $this->generateReferenceNumber((int) $damage->id, (string) $data['damage_date']);
            $damage->save();

            $totalQuantity = 0;
            $totalLoss = 0.0;

            foreach ($data['items'] as $index => $item) {
                $batch = ProductBatch::query()
                    ->where('id', $item['product_batch_id'])
                    ->where('shop_id', $data['shop_id'])
                    ->lockForUpdate()
                    ->first();

                if (! $batch) {
                    throw ValidationException::withMessages([
                        'items.'.$index.'.product_batch_id' => 'Selected batch does not belong to the selected shop.',
                    ]);
                }

                if ((int) $batch->product_id !== (int) $item['product_id']) {
                    throw ValidationException::withMessages([
                        'items.'.$index.'.product_id' => 'Selected product does not match the selected batch.',
                    ]);
                }

                $quantity = (int) $item['quantity'];
                if ((int) $batch->remaining_quantity < $quantity) {
                    throw ValidationException::withMessages([
                        'items.'.$index.'.quantity' => 'Insufficient batch stock. Available: '.$batch->remaining_quantity,
                    ]);
                }

                $purchasePrice = (float) $batch->purchase_price;
                $lineLoss = $quantity * $purchasePrice;

                $this->productBatchService->consumeBatch($batch, $quantity);

                DamageItem::create([
                    'damage_id' => $damage->id,
                    'product_id' => (int) $item['product_id'],
                    'product_batch_id' => (int) $item['product_batch_id'],
                    'quantity' => $quantity,
                    'purchase_price_per_unit' => $purchasePrice,
                    'total_loss' => $lineLoss,
                    'reason' => (string) ($item['reason'] ?? 'damaged'),
                    'reason_note' => $item['reason_note'] ?? null,
                ]);

                $totalQuantity += $quantity;
                $totalLoss += $lineLoss;
            }

            $damage->update([
                'total_quantity' => $totalQuantity,
                'total_loss' => $totalLoss,
            ]);

            $this->capitalService->updateShopCapital((int) $data['shop_id']);

            return $damage->fresh([
                'shop',
                'creator',
                'items.product',
                'items.productBatch',
            ]);
        });
    }

    public function deleteDamage(int $id): void
    {
        DB::transaction(function () use ($id) {
            $damage = Damage::with(['items.productBatch'])->findOrFail($id);

            foreach ($damage->items as $item) {
                $batch = ProductBatch::withTrashed()->find($item->product_batch_id);
                if ($batch) {
                    $this->productBatchService->restoreBatch($batch, (int) $item->quantity);
                }

                $item->delete();
            }

            $damage->delete();
            $this->capitalService->updateShopCapital((int) $damage->shop_id);
        });
    }

    private function generateReferenceNumber(int $id, string $damageDate): string
    {
        $date = date('Ymd', strtotime($damageDate));

        return 'DMG-'.$date.'-'.str_pad((string) $id, 5, '0', STR_PAD_LEFT);
    }
}
