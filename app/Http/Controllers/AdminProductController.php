<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class AdminProductController extends Controller
{
    // 1. LIHAT DAFTAR MENU
    public function index()
    {
        $products = Product::all();
        return view('admin.products.index', ['products' => $products]);
    }

    // 2. TAMPILKAN FORM TAMBAH
    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required',
        ]);

        // Proses Upload Gambar
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => 'images/' . $imageName, // Simpan path-nya
            'stock' => 100
        ]);

        return redirect()->route('products.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    // 4. TAMPILKAN FORM EDIT
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', ['product' => $product]);
    }

    // 5. SIMPAN PERUBAHAN (UPDATE)
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
        ];

        // Cek apakah admin upload gambar baru?
        if ($request->hasFile('image')) {
            // Hapus gambar lama biar server gak penuh
            if(File::exists(public_path($product->image))){
                File::delete(public_path($product->image));
            }

            // Upload gambar baru
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $data['image'] = 'images/' . $imageName;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Menu berhasil diupdate!');
    }

    // 6. HAPUS MENU (DELETE)
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Hapus gambarnya juga
        if(File::exists(public_path($product->image))){
            File::delete(public_path($product->image));
        }

        $product->delete();
        return redirect()->back()->with('success', 'Menu berhasil dihapus!');
    }
}
