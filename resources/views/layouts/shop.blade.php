<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UD Aneka Rupa')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>

<!-- Loading Overlay -->
<div id="loading-overlay">
    <div class="loader-wrap">
        <div class="loader-circle"></div>
        <p class="loader-text">Memuat...</p>
    </div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light sticky-top" id="mainNavbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <div class="brand-icon">
                <i class="fa-solid fa-cookie-bite"></i>
            </div>
            <div>
                <span class="brand-name">UD Aneka Rupa</span>
                <small class="brand-tagline d-block">Pabrik Kerupuk</small>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fa-solid fa-house me-1"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        <i class="fa-solid fa-boxes-stacked me-1"></i> Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('orders.cek-status') ? 'active' : '' }}" href="{{ route('orders.cek-status') }}">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Cek Pesanan
                    </a>
                </li>
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-order" href="{{ route('products.index') }}">
                        <i class="fa-solid fa-cart-shopping me-1"></i> Pesan Sekarang
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

@if (session('success'))
    <div class="container mt-3">
        <div class="alert alert-success">{{ session('success') }}</div>
    </div>
@endif
@if (session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger">{{ session('error') }}</div>
    </div>
@endif

@yield('content')

<!-- Footer -->
<footer class="footer mt-auto">
    <div class="footer-top">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand mb-3">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="brand-icon-sm">
                                <i class="fa-solid fa-cookie-bite"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 text-white fw-bold">UD Aneka Rupa</h5>
                                <small class="text-warning">Pabrik Kerupuk</small>
                            </div>
                        </div>
                        <p class="footer-desc">Produsen kerupuk berkualitas dengan cita rasa autentik sejak tahun 1985.</p>
                    </div>
                    <div class="social-links">
                        <a href="https://wa.me/6281913207335" target="_blank" class="social-link"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="footer-heading">Menu</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('products.index') }}">Produk</a></li>
                        <li><a href="{{ route('orders.cek-status') }}">Cek Pesanan</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="footer-heading">Kategori</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('products.index', ['category' => 'original']) }}">Original</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'pedas']) }}">Pedas</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'gurih']) }}">Gurih</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'manis']) }}">Manis</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h6 class="footer-heading">Kontak Kami</h6>
                    <ul class="footer-contact">
                        <li><i class="fa-solid fa-location-dot text-warning"></i><span>Jl. Industri No. 45, Sidoarjo, Jawa Timur</span></li>
                        <li><i class="fa-solid fa-phone text-warning"></i><span>+62 819-1320-7335</span></li>
                        <li><i class="fa-solid fa-clock text-warning"></i><span>Senin - Sabtu: 08.00 - 17.00 WIB</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <p class="mb-0">&copy; {{ date('Y') }} <strong>UD Aneka Rupa</strong>. Semua hak dilindungi.</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
@stack('scripts')

</body>
</html>