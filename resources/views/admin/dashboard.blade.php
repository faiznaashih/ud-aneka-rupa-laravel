@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="border-left-color:#F97316;">
            <div class="stat-card-icon" style="background:#FFF7ED;color:#F97316;"><i class="fa-solid fa-boxes-stacked"></i></div>
            <div class="stat-num">{{ $totalProdukAktif }}</div>
            <div class="stat-label">Produk Aktif</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="border-left-color:#3B82F6;">
            <div class="stat-card-icon" style="background:#EFF6FF;color:#3B82F6;"><i class="fa-solid fa-receipt"></i></div>
            <div class="stat-num">{{ $totalPesanan }}</div>
            <div class="stat-label">Total Pesanan</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="border-left-color:#FBBF24;">
            <div class="stat-card-icon" style="background:#FFFBEB;color:#D97706;"><i class="fa-solid fa-bell"></i></div>
            <div class="stat-num">{{ $pesananBaru }}</div>
            <div class="stat-label">Pesanan Menunggu</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="border-left-color:#22C55E;">
            <div class="stat-card-icon" style="background:#F0FDF4;color:#22C55E;"><i class="fa-solid fa-money-bill-wave"></i></div>
            <div class="stat-num" style="font-size:1.3rem;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pendapatan</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="page-card">
            <div class="page-card-header">
                <h6 class="page-card-title"><i class="fa-solid fa-receipt me-2" style="color:var(--primary);"></i>Pesanan Terbaru</h6>
                <a href="{{ route('admin.orders.index') }}" class="btn-orange" style="border-radius:50px;padding:6px 16px;font-size:.8rem;">
                    Lihat Semua
                </a>
            </div>
            @if ($pesananTerbaru->isEmpty())
            <p class="text-muted text-center py-4">Belum ada pesanan</p>
            @else
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Pelanggan</th>
                            <th>Produk</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pesananTerbaru as $p)
                        <tr>
                            <td><code class="text-warning" style="font-size:.78rem;">{{ $p->order_code }}</code></td>
                            <td>
                                <div class="fw-600">{{ $p->customer->name ?? '-' }}</div>
                                <div class="text-muted small">{{ $p->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="text-muted small">{{ $p->items->first()->product_name ?? '-' }}</td>
                            <td class="fw-700" style="color:var(--primary);">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                            <td>
                                @php($b = \App\Support\StatusBadge::map($p->status))
                                <span class="badge badge-status bg-{{ $b['bg'] }}">{{ $b['text'] }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <div class="page-card mb-4">
            <div class="page-card-header">
                <h6 class="page-card-title"><i class="fa-solid fa-triangle-exclamation me-2" style="color:#FBBF24;"></i>Stok Rendah</h6>
            </div>
            @if ($stokRendah->isEmpty())
            <p class="text-muted small text-center py-2">Semua stok aman ✓</p>
            @else
            @foreach ($stokRendah as $p)
            <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom:1px solid #F5F5F4;">
                <div>
                    <div class="fw-600" style="font-size:.85rem;">{{ $p->name }}</div>
                    <div class="text-muted" style="font-size:.75rem;">{{ ucfirst($p->category) }}</div>
                </div>
                <span class="badge {{ $p->stock <= 5 ? 'bg-danger' : 'bg-warning text-dark' }} rounded-pill px-3 py-2">
                    {{ $p->stock }} tersisa
                </span>
            </div>
            @endforeach
            @endif
        </div>

        <div class="page-card">
            <h6 class="page-card-title mb-3"><i class="fa-solid fa-bolt me-2" style="color:var(--primary);"></i>Aksi Cepat</h6>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.products.create') }}" class="btn-orange" style="text-align:center;padding:10px;border-radius:10px;display:block;">
                    <i class="fa-solid fa-plus me-2"></i> Tambah Produk Baru
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-outline-warning fw-600" style="border-radius:10px;font-size:.85rem;">
                    <i class="fa-solid fa-bell me-2"></i> Pesanan Menunggu ({{ $pesananBaru }})
                </a>
                <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-secondary fw-600" style="border-radius:10px;font-size:.85rem;">
                    <i class="fa-solid fa-globe me-2"></i> Preview Website
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
