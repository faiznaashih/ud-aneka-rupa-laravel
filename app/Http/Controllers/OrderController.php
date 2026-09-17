<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    // Form Checkout - baca dari keranjang (session)
    public function create(Request $request): View|RedirectResponse
    {
        $cart = session('cart', []);

        if (empty($cart) && ! session()->has('order_success')) {
            return redirect()->route('products.index')->with('error', 'Keranjang Anda masih kosong.');
        }

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
            $items[] = ['product' => $product, 'qty' => $qty, 'subtotal' => $subtotal];
        }

        return view('orders.create', compact('items', 'total'));
    }

    // Submit Pemesanan - proses semua item di keranjang jadi 1 pesanan
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[0-9\-\s]{10,13}$/'],
            'address' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ], [
            'phone.regex' => 'Format nomor HP tidak valid.',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Keranjang Anda masih kosong.');
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        // Validasi stok semua item dulu sebelum simpan apapun
        foreach ($cart as $productId => $qty) {
            $product = $products->get($productId);
            if (! $product || $qty > $product->stock) {
                return back()->withErrors(['quantity' => "Stok \"{$product?->name}\" tidak mencukupi."])->withInput();
            }
        }

        $order = DB::transaction(function () use ($validated, $cart, $products) {
            $customer = Customer::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ]);

            $order = Order::create([
                'customer_id' => $customer->id,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'total' => 0,
            ]);

            $total = 0;
            foreach ($cart as $productId => $qty) {
                $product = $products->get($productId);
                $subtotal = $product->price * $qty;
                $total += $subtotal;

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total' => $total]);

            return $order;
        });

        // Kosongkan keranjang setelah pesanan berhasil
        session()->forget('cart');

        return redirect()
            ->route('orders.create')
            ->with('order_success', $order->order_code);
    }

    // Cek Status Pesanan
    public function cekStatus(Request $request): View
    {
        $kode = $request->input('kode_pesanan', $request->query('kode', ''));

        $order = null;
        $notFound = false;

        if ($kode) {
            $order = Order::with(['customer', 'items.product'])
                ->where('order_code', $kode)
                ->first();

            if (! $order) {
                $notFound = true;
            }
        }

        return view('orders.cek-status', [
            'order' => $order,
            'kodeInput' => $kode,
            'notFound' => $notFound,
        ]);
    }
}
