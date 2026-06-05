<?php

namespace Modules\Product\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductBatch;

class ProductBatchService
{
    public function createBatch(Product $product, array $attributes): ProductBatch
    {
        $batch = ProductBatch::create([
            'product_id' => $product->id,
            'shop_id' => $product->shop_id,
            'batch_code' => $attributes['batch_code'] ?? null,
            'source_type' => $attributes['source_type'] ?? 'manual',
            'source_id' => $attributes['source_id'] ?? null,
            'attribute_values' => $this->normalizeAttributeValues(
                $attributes['attribute_values'] ?? $this->getDefaultProductAttributes($product)
            ),
            'purchase_price' => $attributes['purchase_price'],
            'initial_quantity' => $attributes['initial_quantity'],
            'remaining_quantity' => $attributes['remaining_quantity'] ?? $attributes['initial_quantity'],
            'batch_date' => $attributes['batch_date'] ?? now()->toDateString(),
            'note' => $attributes['note'] ?? null,
        ]);

        if (!$batch->batch_code) {
            $batch->batch_code = $this->generateBatchCode((int) $batch->id, (string) $batch->batch_date);
            $batch->save();
        }

        $this->recalculateProductStock($product);

        return $batch;
    }

    public function consumeBatch(ProductBatch $batch, int $quantity): void
    {
        if ($quantity <= 0) {
            return;
        }

        if ($batch->remaining_quantity < $quantity) {
            throw new \RuntimeException('Insufficient batch stock.');
        }

        $batch->decrement('remaining_quantity', $quantity);
        $this->recalculateProductStock($batch->product);
    }

    public function restoreBatch(ProductBatch $batch, int $quantity): void
    {
        if ($quantity <= 0) {
            return;
        }

        $newQuantity = $batch->remaining_quantity + $quantity;
        $batch->remaining_quantity = min($newQuantity, $batch->initial_quantity);
        $batch->save();

        $this->recalculateProductStock($batch->product);
    }

    public function syncManualStockQuantity(Product $product, int $targetQuantity, float $referencePurchasePrice): void
    {
        $currentQuantity = (int) $this->getAvailableStock($product);

        if ($targetQuantity === $currentQuantity) {
            return;
        }

        if ($targetQuantity > $currentQuantity) {
            $delta = $targetQuantity - $currentQuantity;

            $this->createBatch($product, [
                'source_type' => 'adjustment_in',
                'purchase_price' => $referencePurchasePrice,
                'initial_quantity' => $delta,
                'batch_date' => now()->toDateString(),
                'note' => 'Manual stock increase from product edit.',
            ]);

            return;
        }

        $this->consumeByFifo($product, $currentQuantity - $targetQuantity);
    }

    public function consumeByFifo(Product $product, int $quantity): Collection
    {
        if ($quantity <= 0) {
            return collect();
        }

        $batches = ProductBatch::query()
            ->where('product_id', $product->id)
            ->where('remaining_quantity', '>', 0)
            ->orderBy('batch_date')
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        $remainingToConsume = $quantity;
        $consumed = collect();

        foreach ($batches as $batch) {
            if ($remainingToConsume <= 0) {
                break;
            }

            $take = min($remainingToConsume, (int) $batch->remaining_quantity);
            if ($take <= 0) {
                continue;
            }

            $batch->decrement('remaining_quantity', $take);
            $remainingToConsume -= $take;

            $consumed->push([
                'batch_id' => (int) $batch->id,
                'batch_code' => (string) $batch->batch_code,
                'quantity' => $take,
                'purchase_price' => (float) $batch->purchase_price,
            ]);
        }

        if ($remainingToConsume > 0) {
            throw new \RuntimeException('Insufficient stock across product batches.');
        }

        $this->recalculateProductStock($product);

        return $consumed;
    }

    public function recalculateProductStock(Product $product): int
    {
        $stock = (int) ProductBatch::query()
            ->where('product_id', $product->id)
            ->sum('remaining_quantity');

        $latestPurchasePrice = ProductBatch::query()
            ->where('product_id', $product->id)
            ->where('remaining_quantity', '>', 0)
            ->orderByDesc('batch_date')
            ->orderByDesc('id')
            ->value('purchase_price');

        $product->stock_quantity = $stock;

        if ($latestPurchasePrice !== null) {
            $product->purchase_price = $latestPurchasePrice;
        }

        $product->save();

        return $stock;
    }

    public function getAvailableStock(Product $product): int
    {
        return (int) ProductBatch::query()
            ->where('product_id', $product->id)
            ->sum('remaining_quantity');
    }

    private function generateBatchCode(int $id, string $date): string
    {
        $datePart = Carbon::parse($date)->format('Ymd');

        return 'B-' . $datePart . '-' . str_pad((string) $id, 5, '0', STR_PAD_LEFT);
    }

    private function getDefaultProductAttributes(Product $product): array
    {
        return $product->dynamicValues()
            ->with('dynamicField:id,field_key,label')
            ->get()
            ->filter(fn ($item) => $item->dynamicField && filled($item->value))
            ->map(function ($item) {
                return [
                    'field_id' => (int) $item->product_dynamic_field_id,
                    'field_key' => (string) ($item->dynamicField->field_key ?? ''),
                    'label' => (string) ($item->dynamicField->label ?? 'Attribute'),
                    'value' => (string) $item->value,
                ];
            })
            ->values()
            ->all();
    }

    private function normalizeAttributeValues(array $attributes): array
    {
        return collect($attributes)
            ->filter(fn ($item) => is_array($item) && filled($item['value'] ?? null))
            ->map(function (array $item) {
                return [
                    'field_id' => isset($item['field_id']) ? (int) $item['field_id'] : null,
                    'field_key' => (string) ($item['field_key'] ?? ''),
                    'label' => (string) ($item['label'] ?? 'Attribute'),
                    'value' => (string) ($item['value'] ?? ''),
                ];
            })
            ->values()
            ->all();
    }
}
