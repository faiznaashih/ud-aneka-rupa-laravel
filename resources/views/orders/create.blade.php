@extends('layouts.shop')

@section('title', 'Form Pemesanan')

@section('content')

<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
                <li class="breadcrumb-item active">Form Pemesanan</li>
            </ol>
        </nav>
        <h1 class="mb-1"><i class="fa-solid fa-cart-shopping me-2" style="color:var(--primary)!important;"></i> Form Pemesanan</h1>
        <p class="text-muted mb-0">Isi data Anda untuk melakukan pemesanan</p>
    </div>
</section>

<section class="py-5" style="background:var(--light-bg);">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <!-- Form -->
            <div class="col-lg-7">
                <div class="form-card">
                    <h5 class="fw-700 mb-4">
                        <i class="fa-solid fa-user-pen me-2" style="color:var(--primary);"></i>
                        Data Pemesanan
                    </h5>

                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                        <strong>Terdapat kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('orders.store') }}" id="formPesan" onsubmit="return validateForm('formPesan')">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap Anda" value="{{ old('name') }}" required>
                            <div class="invalid-feedback">Nama wajib diisi.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor HP / WhatsApp <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="border:2px solid #E7E5E4;border-right:0;background:var(--light-bg);">
                                    <i class="fa-solid fa-phone"></i>
                                </span>
                                <input type="tel" name="phone" class="form-control" style="border-left:0;" placeholder="Contoh: 081234567890" value="{{ old('phone') }}" required>
                            </div>
                            <div class="invalid-feedback">Nomor HP wajib diisi.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Pengiriman <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Masukkan alamat lengkap termasuk kecamatan dan kota..." required>{{ old('address') }}</textarea>
                            <div class="invalid-feedback">Alamat wajib diisi.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah Pesanan <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="qty-control">
                                    <button class="qty-btn" type="button" onclick="changeQty('minus')">−</button>
                                    <input type="number" name="quantity" id="jumlah" class="qty-input" value="{{ old('quantity', $qty) }}" min="1" max="{{ $product->stock }}" oninput="updateTotal()" required>
                                    <input type="hidden" id="harga_satuan" value="{{ $product->price }}">
                                    <button class="qty-btn" type="button" onclick="changeQty('plus')">+</button>
                                </div>
                                <small class="text-muted">Stok tersedia: <strong>{{ $product->stock }}</strong></small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Catatan (Opsional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Catatan tambahan untuk pesanan (misal: kemasan khusus, permintaan lain)">{{ old('notes') }}</textarea>
                        </div>

                        <button type="submit" class="btn-primary-custom w-100 justify-content-center py-3" style="display:flex;">
                            <i class="fa-solid fa-paper-plane me-2"></i> Kirim Pesanan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Ringkasan Pesanan -->
            <div class="col-lg-5">
                <div class="form-card">
                    <h5 class="fw-700 mb-4">
                        <i class="fa-solid fa-receipt me-2" style="color:var(--primary);"></i>
                        Ringkasan Pesanan
                    </h5>
                    <div class="d-flex gap-3 mb-4 p-3 rounded-3" style="background:var(--light-bg);">
                        <div style="width:80px;height:80px;border-radius:10px;overflow:hidden;flex-shrink:0;">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/120x120/FFF7ED/F97316?text=IMG' }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                        <div>
                            <div class="fw-700" style="font-size:.9rem;">{{ $product->name }}</div>
                            <div class="text-muted small mb-1">{{ $product->berat_gram }}g · {{ ucfirst($product->category) }}</div>
                            <div class="fw-800" style="color:var(--primary);">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2 text-muted small">
                            <span>Harga satuan</span>
                            <span class="fw-600 text-dark">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-muted small">
                            <span>Jumlah</span>
                            <span class="fw-600 text-dark" id="qty-display">&times; {{ $qty }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2 mt-2">
                            <span class="fw-700">Total</span>
                            <span class="fw-800 fs-5" style="color:var(--primary);" id="total_display">Rp {{ number_format($product->price * $qty, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-4 p-3 rounded-3" style="background:linear-gradient(135deg,var(--light-bg),#FEF3C7);border:1px solid var(--primary-light);">
                        <p class="mb-2 small fw-600"><i class="fa-solid fa-info-circle me-1" style="color:var(--primary);"></i> Informasi Pemesanan:</p>
                        <ul class="mb-0 small text-muted" style="padding-left:1.2rem;">
                            <li>Pesanan akan dikonfirmasi via WhatsApp</li>
                            <li>Pembayaran dilakukan saat barang diterima (COD)</li>
                            <li>Simpan kode pesanan untuk melacak status</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
const jumlahInput = document.getElementById('jumlah');
if (jumlahInput) {
    function updateQtyDisplay() {
        const el = document.getElementById('qty-display');
        if (el) el.textContent = '\u00d7 ' + (jumlahInput.value || 1);
        updateTotal();
    }
    jumlahInput.addEventListener('input', updateQtyDisplay);
}

@if (session('order_success'))
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'success',
        title: '🎉 Pesanan Berhasil!',
        html: `
            <p>Pesanan Anda telah berhasil dikirim.</p>
            <div style="background:#FFF7ED;border:2px solid #FED7AA;border-radius:12px;padding:12px 20px;margin:12px 0;">
                <div style="color:#78716C;font-size:.85rem;margin-bottom:4px;">Kode Pesanan Anda:</div>
                <div style="font-size:1.3rem;font-weight:800;color:#F97316;letter-spacing:1px;">{{ session('order_success') }}</div>
            </div>
            <p style="color:#78716C;font-size:.87rem;">Simpan kode ini untuk melacak status pesanan Anda</p>
        `,
        confirmButtonColor: '#F97316',
        confirmButtonText: 'Lihat Status Pesanan',
        showCancelButton: true,
        cancelButtonText: 'Pesan Lagi',
        cancelButtonColor: '#78716C'
    }).then(result => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('orders.cek-status', ['kode' => session('order_success')]) }}";
        } else {
            window.location.href = "{{ route('products.index') }}";
        }
    });
});
@endif
</script>
@endpush
