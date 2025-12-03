<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; // <--- Wajib, biar bisa baca data pesanan

class AdminController extends Controller
{
    // 1. TAMPILKAN DASHBOARD
    public function index()
    {
        // Ambil data order terbaru
        $orders = Order::with('items.product')->latest()->get();
        return view('admin', ['orders' => $orders]);
    }

    // 2. CEK PESANAN BARU (Untuk Auto Refresh)
    public function checkNewOrder()
    {
        $pendingCount = Order::where('status', 'pending')->count();
        return response()->json(['pending_count' => $pendingCount]);
    }

    // 3. TANDAI PESANAN SELESAI
    public function complete($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'completed']);
        return redirect()->back()->with('success', 'Pesanan selesai!');
    }

    // FUNGSI CANCEL
    public function cancel($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'cancelled']);
        return redirect()->back()->with('warning', 'Pesanan dibatalkan!');
    }
}
