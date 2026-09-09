@extends('layouts.shop')

@section('title', $product->name)

@section('content')

<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Gambar -->
            <div class="col-lg-5">
                <div class="detail-img-wrap">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x460/FFF7ED/F97316?text=' . urlencode($product->name) }}" alt="{{ $product->name }}">
                </div>
                <div class="mt-3 d-flex gap-2 flex-wrap">
                    <span class="badge px-3 py-2" style="background:var(--primary);font-size:.82rem;">
                        <i class="fa-solid fa-tag me-1"></i> {{ ucfirst($product->category) }}
                    </span>
                    @if ($product->stock > 0)
                    <span class="badge px-3 py-2" style="background:#22C55E;font-size:.82rem;">
                        <i class="fa-solid fa-circle-check me-1"></i> Tersedia
                    </span>
                    @else
                    <span class="badge bg-danger px-3 py-2" style="font-size:.82rem;">
                        <i class="fa-solid fa-circle-xmark me-1"></i> Stok Habis
                    </span>
                    @endif
                </div>
            </div>

            <!-- Info -->
            <div class="col-lg-7">
                <h1 style="font-family:'Playfair Display',serif;font-weight:700;font-size:clamp(1.5rem,3vw,2rem);">
                    {{ $product->name }}
                </h1>

                <div class="detail-price mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</div>

                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 rounded-3" style="background:var(--light-bg);">
                            <i class="fa-solid fa-weight-scale text-warning d-block mb-1" style="font-size:1.3rem;"></i>
                            <div class="fw-700" style="font-size:.95rem;">{{ $product->berat_gram }}g</div>
                            <div class="text-muted" style="font-size:.75rem;">Berat</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 rounded-3" style="background:var(--light-bg);">
                            <i class="fa-solid fa-layer-group text-warning d-block mb-1" style="font-size:1.3rem;"></i>
                            <div class="fw-700" style="font-size:.95rem;">{{ $product->stock }}</div>
                            <div class="text-muted" style="font-size:.75rem;">Stok</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 rounded-3" style="background:var(--light-bg);">
                            <i class="fa-solid fa-fire-flame-curved text-warning d-block mb-1" style="font-size:1.3rem;"></i>
                            <div class="fw-700" style="font-size:.95rem;text-transform:capitalize;">{{ $product->category }}</div>
                            <div class="text-muted" style="font-size:.75rem;">Kategori</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 rounded-3" style="background:var(--light-bg);">
                            <i class="fa-solid fa-shield-halved text-warning d-block mb-1" style="font-size:1.3rem;"></i>
                            <div class="fw-700" style="font-size:.95rem;">Higienis</div>
                            <div class="text-muted" style="font-size:.75rem;">Kualitas</div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-700 mb-2"><i class="fa-solid fa-align-left me-2 text-warning"></i>Deskripsi Produk</h6>
                    <p class="text-muted" style="line-height:1.8;">{!! nl2br(e($product->description)) !!}</p>
                </div>

                <hr style="border-color:var(--primary-light);">

                @if ($product->stock > 0)
                <div>
                    <h6 class="fw-700 mb-3"><i class="fa-solid fa-cart-shopping me-2 text-warning"></i>Pesan Produk Ini</h6>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <label class="fw-600 mb-0" style="font-size:.9rem;">Jumlah:</label>
                        <div class="qty-control">
                            <button class="qty-btn" type="button" onclick="changeQty('minus')">−</button>
                            <input type="number" id="jumlah" class="qty-input" value="1" min="1" max="{{ $product->stock }}" oninput="updateTotal()">
                            <input type="hidden" id="harga_satuan" value="{{ $product->price }}">
                            <button class="qty-btn" type="button" onclick="changeQty('plus')">+</button>
                        </div>
                        <span class="text-muted small">Maks. {{ $product->stock }}</span>
                    </div>
                    <div class="mb-4 p-3 rounded-3" style="background:var(--light-bg);display:flex;justify-content:space-between;align-items:center;">
                        <span class="fw-600 text-muted">Total Estimasi:</span>
                        <span class="fw-800 fs-5" style="color:var(--primary);" id="total_display">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('orders.create', ['product' => $product->slug, 'qty' => 1]) }}"
                       id="btn-pesan-link"
                       class="btn-primary-custom d-inline-flex w-100 justify-content-center py-3">
                        <i class="fa-solid fa-cart-plus me-2"></i> Pesan Sekarang
                    </a>
                </div>
                @else
                <div class="alert alert-danger rounded-3">
                    <i class="fa-solid fa-circle-xmark me-2"></i>
                    Maaf, stok produk ini sedang habis. Silakan pilih produk lain.
                </div>
                <a href="{{ route('products.index') }}" class="btn-outline-custom d-inline-flex">
                    <i class="fa-solid fa-arrow-left me-2"></i> Lihat Produk Lain
                </a>
                @endif
            </div>
        </div>

        @if ($related->isNotEmpty())
        <div class="mt-5 pt-4" style="border-top:2px solid var(--primary-light);">
            <h4 class="mb-4 fw-700">
                <i class="fa-solid fa-fire-flame-curved me-2" style="color:var(--primary);"></i>
                Produk Kategori {{ ucfirst($product->category) }} Lainnya
            </h4>
            <div class="row g-4">
                @foreach ($related as $r)
                <div class="col-md-4">
                    <div class="product-card">
                        <div class="product-img-wrap">
                            <img src="{{ $r->image ? asset('storage/' . $r->image) : 'https://placehold.co/400x300/FFF7ED/F97316?text=' . urlencode(substr($r->name, 0, 15)) }}" alt="{{ $r->name }}" loading="lazy">
                            <span class="product-badge">{{ ucfirst($r->category) }}</span>
                        </div>
                        <div class="product-body">
                            <h5 class="product-name">{{ $r->name }}</h5>
                            <div class="product-meta">
                                <span class="product-price">Rp {{ number_format($r->price, 0, ',', '.') }}</span>
                                <span class="product-weight">{{ $r->berat_gram }}g</span>
                            </div>
                            <div class="product-actions">
                                <a href="{{ route('products.show', $r->slug) }}" class="btn-detail">Detail</a>
                                <a href="{{ route('orders.create', ['product' => $r->slug]) }}" class="btn-order-sm">Pesan</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
document.getElementById('jumlah')?.addEventListener('input', function() {
    const qty = this.value || 1;
    const link = document.getElementById('btn-pesan-link');
    if (link) link.href = "{{ route('orders.create', ['product' => $product->slug]) }}" + '&qty=' + qty;
    updateTotal();
});
</script>
@endpush
