@extends('layouts.shop')

@section('title', 'Produk Kami')

@section('content')

<!-- Hero Banner -->
<section class="produk-hero">
    <div class="produk-hero-overlay"></div>
    <img src="{{ asset('assets/images/hero-kerupuk.png') }}"
         onerror="this.style.display='none'"
         alt="" class="produk-hero-bg">
    <div class="container position-relative">
        <span class="produk-hero-tag"><i class="fa-solid fa-leaf me-1"></i> Ragam Rasa, Kualitas Terbaik</span>
        <h1 class="produk-hero-title">Produk Kerupuk Kami</h1>
        <p class="produk-hero-desc">
            Nikmati berbagai pilihan kerupuk dengan cita rasa autentik, dibuat dari bahan pilihan dan proses tradisional.
        </p>
        <div class="produk-hero-badges">
            <span><i class="fa-solid fa-seedling"></i> 100% Bahan Pilihan</span>
            <span><i class="fa-solid fa-fire-flame-curved"></i> Rasa Gurih & Lezat</span>
            <span><i class="fa-solid fa-shield-halved"></i> Produksi Terjamin</span>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">

        <!-- Search -->
        <form method="GET" action="{{ route('products.index') }}" class="mb-4">
            @if ($kategori)<input type="hidden" name="kategori" value="{{ $kategori }}">@endif
            @if ($sort)<input type="hidden" name="sort" value="{{ $sort }}">@endif
            <div class="search-wrap mx-auto" style="max-width:480px;">
                <input type="text" name="search" class="form-control" placeholder="Cari produk kerupuk..." value="{{ $search }}">
                <button type="submit" class="search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </form>

        <!-- Filter + Sort Bar -->
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div class="d-flex gap-2 flex-wrap">
                @php
                    $kategoriList = ['semua' => 'Semua', 'original' => 'Kerupuk Original', 'gurih' => 'Kerupuk Gurih', 'pedas' => 'Kerupuk Pedas', 'manis' => 'Kerupuk Manis'];
                @endphp
                @foreach ($kategoriList as $k => $label)
                    @php
                        $isActive = ($k === $kategori || ($k === 'semua' && !$kategori)) ? 'active' : '';
                        $url = route('products.index', array_filter(['search' => $search ?: null, 'kategori' => $k === 'semua' ? null : $k, 'sort' => $sort !== 'terbaru' ? $sort : null]));
                    @endphp
                    <a href="{{ $url }}" class="pill-filter {{ $isActive }}">{{ $label }}</a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('products.index') }}" class="d-flex align-items-center gap-2">
                @if ($search)<input type="hidden" name="search" value="{{ $search }}">@endif
                @if ($kategori)<input type="hidden" name="kategori" value="{{ $kategori }}">@endif
                <label class="text-muted small mb-0 text-nowrap"><i class="fa-solid fa-arrow-down-wide-short me-1"></i>Urutkan</label>
                <select name="sort" class="form-select form-select-sm sort-select" onchange="this.form.submit()">
                    <option value="terpopuler" {{ $sort === 'terpopuler' ? 'selected' : '' }}>Terpopuler</option>
                    <option value="terbaru" {{ $sort === 'terbaru' || !$sort ? 'selected' : '' }}>Terbaru</option>
                    <option value="harga-asc" {{ $sort === 'harga-asc' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="harga-desc" {{ $sort === 'harga-desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                </select>
            </form>
        </div>

        @if ($search || $kategori)
        <div class="mb-3 d-flex align-items-center justify-content-between">
            <p class="mb-0 text-muted small">
                Menampilkan <strong>{{ $products->total() }}</strong> produk
                @if ($search) untuk "<strong>{{ $search }}</strong>" @endif
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
            @php
                $categoryBadge = [
                    'original' => ['bg' => '#F97316', 'label' => 'Original'],
                    'pedas'    => ['bg' => '#DC2626', 'label' => 'Pedas'],
                    'gurih'    => ['bg' => '#059669', 'label' => 'Gurih'],
                    'manis'    => ['bg' => '#DB2777', 'label' => 'Manis'],
                ];
            @endphp
            @foreach ($products as $p)
            <div class="col-lg-4 col-md-6">
                <div class="product-card-v2 h-100">
                    <div class="product-card-v2-img">
                        <img src="{{ $p->image ? asset('storage/' . $p->image) : 'https://placehold.co/400x300/FFF7ED/F97316?text=' . urlencode(substr($p->name, 0, 15)) }}" alt="{{ $p->name }}" loading="lazy">

                        @if ($p->id === $bestSellerId)
                        <span class="badge-v2" style="background:#1C1917;">
                            <i class="fa-solid fa-crown me-1"></i>Best Seller
                        </span>
                        @else
                        <span class="badge-v2" style="background:{{ $categoryBadge[$p->category]['bg'] ?? '#78716C' }};">
                            {{ $categoryBadge[$p->category]['label'] ?? ucfirst($p->category) }}
                        </span>
                        @endif

                        <button type="button" class="wishlist-btn" onclick="this.classList.toggle('active')" title="Simpan ke favorit">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                    </div>
                    <div class="product-card-v2-body">
                        <h5 class="product-card-v2-name">{{ $p->name }}</h5>
                        <p class="product-card-v2-desc">{{ \Illuminate\Support\Str::limit($p->description, 60) }}</p>

                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="weight-chip"><i class="fa-solid fa-weight-scale me-1"></i>{{ $p->berat_gram }}g</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="product-card-v2-price">Rp {{ number_format($p->price, 0, ',', '.') }}</span>
                            @if ($p->stock > 0)
                            <span class="text-success small fw-600"><i class="fa-solid fa-circle-check me-1"></i>Stok: {{ $p->stock }}</span>
                            @else
                            <span class="text-danger small fw-600">Stok Habis</span>
                            @endif
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('products.show', $p->slug) }}" class="btn-detail-v2">
                                <i class="fa-solid fa-eye me-1"></i> Detail
                            </a>
                            @if ($p->stock > 0)
                            <form method="POST" action="{{ route('cart.add', $p) }}" class="flex-fill">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn-pesan-v2 w-100">
                                    <i class="fa-solid fa-cart-plus me-1"></i> + Keranjang
                                </button>
                            </form>
                            @else
                            <button class="btn-pesan-v2" disabled style="opacity:.5;">Habis</button>
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
        @endif
        @endif
    </div>
</section>

<style>
.produk-hero {
    position: relative;
    padding: 4rem 0 3.5rem;
    overflow: hidden;
    background: #1C1917;
    min-height: 280px;
    display: flex;
    align-items: center;
}
.produk-hero-bg {
    position: absolute; inset: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    opacity: .55;
}
.produk-hero-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(90deg, rgba(28,25,23,.92) 20%, rgba(28,25,23,.55) 100%);
}
.produk-hero-tag {
    position: relative;
    display: inline-block;
    color: #FDBA74;
    font-weight: 600;
    font-size: .9rem;
    margin-bottom: .75rem;
}
.produk-hero-title {
    position: relative;
    color: white;
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    font-size: clamp(1.8rem, 4vw, 2.6rem);
    margin-bottom: .75rem;
}
.produk-hero-desc {
    position: relative;
    color: #E7E5E4;
    max-width: 520px;
    margin-bottom: 1.25rem;
}
.produk-hero-badges {
    position: relative;
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}
.produk-hero-badges span {
    color: white;
    font-size: .82rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: .4rem;
}
.produk-hero-badges i { color: #FBBF24; }

.pill-filter {
    display: inline-block;
    padding: .5rem 1.1rem;
    border-radius: 50px;
    background: white;
    border: 1px solid #E7E5E4;
    color: #57534E;
    font-size: .85rem;
    font-weight: 600;
    text-decoration: none;
    transition: all .2s;
}
.pill-filter:hover { border-color: #F97316; color: #F97316; }
.pill-filter.active { background: #F97316; border-color: #F97316; color: white; }

.sort-select {
    width: auto;
    border-radius: 8px;
    border: 1px solid #E7E5E4;
    font-size: .85rem;
}

.product-card-v2 {
    background: white;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,.06);
    transition: all .25s;
}
.product-card-v2:hover { transform: translateY(-4px); box-shadow: 0 14px 30px rgba(0,0,0,.1); }
.product-card-v2-img { position: relative; aspect-ratio: 4/3; overflow: hidden; background: #FFF7ED; }
.product-card-v2-img img { width: 100%; height: 100%; object-fit: cover; }
.badge-v2 {
    position: absolute; top: .75rem; left: .75rem;
    color: white; font-size: .7rem; font-weight: 700;
    padding: .3rem .7rem; border-radius: 50px;
}
.wishlist-btn {
    position: absolute; top: .6rem; right: .6rem;
    width: 34px; height: 34px;
    border-radius: 50%;
    background: white;
    border: none;
    display: flex; align-items: center; justify-content: center;
    color: #A8A29E;
    box-shadow: 0 2px 8px rgba(0,0,0,.12);
    transition: all .2s;
}
.wishlist-btn:hover { color: #F97316; }
.wishlist-btn.active { color: #EF4444; }

.product-card-v2-body { padding: 1.1rem 1.2rem; }
.product-card-v2-name { font-weight: 700; font-size: 1.02rem; margin-bottom: .3rem; }
.product-card-v2-desc { color: #78716C; font-size: .82rem; margin-bottom: .6rem; min-height: 2.2em; }
.weight-chip {
    background: #FFF7ED; color: #EA580C;
    font-size: .72rem; font-weight: 600;
    padding: .2rem .6rem; border-radius: 50px;
}
.product-card-v2-price { color: #EA580C; font-weight: 800; font-size: 1.1rem; }

.btn-detail-v2, .btn-pesan-v2 {
    flex: 1;
    text-align: center;
    padding: .5rem;
    border-radius: 8px;
    font-size: .82rem;
    font-weight: 600;
    text-decoration: none;
    border: none;
    transition: all .2s;
}
.btn-detail-v2 { background: #FFF7ED; color: #EA580C; }
.btn-detail-v2:hover { background: #FED7AA; color: #C2410C; }
.btn-pesan-v2 { background: #F97316; color: white; }
.btn-pesan-v2:hover { background: #EA580C; color: white; }
</style>

@endsection
