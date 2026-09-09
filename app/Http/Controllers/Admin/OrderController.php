<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->input('status', '');
        $search = $request->input('search', '');

        $orders = Order::with(['customer', 'items'])
            ->when($filter, fn ($q) => $q->where('status', $filter))
            ->when($search, function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $statuses = ['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan'];
        $counts = [];
        foreach ($statuses as $s) {
            $counts[$s] = Order::where('status', $s)->count();
        }
        $counts['semua'] = array_sum($counts);

        return view('admin.orders.index', compact('orders', 'filter', 'search', 'counts'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,diproses,dikirim,selesai,dibatalkan'],
        ]);

        $order->update($validated);

        return redirect()
            ->route('admin.orders.index', $request->only('status') ? ['status' => $request->query('status')] : [])
            ->with('success', 'Status pesanan diperbarui!');
    }
}
