<!DOCTYPE html>
<html lang="id>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Menu Rental PS</title>
        <!-- 1. Panggil CSS Bootstrap (Biar Rapi) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- 2. Panggil Font Awesome (Buat Ikon) -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body class="bg-light">

    <!-- BAGIAN 1: NAVBAR (Judul Atas) -->
    <nav class="navbar navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fa fa-gamepad me-2"></i> KANTIN RENTAL PS
            </a>
        </div>
    </nav>

    <div class="container mb-5">

        <!-- BAGIAN 2: ALERT (Pesan Muncul Sesaat) -->
        <!-- Kalau ada pesan sukses dari Controller, tampilkan kotak hijau ini -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <h3 class="mb-4 text-center fw-bold text-secondary">Mau makan apa hari ini? 🍜</h3>

        <!-- BAGIAN 3: DAFTAR MAKANAN (Looping) -->
        <div class="row">
            <!-- Kita ambil variabel $products dari Controller, lalu kita ulang (loop) -->
            @forelse($products as $item)
            <div class="col-6 col-md-3 mb-4">
                <div class="card h-100 shadow-sm border-0 hover-effect">
                    <!-- Gambar Makanan -->
                    <img src="{{ $item->image }}" class="card-img-top" alt="{{ $item->name }}" style="height: 150px; object-fit: cover;">

                    <div class="card-body d-flex flex-column">
                        <!-- Nama Makanan -->
                        <h5 class="card-title fs-6 fw-bold">{{ $item->name }}</h5>
                        <!-- Deskripsi Pendek -->
                        <p class="card-text text-muted small flex-grow-1">{{ Str::limit($item->description, 50) }}</p>

                        <!-- Harga -->
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="text-primary fw-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        </div>

                        <!-- Tombol Tambah (Link ke Route) -->
                        <a href="{{ route('add.to.cart', $item->id) }}" class="btn btn-outline-primary btn-sm mt-2 w-100">
                            <i class="fa fa-plus"></i> Tambah
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <!-- Ini muncul kalau database kosong -->
            <div class="col-12 text-center py-5">
                <h4 class="text-muted">Belum ada menu makanan yang tersedia.</h4>
                <p>Minta admin untuk menjalankan <code>php artisan db:seed</code> ya!</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- BAGIAN 4: TOMBOL KERANJANG MELAYANG -->
    <div class="fixed-bottom m-4 text-end">
        <a href="{{ route('cart') }}" class="btn btn-warning btn-lg shadow-lg rounded-pill px-4 fw-bold">
            <i class="fa fa-shopping-cart me-2"></i> Keranjang
            <!-- Menghitung jumlah barang di session cart -->
            <span class="badge bg-danger ms-1">{{ count((array) session('cart')) }}</span>
        </a>
    </div>

</body>
</html>
