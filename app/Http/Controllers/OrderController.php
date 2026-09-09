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
    // Form Pemesanan (pesan.php)
    public function create(Request $request): View|RedirectResponse
    {
        $slug = $request->query('product');
        $qty = max(1, (int) $request->query('qty', 1));

        if (! $slug) {
            return redirect()->route('products.index');
        }

        $product = Product::where('slug', $slug)->where('is_active', true)->first();

        if (! $product || $product->stock <= 0) {
            return redirect()->route('products.index');
        }

        return view('orders.create', compact('product', 'qty'));
    }

    // Submit Pemesanan
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[0-9\-\s]{10,13}$/'],
            'address' => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ], [
            'phone.regex' => 'Format nomor HP tidak valid.',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($validated['quantity'] > $product->stock) {
            return back()->withErrors(['quantity' => "Stok tidak mencukupi. Stok tersedia: {$product->stock}"])->withInput();
        }

        $order = DB::transaction(function () use ($validated, $product) {
            $customer = Customer::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ]);

            $subtotal = $product->price * $validated['quantity'];

            $order = Order::create([
                'customer_id' => $customer->id,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'total' => $subtotal,
            ]);

            $order->items()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'quantity' => $validated['quantity'],
                'subtotal' => $subtotal,
            ]);

            return $order;
        });

        return redirect()
            ->route('orders.create', ['product' => $product->slug])
            ->with('order_success', $order->order_code);
    }

    // Cek Status Pesanan (cek_status.php) - form dan hasil di satu halaman
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
