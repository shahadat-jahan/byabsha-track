@extends('layouts.app')

@section('title', __('sale.product_sales_title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h1 class="h3 mb-1">{{ __('sale.product_sales_title') }}</h1>
        <p class="text-muted mb-0">{{ __('sale.product_sales_subtitle') }}</p>
    </div>
    <a href="{{ route('sale.index', ['shop_id' => $product->shop_id]) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> {{ __('sale.back_to_list') }}
    </a>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="text-muted small">{{ __('sale.product') }}</div>
                <div class="fw-semibold">{{ $product->name }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">{{ __('sale.shop') }}</div>
                <div class="fw-semibold">{{ $product->shop?->name ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">{{ __('sale.category') }}</div>
                <div class="fw-semibold">{{ $product->category ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="text-muted small">{{ __('sale.quantity') }}</div>
                <div class="h5 mb-0">{{ (int) ($totals->total_quantity ?? 0) }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">{{ __('sale.total_amount') }}</div>
                <div class="h5 mb-0">{{ number_format((float) ($totals->total_amount ?? 0), 2) }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">{{ __('sale.profit') }}</div>
                <div class="h5 mb-0 {{ (float) ($totals->total_profit ?? 0) < 0 ? 'text-danger' : 'text-success' }}">
                    {{ number_format((float) ($totals->total_profit ?? 0), 2) }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th>{{ __('sale.col_date') }}</th>
                        <th>{{ __('sale.table_batch') }}</th>
                        <th>{{ __('sale.col_quantity') }}</th>
                        <th>{{ __('sale.col_sale_price') }}</th>
                        <th>{{ __('sale.table_buying_price') }}</th>
                        <th>{{ __('sale.quick_sale_discount') }}</th>
                        <th>{{ __('sale.col_total') }}</th>
                        <th>{{ __('sale.col_profit') }}</th>
                        <th>{{ __('sale.col_customer_name') }}</th>
                        <th>{{ __('sale.col_customer_phone') }}</th>
                        <th>{{ __('sale.col_customer_address') }}</th>
                        <th>{{ __('sale.free_service_start') }}</th>
                        <th>{{ __('sale.free_service_expiry') }}</th>
                        <th>{{ __('sale.free_service_status') }}</th>
                        <th>{{ __('sale.col_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        @php
                            $serviceRecord = $sale->warranties->first();
                            $serviceStatus = $serviceRecord
                                ? (($serviceRecord->status === 'active' && $serviceRecord->end_date->isPast()) ? 'expired' : $serviceRecord->status)
                                : null;
                        @endphp
                        <tr>
                            <td>{{ optional($sale->sale_date)->format('d M Y') ?? '-' }}</td>
                            <td>{{ $sale->productBatch?->batch_code ?? '-' }}</td>
                            <td>{{ (int) $sale->quantity }}</td>
                            <td>{{ number_format((float) $sale->sale_price, 2) }}</td>
                            <td>{{ number_format((float) ($sale->purchase_price_per_unit ?? $sale->productBatch?->purchase_price ?? 0), 2) }}</td>
                            <td>{{ number_format((float) ($sale->discount ?? 0), 2) }}</td>
                            <td>{{ number_format((float) $sale->total_amount, 2) }}</td>
                            <td class="{{ (float) $sale->profit < 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format((float) $sale->profit, 2) }}
                            </td>
                            <td>{{ $sale->customer_name ?: '-' }}</td>
                            <td>{{ $sale->customer_phone ?: '-' }}</td>
                            <td>{{ $sale->customer_address ?: '-' }}</td>
                            <td>{{ $serviceRecord?->start_date?->format('d M Y') ?? '-' }}</td>
                            <td>{{ $serviceRecord?->end_date?->format('d M Y') ?? '-' }}</td>
                            <td>
                                @if($serviceStatus)
                                    <span class="badge bg-{{ $serviceStatus === 'active' ? 'success' : ($serviceStatus === 'claimed' ? 'info' : ($serviceStatus === 'expired' ? 'warning text-dark' : 'secondary')) }}">
                                        {{ __('sale.status_' . $serviceStatus) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-info js-sale-detail-btn"
                                    title="{{ __('sale.show_title') }}"
                                    data-sale-id="{{ $sale->id }}"
                                    data-sale-date="{{ optional($sale->sale_date)->format('d M Y') ?? '-' }}"
                                    data-batch-code="{{ $sale->productBatch?->batch_code ?? '-' }}"
                                    data-quantity="{{ (int) $sale->quantity }}"
                                    data-sale-price="{{ number_format((float) $sale->sale_price, 2, '.', '') }}"
                                    data-purchase-price="{{ number_format((float) ($sale->purchase_price_per_unit ?? 0), 2, '.', '') }}"
                                    data-discount="{{ number_format((float) ($sale->discount ?? 0), 2, '.', '') }}"
                                    data-total-amount="{{ number_format((float) $sale->total_amount, 2, '.', '') }}"
                                    data-profit="{{ number_format((float) $sale->profit, 2, '.', '') }}"
                                    data-customer-name="{{ $sale->customer_name ?: '-' }}"
                                    data-customer-phone="{{ $sale->customer_phone ?: '-' }}"
                                    data-customer-address="{{ $sale->customer_address ?: '-' }}"
                                    data-service-start="{{ $serviceRecord?->start_date?->format('d M Y') ?? '-' }}"
                                    data-service-expiry="{{ $serviceRecord?->end_date?->format('d M Y') ?? '-' }}"
                                    data-service-status="{{ $serviceStatus ? __('sale.status_' . $serviceStatus) : '-' }}"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="text-center py-4 text-muted">{{ __('sale.no_product_sales') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($sales->hasPages())
    <div class="mt-3">
        {{ $sales->links() }}
    </div>
@endif

<div class="modal fade" id="saleDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('sale.show_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">{{ __('sale.sale_date') }}</div>
                        <div class="fw-semibold" id="modalSaleDate">-</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">{{ __('sale.quantity_sold') }}</div>
                        <div class="fw-semibold" id="modalQuantity">-</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">{{ __('sale.table_batch') }}</div>
                        <div class="fw-semibold" id="modalBatchCode">-</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">{{ __('sale.sale_price_unit') }}</div>
                        <div class="fw-semibold" id="modalSalePrice">-</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">{{ __('sale.table_buying_price') }}</div>
                        <div class="fw-semibold" id="modalPurchasePrice">-</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">{{ __('sale.quick_sale_discount') }}</div>
                        <div class="fw-semibold" id="modalDiscount">-</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">{{ __('sale.total_amount') }}</div>
                        <div class="fw-semibold" id="modalTotalAmount">-</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">{{ __('sale.profit') }}</div>
                        <div class="fw-semibold" id="modalProfit">-</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('sale.customer_name') }}</div>
                        <div class="fw-semibold" id="modalCustomerName">-</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('sale.customer_phone') }}</div>
                        <div class="fw-semibold" id="modalCustomerPhone">-</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('sale.customer_address') }}</div>
                        <div class="fw-semibold" id="modalCustomerAddress">-</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('sale.free_service_start') }}</div>
                        <div class="fw-semibold" id="modalServiceStart">-</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('sale.free_service_expiry') }}</div>
                        <div class="fw-semibold" id="modalServiceExpiry">-</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('sale.free_service_status') }}</div>
                        <div class="fw-semibold" id="modalServiceStatus">-</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalElement = document.getElementById('saleDetailsModal');
        const detailsModal = new bootstrap.Modal(modalElement);

        const formatNumber = (value) => {
            const num = Number(value);
            if (!Number.isFinite(num)) {
                return '0.00';
            }
            return num.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
        };

        document.addEventListener('click', function (event) {
            const button = event.target.closest('.js-sale-detail-btn');
            if (!button) {
                return;
            }

            document.getElementById('modalSaleDate').textContent = button.dataset.saleDate || '-';
            document.getElementById('modalQuantity').textContent = button.dataset.quantity || '-';
            document.getElementById('modalBatchCode').textContent = button.dataset.batchCode || '-';
            document.getElementById('modalSalePrice').textContent = formatNumber(button.dataset.salePrice);
            document.getElementById('modalPurchasePrice').textContent = formatNumber(button.dataset.purchasePrice);
            document.getElementById('modalDiscount').textContent = formatNumber(button.dataset.discount);
            document.getElementById('modalTotalAmount').textContent = formatNumber(button.dataset.totalAmount);

            const profitValue = Number(button.dataset.profit || 0);
            const modalProfit = document.getElementById('modalProfit');
            modalProfit.textContent = formatNumber(profitValue);
            modalProfit.classList.remove('text-success', 'text-danger');
            if (profitValue < 0) {
                modalProfit.classList.add('text-danger');
            } else {
                modalProfit.classList.add('text-success');
            }

            document.getElementById('modalCustomerName').textContent = button.dataset.customerName || '-';
            document.getElementById('modalCustomerPhone').textContent = button.dataset.customerPhone || '-';
            document.getElementById('modalCustomerAddress').textContent = button.dataset.customerAddress || '-';
            document.getElementById('modalServiceStart').textContent = button.dataset.serviceStart || '-';
            document.getElementById('modalServiceExpiry').textContent = button.dataset.serviceExpiry || '-';
            document.getElementById('modalServiceStatus').textContent = button.dataset.serviceStatus || '-';

            detailsModal.show();
        });
    });
</script>
@endpush
