@extends('layouts.app')

@section('title', __('brand::brand.show_title'))

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <h1 class="page-title">{{ __('brand::brand.show_title') }}</h1>
    <a href="{{ route('brand.index') }}" class="btn btn-secondary">{{ __('brand::brand.back') }}</a>
</div>

<div class="content-card">
    <table class="table table-borderless">
        <tbody>
            <tr>
                <th style="width: 220px;">{{ __('brand::brand.name') }}</th>
                <td>{{ $brand->name }}</td>
            </tr>
            <tr>
                <th>{{ __('brand::brand.product_count') }}</th>
                <td>{{ $brand->products_count }}</td>
            </tr>
        </tbody>
    </table>

    <div class="d-flex gap-2 mt-3">
        <a href="{{ route('brand.edit', $brand->id) }}" class="btn btn-warning">{{ __('app.edit') }}</a>
        <a href="{{ route('brand.index') }}" class="btn btn-secondary">{{ __('brand::brand.back') }}</a>
    </div>
</div>
@endsection
