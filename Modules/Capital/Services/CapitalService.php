<?php

namespace Modules\Capital\Services;

use Modules\Capital\Models\Capital;
use Modules\Product\Models\ProductBatch;
use Modules\Shop\Models\Shop;

class CapitalService
{
    /**
     * Calculate total inventory value for a shop using batch-level pricing (FIFO model).
     */
    public function calculateShopCapital($shopId)
    {
        return (float) ProductBatch::query()
            ->where('shop_id', $shopId)
            ->selectRaw('COALESCE(SUM(remaining_quantity * purchase_price), 0) as total')
            ->value('total');
    }

    public function updateShopCapital($shopId)
    {
        $totalCapital = $this->calculateShopCapital($shopId);
        Capital::updateOrCreate(
            ['shop_id' => $shopId],
            ['total_capital' => $totalCapital]
        );
        return $totalCapital;
    }

    public function updateAllShopsCapital(array $shopIds = [])
    {
        $query = Shop::query();
        if (!empty($shopIds)) {
            $query->whereIn('id', $shopIds);
        }
        $shops = $query->get();
        $results = [];
        foreach ($shops as $shop) {
            $results[$shop->id] = $this->updateShopCapital($shop->id);
        }
        return $results;
    }

    public function getAllShopCapitals(array $shopIds = [])
    {
        $query = Capital::with([
            'shop',
            'shop.batches' => function ($q) {
                $q->where('remaining_quantity', '>', 0)->with('product');
            }
        ])->whereHas('shop');
        if (!empty($shopIds)) {
            $query->whereIn('shop_id', $shopIds);
        }
        return $query->get();
    }

    public function getShopCapital($shopId)
    {
        return Capital::where('shop_id', $shopId)->first();
    }
}
