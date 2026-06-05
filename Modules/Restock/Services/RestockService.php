<?php

namespace Modules\Restock\Services;

use Modules\Restock\Models\Restock;
use Modules\Product\Models\Product;
use Modules\Capital\Services\CapitalService;
use Modules\Product\Services\ProductBatchService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RestockService
{
    protected CapitalService $capitalService;
    protected ProductBatchService $productBatchService;

    public function __construct(CapitalService $capitalService, ProductBatchService $productBatchService)
    {
        $this->capitalService = $capitalService;
        $this->productBatchService = $productBatchService;
    }

    public function getRestocks(array $filters = [], int $perPage = 15)
    {
        $query = Restock::with([
            'product' => fn ($query) => $query->withTrashed(),
            'shop' => fn ($query) => $query->withTrashed(),
            'productBatch' => fn ($query) => $query->withTrashed(),
        ])->latest('restock_date');

        // Always scope to allowed shop IDs when provided
        if (!empty($filters['shop_ids'])) {
            $query->whereIn('shop_id', $filters['shop_ids']);
        }

        if (!empty($filters['shop_id'])) {
            $query->where('shop_id', $filters['shop_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('restock_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('restock_date', '<=', $filters['date_to']);
        }

        return $query->paginate($perPage)->appends($filters);
    }

    public function storeRestock(array $data): Restock
    {
        return DB::transaction(function () use ($data) {
            $product = Product::findOrFail($data['product_id']);

            $totalCost = $data['quantity'] * $data['purchase_price_per_unit'];

            $restock = Restock::create([
                'product_id' => $data['product_id'],
                'shop_id' => $data['shop_id'],
                'quantity' => $data['quantity'],
                'remaining_quantity' => $data['quantity'],
                'purchase_price_per_unit' => $data['purchase_price_per_unit'],
                'total_cost' => $totalCost,
                'restock_date' => $data['restock_date'],
                'note' => $data['note'] ?? null,
            ]);

            $batch = $this->productBatchService->createBatch($product, [
                'source_type' => 'restock',
                'source_id' => (int) $restock->id,
                'attribute_values' => $data['attribute_values'] ?? [],
                'purchase_price' => (float) $data['purchase_price_per_unit'],
                'initial_quantity' => (int) $data['quantity'],
                'batch_date' => $data['restock_date'],
                'note' => $data['note'] ?? 'Batch created from restock.',
            ]);

            $restock->update([
                'product_batch_id' => $batch->id,
            ]);

            $this->capitalService->updateShopCapital($data['shop_id']);

            return $restock;
        });
    }

    public function getRestock(int $id): Restock
    {
        return Restock::with([
            'product' => fn ($query) => $query->withTrashed(),
            'shop' => fn ($query) => $query->withTrashed(),
            'productBatch' => fn ($query) => $query->withTrashed(),
        ])->findOrFail($id);
    }

    public function updateRestock(int $id, array $data): Restock
    {
        return DB::transaction(function () use ($id, $data) {
            $restock = Restock::findOrFail($id);
            $product = Product::withTrashed()->findOrFail($data['product_id']);
            $oldProduct = Product::withTrashed()->findOrFail($restock->product_id);
            $oldShopId = (int) $restock->shop_id;
            $batch = $restock->productBatch;

            // Units already consumed (sold) from this batch must remain consumed.
            $consumed = $restock->quantity - $restock->remaining_quantity;
            $newQuantity = (int) $data['quantity'];

            if ($newQuantity < $consumed) {
                throw new \RuntimeException(
                    "Cannot reduce batch quantity below already-sold units ({$consumed})."
                );
            }

            if ($batch) {
                $soldFromBatch = (int) $batch->initial_quantity - (int) $batch->remaining_quantity;
                if ($soldFromBatch > 0) {
                    throw ValidationException::withMessages([
                        'product_id' => __('restock.batch_already_sold') ?: 'This restock batch already has sales and cannot be edited.',
                    ]);
                }
            }

            $totalCost = $newQuantity * $data['purchase_price_per_unit'];

            $restock->update([
                'product_id' => $data['product_id'],
                'shop_id' => $data['shop_id'],
                'quantity' => $newQuantity,
                'remaining_quantity' => $newQuantity - $consumed,
                'purchase_price_per_unit' => $data['purchase_price_per_unit'],
                'total_cost' => $totalCost,
                'restock_date' => $data['restock_date'],
                'note' => $data['note'] ?? null,
            ]);

            if ($batch) {
                $batch->update([
                    'product_id' => $data['product_id'],
                    'shop_id' => $data['shop_id'],
                    'source_type' => 'restock',
                    'source_id' => (int) $restock->id,
                    'attribute_values' => $data['attribute_values'] ?? [],
                    'purchase_price' => (float) $data['purchase_price_per_unit'],
                    'initial_quantity' => (int) $data['quantity'],
                    'remaining_quantity' => (int) $data['quantity'],
                    'batch_date' => $data['restock_date'],
                    'note' => $data['note'] ?? null,
                ]);
            } else {
                $batch = $this->productBatchService->createBatch($product, [
                    'source_type' => 'restock',
                    'source_id' => (int) $restock->id,
                    'attribute_values' => $data['attribute_values'] ?? [],
                    'purchase_price' => (float) $data['purchase_price_per_unit'],
                    'initial_quantity' => (int) $data['quantity'],
                    'batch_date' => $data['restock_date'],
                    'note' => $data['note'] ?? null,
                ]);

                $restock->update(['product_batch_id' => $batch->id]);
            }

            $this->productBatchService->recalculateProductStock($oldProduct);
            if ((int) $oldProduct->id !== (int) $product->id) {
                $this->productBatchService->recalculateProductStock($product);
            }

            $this->capitalService->updateShopCapital($oldShopId);
            if ($oldShopId !== (int) $data['shop_id']) {
                $this->capitalService->updateShopCapital((int) $data['shop_id']);
            }

            return $restock;
        });
    }

    public function deleteRestock(int $id): void
    {
        DB::transaction(function () use ($id) {
            Log::debug('Finding restock with ID: ' . $id);
            $restock = Restock::with('productBatch')->findOrFail($id);
            Log::debug('Found restock, deleting...');

            $product = Product::withTrashed()->findOrFail($restock->product_id);

            if ($restock->productBatch) {
                $soldFromBatch = (int) $restock->productBatch->initial_quantity - (int) $restock->productBatch->remaining_quantity;
                if ($soldFromBatch > 0) {
                    throw ValidationException::withMessages([
                        'restock' => __('restock.batch_already_sold') ?: 'This restock batch already has sales and cannot be deleted.',
                    ]);
                }

                $restock->productBatch->delete();
            }

            $restock->delete();
            $this->productBatchService->recalculateProductStock($product);

            $this->capitalService->updateShopCapital($restock->shop_id);
            Log::debug('Restock deletion transaction completed');
        });
    }
}
