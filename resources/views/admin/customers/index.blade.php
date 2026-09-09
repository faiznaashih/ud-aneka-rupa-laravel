@extends('layouts.admin')

@section('title', 'Data Pelanggan')

@section('content')
<div class="page-card">
    <div class="page-card-header">
        <h6 class="page-card-title"><i class="fa-solid fa-users me-2" style="color:var(--primary);"></i>Data Pelanggan ({{ $customers->total() }})</h6>
    </div>

    <form method="GET" class="mb-3">
        <div class="d-flex gap-2" style="max-width:380px;">
            <input type="text" name="search" class="form-control" placeholder="Cari nama / nomor HP..." value="{{ $search }}">
            <button type="submit" class="btn-orange px-3" style="border-radius:10px;"><i class="fa-solid fa-search"></i></button>
            @if ($search)
            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Pelanggan</th>
                    <th>Nomor HP</th>
                    <th>Alamat</th>
                    <th>Total Pesanan</th>
                    <th>Total Belanja</th>
                    <th>Pesanan Terakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $i => $c)
                <tr>
                    <td class="text-muted small">{{ $customers->firstItem() + $i }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#FBBF24);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:.85rem;flex-shrink:0;">
                                {{ strtoupper(substr($c->name, 0, 1)) }}
                            </div>
                            <span class="fw-600 small">{{ $c->name }}</span>
                        </div>
                    </td>
                    <td class="small">
                        <a href="https://wa.me/62{{ ltrim($c->phone, '0') }}" target="_blank" class="text-success fw-600" style="text-decoration:none;">
                            <i class="fab fa-whatsapp me-1"></i>{{ $c->phone }}
                        </a>
                    </td>
                    <td class="small text-muted" style="max-width:180px;">
                        <span style="overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $c->address }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-primary rounded-pill">{{ $c->orders_count }} pesanan</span>
                    </td>
                    <td class="fw-700" style="color:var(--primary);font-size:.85rem;">Rp {{ number_format($c->orders_sum_total ?? 0, 0, ',', '.') }}</td>
                    <td class="small text-muted">{{ $c->orders_max_created_at ? \Illuminate\Support\Carbon::parse($c->orders_max_created_at)->format('d M Y') : '-' }}</td>
                    <td>
                        <a href="{{ route('admin.orders.index', ['search' => $c->phone]) }}" class="btn btn-sm btn-outline-primary fw-600" style="border-radius:6px;font-size:.75rem;">
                            <i class="fa-solid fa-eye me-1"></i> Riwayat
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">Data pelanggan tidak ditemukan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($customers->hasPages())
    <nav class="mt-3">
        <ul class="pagination pagination-sm justify-content-end mb-0">
            @for ($i = 1; $i <= $customers->lastPage(); $i++)
            <li class="page-item {{ $i === $customers->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="{{ $customers->url($i) }}">{{ $i }}</a>
            </li>
            @endfor
        </ul>
    </nav>
    @endif
</div>
@endsection
