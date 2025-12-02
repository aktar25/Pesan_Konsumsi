<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class ProductController extends Controller
{

    // MENU UTAMA
    public function index()
    {
        $products = Product::all();
        return view('menu', ['products' => $products]);
    }

    // masukkan keranjang
     public function addToCart($id)
    {
        $product = Product::findOrFail($id);

        // Ambil keranjang lama dari session
        $cart = session()->get('cart', []);

        // Cek apakah barang sudah ada
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk berhasil masuk keranjang!');
    }

    // --- FUNGSI LIHAT KERANJANG ---
    public function cart()
    {
        return view('cart'); // Kita akan buat file view ini nanti
    }

    // --- FUNGSI HAPUS ITEM DARI KERANJANG ---
    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Produk berhasil dihapus');
        }
    }

    // proses checkout
    public function checkout(Request $request)
    {
        // Validasi
        $request->validate([
            'customer_name' => 'required',
            'table_number' => 'required',
        ]);

        // Cek Keranjang
        $cart = session()->get('cart');
        if(!$cart) {
            return redirect()->back()->with('error', 'Keranjang masih kosong!');
        }

        // Hitung Total
        $total = 0;
        foreach($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        // Simpan Order
        $order = Order::create([
            'customer_name' => $request->customer_name,
            'table_number' => $request->table_number,
            'total_price' => $total,
            'status' => 'pending'
        ]);

        // Simpan Detail Item
        foreach($cart as $id => $details) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price']
            ]);
        }

        // Bersihkan Keranjang
        session()->forget('cart');
        return redirect('/')->with('success', 'Pesanan BERHASIL dibuat! Mohon tunggu...');
    }

}

