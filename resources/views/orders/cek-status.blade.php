@extends('layouts.shop')

@section('title', 'Cek Status Pesanan')

@section('content')

<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Cek Status Pesanan</li>
            </ol>
        </nav>
        <h1 class="mb-1"><i class="fa-solid fa-magnifying-glass me-2" style="color:var(--primary)!important;"></i> Cek Status Pesanan</h1>
        <p class="text-muted mb-0">Masukkan kode pesanan untuk melihat status pesanan Anda</p>
    </div>
</section>

<section class="py-5" style="background:var(--light-bg); min-height: 60vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="form-card mb-4" id="lacak-box">
                    <h5 class="fw-700 mb-4"><i class="fa-solid fa-search me-2" style="color:var(--primary);"></i>Lacak Pesanan</h5>
                    <form method="POST" action="{{ route('orders.cek-status') }}" id="formCek" onsubmit="return validateForm('formCek')">
                        @csrf
                        <div class="d-flex gap-2">
                            <input type="text" name="kode_pesanan" class="form-control form-control-lg"
                                   placeholder="Contoh: ORD-8F3K2Q1A" value="{{ $kodeInput }}"
                                   style="border-radius:50px;border:2px solid var(--primary-light);" required>
                            <button type="submit" class="btn-primary-custom px-4" style="white-space:nowrap;border-radius:50px;">
                                <i class="fa-solid fa-search me-1"></i> Lacak
                            </button>
                        </div>
                        <div class="mt-2 small text-muted">
                            <i class="fa-solid fa-circle-info me-1 text-warning"></i>
                            Kode pesanan diberikan setelah Anda melakukan pemesanan
                        </div>
                    </form>
                </div>

                @if ($notFound)
                <div class="text-center py-5">
                    <i class="fa-solid fa-box-open d-block mb-3" style="font-size:3rem;color:var(--primary-light);"></i>
                    <h5 class="fw-700">Pesanan Tidak Ditemukan</h5>
                    <p class="text-muted">Kode pesanan "<strong>{{ $kodeInput }}</strong>" tidak ditemukan di sistem kami.</p>
                    <p class="text-muted small">Pastikan kode pesanan yang Anda masukkan sudah benar.</p>
                </div>
                @endif

                @if ($order)
                @php
                    $item = $order->items->first();
                    $statusSteps = ['pending', 'diproses', 'dikirim', 'selesai'];
                    $statusLabels = ['Menunggu', 'Diproses', 'Dikirim', 'Selesai'];
                    $statusIcons = ['fa-clock', 'fa-gear', 'fa-truck', 'fa-circle-check'];
                    $currentStep = $order->status !== 'dibatalkan' ? array_search($order->status, $statusSteps) : 0;
                    $currentStep = $currentStep === false ? 0 : $currentStep;
                    $badge = \App\Support\StatusBadge::map($order->status);
                @endphp
                <div class="order-card">
                    <div class="order-header">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                            <div>
                                <div class="small text-muted mb-1">Kode Pesanan</div>
                                <div class="fw-800 fs-5" style="color:var(--primary);letter-spacing:.5px;">{{ $order->order_code }}</div>
                            </div>
                            <div class="text-md-end">
                                <span class="badge badge-status bg-{{ $badge['bg'] }}">{{ $badge['text'] }}</span>
                                <div class="small text-muted mt-1">
                                    <i class="fa-regular fa-clock me-1"></i>
                                    {{ $order->created_at->format('d M Y, H:i') }} WIB
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="order-body">
                        @if ($order->status !== 'dibatalkan')
                        <div class="mb-4">
                            <h6 class="fw-700 mb-3 text-muted small text-uppercase">Progress Pesanan</h6>
                            <div class="progress-steps">
                                @foreach ($statusSteps as $i => $step)
                                <div class="step-item {{ $i <= $currentStep ? 'active' : '' }}">
                                    <div class="step-circle {{ $i < $currentStep ? 'done' : ($i === $currentStep ? 'active' : '') }}">
                                        <i class="fa-solid {{ $statusIcons[$i] }}"></i>
                                    </div>
                                    <span class="step-label">{{ $statusLabels[$i] }}</span>
                                </div>
                                @if ($i < count($statusSteps) - 1)
                                <div class="step-line {{ $i < $currentStep ? 'done' : '' }}"></div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="alert alert-danger rounded-3 mb-4">
                            <i class="fa-solid fa-circle-xmark me-2"></i>
                            Pesanan ini telah <strong>dibatalkan</strong>.
                        </div>
                        @endif

                        <div class="order-meta-row mb-4">
                            <div class="order-meta-item">
                                <label><i class="fa-solid fa-user me-1"></i> Nama Pelanggan</label>
                                <span>{{ $order->customer->name }}</span>
                            </div>
                            <div class="order-meta-item">
                                <label><i class="fa-solid fa-phone me-1"></i> Nomor HP</label>
                                <span>{{ $order->customer->phone }}</span>
                            </div>
                            <div class="order-meta-item">
                                <label><i class="fa-solid fa-location-dot me-1"></i> Alamat</label>
                                <span>{{ $order->customer->address }}</span>
                            </div>
                        </div>

                        <h6 class="fw-700 mb-3 text-muted small text-uppercase">Detail Produk</h6>
                        <div class="d-flex gap-3 p-3 rounded-3 mb-4" style="background:var(--light-bg);border:1px solid var(--primary-light);">
                            <div style="width:72px;height:72px;border-radius:10px;overflow:hidden;flex-shrink:0;">
                                <img src="{{ $item?->product?->image ? asset('storage/' . $item->product->image) : 'https://placehold.co/120x120/FFF7ED/F97316?text=IMG' }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-700">{{ $item->product_name ?? '-' }}</div>
                                <div class="text-muted small">{{ $item?->product?->berat_gram }}g per kemasan</div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div class="text-muted small">
                                        Rp {{ number_format($item->price ?? 0, 0, ',', '.') }} &times; {{ $item->quantity ?? 0 }}
                                    </div>
                                    <div class="fw-800" style="color:var(--primary);">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($order->notes)
                        <div class="mb-3 p-3 rounded-3" style="background:#FFFBF5;border:1px solid var(--primary-light);">
                            <div class="small text-muted mb-1"><i class="fa-solid fa-note-sticky me-1"></i> Catatan</div>
                            <div class="fw-600 small">{{ $order->notes }}</div>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <span class="fw-700">Total Pembayaran</span>
                            <span class="fw-800 fs-4" style="color:var(--primary);">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>

                        <div class="mt-4 d-flex gap-2 flex-wrap">
                            <a href="{{ route('products.index') }}" class="btn-primary-custom">
                                <i class="fa-solid fa-cart-shopping me-1"></i> Pesan Lagi
                            </a>
                            <button onclick="window.print()" class="btn-outline-custom">
                                <i class="fa-solid fa-print me-1"></i> Cetak
                            </button>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</section>

@endsection

<style>
@media print {
    nav, footer, .breadcrumb, #lacak-box, .btn-primary-custom, .btn-outline-custom, button {
        display: none !important;
    }
    .page-hero { display: none !important; }
    body { background: white !important; }
    .order-card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
    section { padding: 0 !important; background: white !important; }
}
</style>