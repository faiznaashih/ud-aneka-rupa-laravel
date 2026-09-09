@extends('layouts.shop')

@section('title', 'Produk Kami')

@section('content')

<!-- Page Hero -->
<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Produk</li>
            </ol>
        </nav>
        <h1 class="mb-1"><i class="fa-solid fa-boxes-stacked me-2" style="color:var(--primary)!important;"></i> Produk Kami</h1>
        <p class="text-muted mb-0">Temukan berbagai pilihan kerupuk lezat dari UD Aneka Rupa</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <!-- Search & Filter Bar -->
        <div class="card border-0 shadow-sm mb-4 p-3" style="border-radius:var(--radius);">
            <div class="d-flex flex-column flex-md-row gap-3 align-items-md-center justify-content-between">
                <form method="GET" action="{{ route('products.index') }}" class="search-wrap flex-grow-1">
                    @if ($kategori)
                        <input type="hidden" name="kategori" value="{{ $kategori }}">
                    @endif
                    <input type="text" name="search" class="form-control" placeholder="Cari produk kerupuk..." value="{{ $search }}">
                    <button type="submit" class="search-btn">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>

                <div class="d-flex gap-2 flex-wrap">
                    @php
                        $kategoriList = ['semua' => 'Semua', 'original' => 'Original', 'pedas' => 'Pedas', 'gurih' => 'Gurih', 'manis' => 'Manis'];
                    @endphp
                    @foreach ($kategoriList as $k => $label)
                        @php
                            $isActive = ($k === $kategori || ($k === 'semua' && !$kategori)) ? 'active' : '';
                            $url = route('products.index', array_filter(['search' => $search ?: null, 'kategori' => $k === 'semua' ? null : $k]));
                        @endphp
                        <a href="{{ $url }}" class="filter-btn {{ $isActive }}">{{ $label }}</a>
                    @endforeach
                </div>
            </div>
        </div>

        @if ($search || $kategori)
        <div class="mb-3 d-flex align-items-center justify-content-between">
            <p class="mb-0 text-muted small">
                Menampilkan <strong>{{ $products->total() }}</strong> produk
                @if ($search) untuk pencarian "<strong>{{ $search }}</strong>" @endif
                @if ($kategori) kategori <strong>{{ ucfirst($kategori) }}</strong> @endif
            </p>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:50px;">
                <i class="fa-solid fa-xmark me-1"></i> Reset
            </a>
        </div>
        @endif

        @if ($products->isEmpty())
        <div class="empty-state">
            <i class="fa-solid fa-magnifying-glass d-block mb-3"></i>
            <h5>Produk Tidak Ditemukan</h5>
            <p>Coba kata kunci atau filter yang berbeda</p>
            <a href="{{ route('products.index') }}" class="btn-primary-custom d-inline-flex mt-2">Reset Pencarian</a>
        </div>
        @else
        <div class="row g-4">
            @foreach ($products as $p)
            <div class="col-lg-4 col-md-6">
                <div class="product-card h-100">
                    <div class="product-img-wrap">
                        <img src="{{ $p->image ? asset('storage/' . $p->image) : 'https://placehold.co/400x300/FFF7ED/F97316?text=' . urlencode(substr($p->name, 0, 15)) }}" alt="{{ $p->name }}" loading="lazy">
                        <span class="product-badge">{{ ucfirst($p->category) }}</span>
                    </div>
                    <div class="product-body">
                        <h5 class="product-name">{{ $p->name }}</h5>
                        <p class="product-desc">{{ $p->description }}</p>
                        <div class="product-meta">
                            <span class="product-price">Rp {{ number_format($p->price, 0, ',', '.') }}</span>
                            <span class="product-weight"><i class="fa-solid fa-weight-scale me-1"></i>{{ $p->berat_gram }}g</span>
                        </div>
                        <div class="product-meta mb-2">
                            @if ($p->stock > 0)
                            <span class="product-stock"><i class="fa-solid fa-circle-check me-1"></i>Stok: {{ $p->stock }}</span>
                            @else
                            <span class="text-danger small fw-600"><i class="fa-solid fa-circle-xmark me-1"></i>Stok Habis</span>
                            @endif
                        </div>
                        <div class="product-actions">
                            <a href="{{ route('products.show', $p->slug) }}" class="btn-detail">
                                <i class="fa-solid fa-eye me-1"></i> Detail
                            </a>
                            @if ($p->stock > 0)
                            <a href="{{ route('orders.create', ['product' => $p->slug]) }}" class="btn-order-sm">
                                <i class="fa-solid fa-cart-plus me-1"></i> Pesan
                            </a>
                            @else
                            <button class="btn-order-sm" disabled style="opacity:.5;cursor:not-allowed;">Habis</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if ($products->hasPages())
        <nav class="mt-5 d-flex justify-content-center">
            <ul class="pagination">
                <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $products->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                </li>
                @for ($i = 1; $i <= $products->lastPage(); $i++)
                <li class="page-item {{ $i == $products->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                </li>
                @endfor
                <li class="page-item {{ ! $products->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $products->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
        <p class="text-center text-muted small mt-2">Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}</p>
        @endif
        @endif
    </div>
</section>

@endsection
