<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalProdukAktif = Product::where('is_active', true)->count();
        $totalPesanan = Order::count();
        $pesananBaru = Order::where('status', 'pending')->count();
        $totalPendapatan = Order::where('status', 'selesai')->sum('total');

        $pesananTerbaru = Order::with(['customer', 'items'])->latest()->take(5)->get();
        $stokRendah = Product::where('is_active', true)->where('stock', '<', 20)->orderBy('stock')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProdukAktif', 'totalPesanan', 'pesananBaru', 'totalPendapatan',
            'pesananTerbaru', 'stokRendah'
        ));
    }
}
