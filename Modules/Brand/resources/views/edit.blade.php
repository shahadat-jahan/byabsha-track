@extends('layouts.app')

@section('title', __('brand::brand.edit_title'))

@section('content')
<div class="mb-4">
    <h1 class="page-title">{{ __('brand::brand.edit_title') }}</h1>
</div>

<div class="content-card">
    <form action="{{ route('brand.update', $brand->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">{{ __('brand::brand.name') }} <span class="text-danger">*</span></label>
            <input
                type="text"
                class="form-control @error('name') is-invalid @enderror"
                id="name"
                name="name"
                value="{{ old('name', $brand->name) }}"
                placeholder="{{ __('brand::brand.name_placeholder') }}"
                required
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('brand.index') }}" class="btn btn-secondary">{{ __('app.cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('brand::brand.update_btn') }}</button>
        </div>
    </form>
</div>
@endsection
