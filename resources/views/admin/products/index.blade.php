@extends('layouts.admin')

@section('title', 'Kelola Produk')

@section('content')
<div class="page-card">
    <div class="page-card-header">
        <h6 class="page-card-title"><i class="fa-solid fa-boxes-stacked me-2" style="color:var(--primary);"></i>Daftar Produk ({{ $products->total() }})</h6>
        <a href="{{ route('admin.products.create') }}" class="btn-orange" style="border-radius:50px;padding:8px 18px;font-size:.83rem;">
            <i class="fa-solid fa-plus me-1"></i> Tambah Produk
        </a>
    </div>

    <form method="GET" class="mb-3">
        <div class="d-flex gap-2" style="max-width:360px;">
            <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
            <button type="submit" class="btn-orange px-3" style="border-radius:10px;"><i class="fa-solid fa-search"></i></button>
            @if (request('search'))
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $i => $p)
                <tr>
                    <td class="text-muted small">{{ $products->firstItem() + $i }}</td>
                    <td>
                        <img src="{{ $p->image ? asset('storage/' . $p->image) : 'https://placehold.co/80x80/FFF7ED/F97316?text=IMG' }}" class="img-preview" alt="">
                    </td>
                    <td>
                        <div class="fw-600">{{ $p->name }}</div>
                        <div class="text-muted small">{{ $p->berat_gram }}g</div>
                    </td>
                    <td><span class="badge bg-secondary text-white">{{ ucfirst($p->category) }}</span></td>
                    <td class="fw-700" style="color:var(--primary);">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $p->stock <= 5 ? 'bg-danger' : ($p->stock <= 20 ? 'bg-warning text-dark' : 'bg-success') }}">
                            {{ $p->stock }}
                        </span>
                    </td>
                    <td>
                        @if ($p->is_active)
                        <span class="badge badge-status bg-success">Aktif</span>
                        @else
                        <span class="badge badge-status bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-sm btn-warning" title="Edit" style="border-radius:6px;">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form id="del-prod-{{ $p->id }}" action="{{ route('admin.products.destroy', $p) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" title="Hapus" style="border-radius:6px;"
                                        onclick="confirmDelete('del-prod-{{ $p->id }}')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">Produk tidak ditemukan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($products->hasPages())
    <nav class="mt-3">
        <ul class="pagination pagination-sm justify-content-end mb-0">
            @for ($i = 1; $i <= $products->lastPage(); $i++)
            <li class="page-item {{ $i === $products->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
            </li>
            @endfor
        </ul>
    </nav>
    @endif
</div>
@endsection
