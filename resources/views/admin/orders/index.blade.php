@extends('layouts.admin')

@section('title', 'Kelola Pesanan')

@section('content')
@php
    $tabList = ['semua' => 'Semua', 'pending' => 'Menunggu', 'diproses' => 'Diproses', 'dikirim' => 'Dikirim', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'];
    $tabColors = ['semua' => 'secondary', 'pending' => 'warning', 'diproses' => 'info', 'dikirim' => 'primary', 'selesai' => 'success', 'dibatalkan' => 'danger'];
@endphp

<div class="page-card mb-3 py-2">
    <div class="d-flex flex-wrap gap-2">
        @foreach ($tabList as $k => $label)
            @php
                $active = ($filter === $k || (!$filter && $k === 'semua')) ? 'active' : '';
                $url = route('admin.orders.index', array_filter(['status' => $k === 'semua' ? null : $k, 'search' => $search ?: null]));
            @endphp
            <a href="{{ $url }}" class="btn btn-sm {{ $active ? 'btn-'.$tabColors[$k] : 'btn-outline-'.$tabColors[$k] }} fw-600" style="border-radius:50px;font-size:.78rem;">
                {{ $label }} <span class="badge bg-white text-dark ms-1">{{ $counts[$k] ?? 0 }}</span>
            </a>
        @endforeach
    </div>
</div>

<div class="page-card">
    <div class="page-card-header">
        <h6 class="page-card-title">
            <i class="fa-solid fa-receipt me-2" style="color:var(--primary);"></i>
            Daftar Pesanan {{ $filter ? '— '.($tabList[$filter] ?? $filter) : '' }} ({{ $orders->total() }})
        </h6>
    </div>

    <form method="GET" class="mb-3">
        @if ($filter)<input type="hidden" name="status" value="{{ $filter }}">@endif
        <div class="d-flex gap-2" style="max-width:400px;">
            <input type="text" name="search" class="form-control" placeholder="Cari kode/nama/HP..." value="{{ $search }}">
            <button type="submit" class="btn-orange px-3" style="border-radius:10px;"><i class="fa-solid fa-search"></i></button>
            @if ($search)
            <a href="{{ route('admin.orders.index', array_filter(['status' => $filter ?: null])) }}" class="btn btn-outline-secondary" style="border-radius:10px;">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Produk</th>
                    <th>Jml</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $i => $p)
                @php($item = $p->items->first())
                <tr>
                    <td class="text-muted small">{{ $orders->firstItem() + $i }}</td>
                    <td><code style="color:var(--primary);font-size:.78rem;">{{ $p->order_code }}</code></td>
                    <td>
                        <div class="fw-600 small">{{ $p->customer->name ?? '-' }}</div>
                        <div class="text-muted" style="font-size:.72rem;"><i class="fa-solid fa-phone me-1"></i>{{ $p->customer->phone ?? '-' }}</div>
                    </td>
                    <td class="small text-muted">{{ $item->product_name ?? '-' }}</td>
                    <td class="text-center fw-600">{{ $item->quantity ?? 0 }}</td>
                    <td class="fw-700" style="color:var(--primary);font-size:.85rem;">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                    <td class="small text-muted">{{ $p->created_at->format('d/m/Y') }}</td>
                    <td>
                        @php($b = \App\Support\StatusBadge::map($p->status))
                        <span class="badge badge-status bg-{{ $b['bg'] }}">{{ $b['text'] }}</span>
                    </td>
                    <td>
                        <form id="form-status-{{ $p->id }}" method="POST" action="{{ route('admin.orders.update-status', $p) }}" class="d-none">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" id="status-input-{{ $p->id }}">
                        </form>
                        <button class="btn btn-sm btn-warning" style="border-radius:6px;" onclick="updateStatus({{ $p->id }}, '{{ $p->status }}')">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center py-4 text-muted">Tidak ada pesanan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($orders->hasPages())
    <nav class="mt-3">
        <ul class="pagination pagination-sm justify-content-end mb-0">
            @for ($i = 1; $i <= $orders->lastPage(); $i++)
            <li class="page-item {{ $i === $orders->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="{{ $orders->url($i) }}">{{ $i }}</a>
            </li>
            @endfor
        </ul>
    </nav>
    @endif
</div>
@endsection

@push('scripts')
<script>
function updateStatus(id, currentStatus) {
    const options = {
        pending:    'Menunggu',
        diproses:   'Diproses',
        dikirim:    'Dikirim',
        selesai:    'Selesai',
        dibatalkan: 'Dibatalkan'
    };

    let inputHtml = '<select id="swal-status" class="swal2-input">';
    for (const [val, label] of Object.entries(options)) {
        inputHtml += `<option value="${val}" ${val === currentStatus ? 'selected' : ''}>${label}</option>`;
    }
    inputHtml += '</select>';

    Swal.fire({
        title: 'Update Status Pesanan',
        html: `<p style="color:#78716C;margin-bottom:8px;">Pilih status baru untuk pesanan ini:</p>${inputHtml}`,
        showCancelButton: true,
        confirmButtonText: 'Update',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#F97316',
        cancelButtonColor: '#78716C',
        preConfirm: () => document.getElementById('swal-status').value
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById('status-input-' + id).value = result.value;
            document.getElementById('form-status-' + id).submit();
        }
    });
}
</script>
@endpush
