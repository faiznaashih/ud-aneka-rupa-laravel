<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    // Halaman Keranjang
    public function index(): View
    {
        $cart = session('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $qty) {
            $product = $products->get($productId);
            if (! $product) {
                continue;
            }
            $subtotal = $product->price * $qty;
            $total += $subtotal;
            $items[] = [
                'product' => $product,
                'qty' => $qty,
                'subtotal' => $subtotal,
            ];
        }

        return view('cart.index', compact('items', 'total'));
    }

    // Tambah produk ke keranjang
    public function add(Request $request, Product $product): RedirectResponse
    {
        $qty = max(1, (int) $request->input('quantity', 1));

        if (! $product->is_active || $product->stock <= 0) {
            return back()->with('error', 'Produk tidak tersedia.');
        }

        $cart = session('cart', []);
        $currentQty = $cart[$product->id] ?? 0;
        $newQty = min($currentQty + $qty, $product->stock);

        $cart[$product->id] = $newQty;
        session(['cart' => $cart]);

        return back()->with('success', "\"{$product->name}\" ditambahkan ke keranjang.");
    }

    // Update jumlah item di keranjang
    public function update(Request $request, Product $product): RedirectResponse
    {
        $qty = max(1, (int) $request->input('quantity', 1));
        $qty = min($qty, $product->stock);

        $cart = session('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id] = $qty;
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Keranjang diperbarui.');
    }

    // Hapus item dari keranjang
    public function remove(Product $product): RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}
