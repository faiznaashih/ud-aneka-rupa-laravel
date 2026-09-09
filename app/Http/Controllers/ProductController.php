<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    // Beranda: hero, fitur, produk unggulan, CTA, testimoni
    public function home(): View
    {
        $products = Product::where('is_active', true)->latest()->take(6)->get();
        $totalProduk = Product::where('is_active', true)->count();
        $totalPesananSelesai = Order::where('status', 'selesai')->count();

        return view('home', compact('products', 'totalProduk', 'totalPesananSelesai'));
    }

    // Halaman Daftar Produk (produk.php): search + filter kategori + pagination
    public function index(Request $request): View
    {
        $search = $request->input('search', '');
        $kategori = $request->input('kategori', '');

        $products = Product::query()
            ->where('is_active', true)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($kategori && $kategori !== 'semua', fn ($q) => $q->where('category', $kategori))
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('products.index', compact('products', 'search', 'kategori'));
    }

    // Halaman Detail Produk (detail.php)
    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $related = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(3)
            ->get();

        return view('products.show', compact('product', 'related'));
    }
}
