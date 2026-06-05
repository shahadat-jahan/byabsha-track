<div class="shop-details-modal-content">
    <h4 class="shop-details-title mb-3"><i class="bi bi-shop-window"></i> {{ $shop->name }}</h4>
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="detail-box">
                <span class="detail-label">Total Products</span>
                <span class="detail-value">{{ $shop->products->count() }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="detail-box">
                <span class="detail-label">Total Sales</span>
                <span class="detail-value">{{ $shop->sales->count() }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="detail-box">
                <span class="detail-label">Current Capital</span>
                <span class="detail-value">{{ currency_symbol() }} {{ $capital ? number_format($capital->total_capital, 2) : '0.00' }}</span>
                <span class="detail-formula">&Sigma; (Stock Qty &times; Purchase Price)</span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="detail-box">
                <span class="detail-label">Today's Sales</span>
                <span class="detail-value">{{ currency_symbol() }} {{ number_format($todaySales, 2) }}</span>
                <span class="detail-formula">&Sigma; (Qty &times; Sale Price) &mdash; today</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="detail-box">
                <span class="detail-label">Today's Profit</span>
                <span class="detail-value">{{ currency_symbol() }} {{ number_format($todayProfit, 2) }}</span>
                <span class="detail-formula">&Sigma; (Sale &minus; Purchase) &times; Qty &mdash; today</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="detail-box">
                <span class="detail-label">Monthly Profit</span>
                <span class="detail-value">{{ currency_symbol() }} {{ number_format($monthlyProfit, 2) }}</span>
                <span class="detail-formula">&Sigma; profit &mdash; this month</span>
            </div>
        </div>
    </div>
    <hr>
    <h5 class="mt-4 mb-2">Recent Products</h5>
    <ul class="list-group mb-3">
        @foreach($shop->products->take(5) as $product)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $product->name }}
                <span class="badge stock-badge">Stock: {{ $product->stock_quantity }}</span>
            </li>
        @endforeach
        @if($shop->products->count() == 0)
            <li class="list-group-item text-muted">No products found.</li>
        @endif
    </ul>
    <h5 class="mt-4 mb-2">Recent Sales</h5>
    <ul class="list-group">
        @foreach($shop->sales->sortByDesc('sale_date')->take(5) as $sale)
            <li class="list-group-item">
                <span class="fw-bold">{{ $sale->product->name ?? 'N/A' }}</span> - {{ currency_symbol() }}{{ number_format($sale->total_amount, 2) }} <span class="text-muted">({{ $sale->sale_date }})</span>
            </li>
        @endforeach
        @if($shop->sales->count() == 0)
            <li class="list-group-item text-muted">No sales found.</li>
        @endif
    </ul>
</div>
<style>
.shop-details-modal-content .shop-details-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.shop-details-modal-content .shop-details-title i {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #0f766e;
    background: rgba(15, 118, 110, 0.12);
    border: 1px solid rgba(15, 118, 110, 0.22);
}

.shop-details-modal-content .detail-box {
    background: #f8fbff;
    border: 1px solid #d8e4ee;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
    text-align: center;
    height: 100%;
}
.shop-details-modal-content .detail-label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #64748b;
    margin-bottom: 0.2rem;
}
.shop-details-modal-content .detail-value {
    font-size: 1.15rem;
    font-weight: 700;
    color: #0f172a;
}
.shop-details-modal-content .detail-formula {
    display: block;
    font-size: 0.68rem;
    color: #94a3b8;
    margin-top: 0.2rem;
}

.shop-details-modal-content .list-group-item {
    border-color: #e2e8f0;
    padding-top: 0.65rem;
    padding-bottom: 0.65rem;
}

.shop-details-modal-content .stock-badge {
    background: rgba(15, 118, 110, 0.12);
    color: #155e75;
    font-weight: 600;
}
</style>
