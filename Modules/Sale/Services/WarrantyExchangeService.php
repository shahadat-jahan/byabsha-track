<?php

namespace Modules\Sale\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Capital\Services\CapitalService;
use Modules\Product\Models\ProductBatch;
use Modules\Product\Services\ProductBatchService;
use Modules\Sale\Models\Sale;
use Modules\Sale\Models\SaleExchange;
use Modules\Sale\Models\SaleWarranty;

class WarrantyExchangeService
{
    public function __construct(
        protected ProductBatchService $productBatchService,
        protected CapitalService $capitalService,
    ) {}

    public function getWarranties(array $filters = [], int $perPage = 20)
    {
        $query = SaleWarranty::with([
            'sale.product' => fn ($q) => $q->withTrashed(),
            'shop' => fn ($q) => $q->withTrashed(),
            'creator' => fn ($q) => $q->withTrashed(),
        ])->latest('id');

        if (! empty($filters['shop_ids'])) {
            $query->whereIn('shop_id', $filters['shop_ids']);
        }

        if (! empty($filters['shop_id'])) {
            $query->where('shop_id', (int) $filters['shop_id']);
        }

        if (! empty($filters['status'])) {
            if ($filters['status'] === 'expired') {
                $query->where('status', 'active')->whereDate('end_date', '<', now()->toDateString());
            } else {
                $query->where('status', $filters['status']);
            }
        }

        return $query->paginate($perPage)->appends($filters);
    }

    public function createWarranty(array $data): SaleWarranty
    {
        return DB::transaction(function () use ($data) {
            $sale = Sale::with(['product', 'shop'])->findOrFail((int) $data['sale_id']);

            if ((int) $sale->shop_id !== (int) $data['shop_id']) {
                throw ValidationException::withMessages([
                    'sale_id' => 'Selected sale does not belong to selected shop.',
                ]);
            }

            $warranty = SaleWarranty::create([
                'sale_id' => (int) $sale->id,
                'shop_id' => (int) $sale->shop_id,
                'warranty_code' => 'TMP',
                'source_type' => 'manual',
                'coverage_quantity' => (int) $sale->quantity,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'status' => 'active',
                'terms' => $data['terms'] ?? null,
                'created_by' => $data['created_by'] ?? null,
            ]);

            $warranty->warranty_code = $this->generateWarrantyCode((int) $warranty->id, (string) $warranty->start_date);
            $warranty->save();

            return $warranty->fresh(['sale.product', 'shop', 'creator']);
        });
    }

    public function markWarrantyClaimed(int $id, ?string $claimNote = null): SaleWarranty
    {
        $warranty = SaleWarranty::findOrFail($id);

        if ($warranty->status !== 'active') {
            throw ValidationException::withMessages([
                'warranty' => 'Only active service records can be claimed.',
            ]);
        }

        if ($warranty->end_date && $warranty->end_date->isPast()) {
            throw ValidationException::withMessages([
                'warranty' => 'Service period has expired for this sale item.',
            ]);
        }

        $warranty->update([
            'status' => 'claimed',
            'claim_note' => $claimNote,
            'claimed_at' => now(),
        ]);

        return $warranty->fresh(['sale.product', 'shop', 'creator']);
    }

    public function syncAutoWarrantyForSale(Sale $sale, ?int $createdBy = null): ?SaleWarranty
    {
        $sale->loadMissing('product');
        $product = $sale->product;

        if (! $product || ! (bool) $product->has_free_service) {
            SaleWarranty::query()
                ->where('sale_id', (int) $sale->id)
                ->where('source_type', 'auto_free_service')
                ->delete();

            return null;
        }

        $durationValue = (int) ($product->free_service_duration_value ?? 0);
        $durationUnit = (string) ($product->free_service_duration_unit ?? '');
        if ($durationValue <= 0 || ! in_array($durationUnit, ['day', 'month', 'year'], true)) {
            return null;
        }

        $startDate = Carbon::parse($sale->sale_date)->startOfDay();
        $endDate = $this->calculateServiceEndDate($startDate, $durationValue, $durationUnit);

        $warranty = SaleWarranty::query()
            ->where('sale_id', (int) $sale->id)
            ->where('source_type', 'auto_free_service')
            ->first();

        if (! $warranty) {
            $warranty = new SaleWarranty;
            $warranty->sale_id = (int) $sale->id;
            $warranty->shop_id = (int) $sale->shop_id;
            $warranty->warranty_code = 'TMP';
        }

        $warranty->source_type = 'auto_free_service';
        $warranty->duration_snapshot_value = $durationValue;
        $warranty->duration_snapshot_unit = $durationUnit;
        $warranty->coverage_quantity = (int) $sale->quantity;
        $warranty->start_date = $startDate->toDateString();
        $warranty->end_date = $endDate->toDateString();
        $warranty->status = $endDate->isPast() ? 'expired' : 'active';
        $warranty->terms = $product->free_service_terms;
        $warranty->created_by = $createdBy;
        $warranty->save();

        if ($warranty->warranty_code === 'TMP') {
            $warranty->warranty_code = $this->generateWarrantyCode((int) $warranty->id, (string) $warranty->start_date);
            $warranty->save();
        }

        return $warranty->fresh(['sale.product', 'shop', 'creator']);
    }

    private function calculateServiceEndDate(Carbon $startDate, int $durationValue, string $durationUnit): Carbon
    {
        $endDate = $startDate->copy();

        return match ($durationUnit) {
            'day' => $endDate->addDays($durationValue),
            'month' => $endDate->addMonthsNoOverflow($durationValue),
            'year' => $endDate->addYearsNoOverflow($durationValue),
            default => $endDate,
        };
    }

