@extends('layouts.admin')

@section('title', $product ? 'Edit Produk' : 'Tambah Produk')

@section('content')
<div class="page-card" style="max-width:760px;">
    <div class="page-card-header">
        <h6 class="page-card-title">
            <i class="fa-solid fa-{{ $product ? 'pen' : 'plus' }} me-2" style="color:var(--primary);"></i>
            {{ $product ? 'Edit Produk' : 'Tambah Produk Baru' }}
        </h6>
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:50px;">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <form method="POST" action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($product) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-select">
                    @foreach (['original', 'pedas', 'gurih', 'manis'] as $k)
                    <option value="{{ $k }}" {{ old('category', $product->category ?? '') === $k ? 'selected' : '' }}>{{ ucfirst($k) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi Produk</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="price" class="form-control" min="0" value="{{ old('price', $product->price ?? '') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Stok <span class="text-danger">*</span></label>
                <input type="number" name="stock" class="form-control" min="0" value="{{ old('stock', $product->stock ?? 0) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Berat (gram)</label>
                <input type="number" name="berat_gram" class="form-control" min="0" value="{{ old('berat_gram', $product->berat_gram ?? 250) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Gambar Produk</label>
                <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this,'imgPreview')">
                <div class="mt-2">
                    <img id="imgPreview"
                         src="{{ ($product && $product->image) ? asset('storage/' . $product->image) : '' }}"
                         class="img-preview"
                         style="{{ ($product && $product->image) ? '' : 'display:none;' }}">
                </div>
                <div class="form-text">Format: JPG/PNG/WEBP. Maks 2MB.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="is_active" class="form-select">
                    <option value="aktif" {{ old('is_active', ($product->is_active ?? true) ? 'aktif' : 'nonaktif') === 'aktif' ? 'selected' : '' }}>✅ Aktif</option>
                    <option value="nonaktif" {{ old('is_active', ($product->is_active ?? true) ? 'aktif' : 'nonaktif') === 'nonaktif' ? 'selected' : '' }}>⛔ Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn-orange px-4 py-2">
                <i class="fa-solid fa-floppy-disk me-2"></i> {{ $product ? 'Update Produk' : 'Simpan Produk' }}
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary px-4 py-2" style="border-radius:8px;">Batal</a>
        </div>
    </form>
</div>
@endsection
