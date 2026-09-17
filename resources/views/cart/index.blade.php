@extends('layouts.shop')

@section('title', 'Keranjang Belanja')

@section('content')

<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Keranjang</li>
            </ol>
        </nav>
        <h1 class="mb-1"><i class="fa-solid fa-cart-shopping me-2" style="color:var(--primary)!important;"></i> Keranjang Belanja</h1>
        <p class="text-muted mb-0">Periksa kembali pesanan Anda sebelum checkout</p>
    </div>
</section>

<section class="py-5" style="background:var(--light-bg); min-height: 50vh;">
    <div class="container">

        @if (empty($items))
        <div class="empty-state">
            <i class="fa-solid fa-cart-shopping d-block mb-3"></i>
            <h5>Keranjang Anda Kosong</h5>
            <p>Yuk pilih produk kerupuk favorit Anda dulu</p>
            <a href="{{ route('products.index') }}" class="btn-primary-custom d-inline-flex mt-2">
                <i class="fa-solid fa-boxes-stacked"></i> Lihat Produk
            </a>
        </div>
        @else
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="form-card">
                    @foreach ($items as $item)
                    @php($p = $item['product'])
                    <div class="d-flex gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div style="width:80px;height:80px;border-radius:10px;overflow:hidden;flex-shrink:0;">
                            <img src="{{ $p->image ? asset('storage/' . $p->image) : 'https://placehold.co/120x120/FFF7ED/F97316?text=IMG' }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <h6 class="fw-700 mb-1">{{ $p->name }}</h6>
                                <form method="POST" action="{{ route('cart.remove', $p) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm text-danger border-0" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="text-muted small mb-2">{{ $p->berat_gram }}g &middot; Rp {{ number_format($p->price, 0, ',', '.') }}</div>

                            <div class="d-flex justify-content-between align-items-center">
                                <form method="POST" action="{{ route('cart.update', $p) }}" class="d-flex align-items-center gap-2">
                                    @csrf @method('PATCH')
                                    <div class="qty-control" style="transform:scale(.85);transform-origin:left;">
                                        <button type="button" class="qty-btn" onclick="stepQty(this, -1)">−</button>
                                        <input type="number" name="quantity" class="qty-input" value="{{ $item['qty'] }}" min="1" max="{{ $p->stock }}">
                                        <button type="button" class="qty-btn" onclick="stepQty(this, 1)">+</button>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;font-size:.75rem;">Update</button>
                                </form>
                                <span class="fw-800" style="color:var(--primary);">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-card">
                    <h5 class="fw-700 mb-4"><i class="fa-solid fa-receipt me-2" style="color:var(--primary);"></i>Ringkasan Belanja</h5>
                    <div class="d-flex justify-content-between mb-2 text-muted small">
                        <span>Total Item</span>
                        <span class="fw-600 text-dark">{{ count($items) }} produk</span>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-3 mt-2">
                        <span class="fw-700">Total Belanja</span>
                        <span class="fw-800 fs-5" style="color:var(--primary);">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <a href="{{ route('orders.create') }}" class="btn-primary-custom w-100 justify-content-center py-3 mt-4">
                        <i class="fa-solid fa-lock me-2"></i> Lanjut ke Pembayaran
                    </a>
                    <a href="{{ route('products.index') }}" class="btn-outline-custom w-100 justify-content-center py-2 mt-2">
                        Tambah Produk Lain
                    </a>
                </div>
            </div>
        </div>
        @endif

    </div>
</section>

@endsection

@push('scripts')
<script>
function stepQty(btn, delta) {
    const input = btn.parentElement.querySelector('.qty-input');
    let val = parseInt(input.value || 1) + delta;
    const max = parseInt(input.max || 999);
    if (val < 1) val = 1;
    if (val > max) val = max;
    input.value = val;
}
</script>
@endpush