    public function voidWarranty(int $id, ?string $claimNote = null): SaleWarranty
    {
        $warranty = SaleWarranty::findOrFail($id);
        $warranty->update([
            'status' => 'void',
            'claim_note' => $claimNote,
        ]);

        return $warranty->fresh(['sale.product', 'shop', 'creator']);
    }

    public function getExchanges(array $filters = [], int $perPage = 20)
    {
        $query = SaleExchange::with([
            'sale.product' => fn ($q) => $q->withTrashed(),
            'shop' => fn ($q) => $q->withTrashed(),
            'originalBatch.product' => fn ($q) => $q->withTrashed(),
            'replacementBatch.product' => fn ($q) => $q->withTrashed(),
            'creator' => fn ($q) => $q->withTrashed(),
        ])->latest('exchange_date')->latest('id');

        if (! empty($filters['shop_ids'])) {
            $query->whereIn('shop_id', $filters['shop_ids']);
        }

        if (! empty($filters['shop_id'])) {
            $query->where('shop_id', (int) $filters['shop_id']);
        }

        if (! empty($filters['type'])) {
            $query->where('exchange_type', $filters['type']);
        }

        return $query->paginate($perPage)->appends($filters);
    }

    public function createExchange(array $data): SaleExchange
    {
        return DB::transaction(function () use ($data) {
            $sale = Sale::with(['product', 'productBatch'])->findOrFail((int) $data['sale_id']);

            if ((int) $sale->shop_id !== (int) $data['shop_id']) {
                throw ValidationException::withMessages([
                    'sale_id' => 'Selected sale does not belong to selected shop.',
                ]);
            }

            if (! $sale->product_batch_id) {
                throw ValidationException::withMessages([
                    'sale_id' => 'Selected sale has no batch mapping. Exchange cannot be tracked safely.',
                ]);
            }

            $quantity = (int) $data['quantity'];
            $alreadyExchanged = (int) SaleExchange::query()
                ->where('sale_id', $sale->id)
                ->where('status', 'completed')
                ->sum('quantity');

            $remainingExchangeable = (int) $sale->quantity - $alreadyExchanged;
            if ($remainingExchangeable <= 0 || $quantity > $remainingExchangeable) {
                throw ValidationException::withMessages([
                    'quantity' => 'Exchange quantity exceeds remaining exchangeable amount. Remaining: '.max($remainingExchangeable, 0),
                ]);
            }

            $originalBatch = ProductBatch::withTrashed()->lockForUpdate()->find($sale->product_batch_id);
            if (! $originalBatch) {
                throw ValidationException::withMessages([
                    'sale_id' => 'Original batch not found for this sale.',
                ]);
            }

            $exchangeType = (string) $data['exchange_type'];
            $replacementBatch = null;

            if ($exchangeType === 'replacement') {
                if (empty($data['replacement_batch_id'])) {
                    throw ValidationException::withMessages([
                        'replacement_batch_id' => 'Replacement batch is required for replacement exchange.',
                    ]);
                }

                $replacementBatch = ProductBatch::query()
                    ->where('id', (int) $data['replacement_batch_id'])
                    ->where('shop_id', (int) $data['shop_id'])
                    ->lockForUpdate()
                    ->first();

                if (! $replacementBatch) {
                    throw ValidationException::withMessages([
                        'replacement_batch_id' => 'Replacement batch does not belong to selected shop.',
                    ]);
                }

                if ((int) $replacementBatch->remaining_quantity < $quantity) {
                    throw ValidationException::withMessages([
                        'replacement_batch_id' => 'Insufficient replacement batch stock. Available: '.$replacementBatch->remaining_quantity,
                    ]);
                }
            }

            $this->productBatchService->restoreBatch($originalBatch, $quantity);

            if ($replacementBatch) {
                $this->productBatchService->consumeBatch($replacementBatch, $quantity);
            }

            $originalCost = (float) $originalBatch->purchase_price;
            $replacementCost = $replacementBatch ? (float) $replacementBatch->purchase_price : 0.0;
            $costDifference = $replacementBatch
                ? ($replacementCost - $originalCost) * $quantity
                : -($originalCost * $quantity);

            $exchange = SaleExchange::create([
                'shop_id' => (int) $sale->shop_id,
                'sale_id' => (int) $sale->id,
                'original_batch_id' => (int) $originalBatch->id,
                'replacement_batch_id' => $replacementBatch ? (int) $replacementBatch->id : null,
                'quantity' => $quantity,
                'exchange_date' => $data['exchange_date'],
                'exchange_type' => $exchangeType,
                'reason' => (string) ($data['reason'] ?? 'defective'),
                'note' => $data['note'] ?? null,
                'cost_difference' => $costDifference,
                'status' => 'completed',
                'created_by' => $data['created_by'] ?? null,
            ]);

            $this->capitalService->updateShopCapital((int) $sale->shop_id);

            return $exchange->fresh([
                'sale.product',
                'shop',
                'originalBatch.product',
                'replacementBatch.product',
                'creator',
            ]);
        });
    }

    private function generateWarrantyCode(int $id, string $startDate): string
    {
        $date = date('Ymd', strtotime($startDate));

        return 'WAR-'.$date.'-'.str_pad((string) $id, 5, '0', STR_PAD_LEFT);
    }
}
