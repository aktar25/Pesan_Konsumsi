<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dapur - Rental PS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .blink { animation: blinker 1s linear infinite; }
        @keyframes blinker { 50% { opacity: 0; } }
        body { background-color: #212529; color: white; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-secondary mb-4 shadow">
        <div class="container">
            <span class="navbar-brand fw-bold">
                <i class="fa fa-utensils me-2"></i> ADMIN DAPUR
            </span>
            <button class="btn btn-warning btn-sm fw-bold shadow-sm" onclick="testAudio()">
                <i class="fa fa-volume-up me-1"></i> Klik Tes Suara Dulu
            </button>
        </div>
    </nav>

    <div class="container">

        <div class="alert alert-info d-flex align-items-center mb-4 text-dark shadow-sm">
            <i class="fa fa-info-circle fa-2x me-3"></i>
            <div>
                <strong>Monitor Aktif!</strong> Halaman ini akan mengecek pesanan baru setiap 5 detik.<br>
                Pastikan tombol "Tes Suara" sudah diklik agar notifikasi bunyi.
            </div>
        </div>

        <div class="card bg-dark text-white border-secondary shadow-lg">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="fa fa-fire me-2"></i> Daftar Pesanan Masuk</h5>
                <span class="badge bg-light text-danger fw-bold">Live Update</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 text-center align-middle">
                        <thead>
                            <tr>
                                <th>Meja</th>
                                <th>Nama</th>
                                <th>Menu Pesanan</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr class="{{ $order->status == 'pending' ? 'table-active border border-warning border-3' : '' }}">
                                <td class="fs-4 fw-bold text-warning">{{ $order->table_number }}</td>
                                <td class="fw-bold">{{ $order->customer_name }}</td>
                                <td class="text-start">
                                    <ul class="list-unstyled mb-0 small">
                                        @foreach($order->items as $item)
                                            <li class="mb-1">
                                                <span class="badge bg-primary rounded-pill me-1">{{ $item->quantity }}x</span>
                                                {{ $item->product->name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td>
                                    @if($order->status == 'pending')
                                        <form action="{{ route('admin.complete', $order->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-success btn-sm w-100 fw-bold shadow-sm">
                                                <i class="fa fa-check me-1"></i> Selesai
                                            </button>
                                        </form>
                                        <div class="badge bg-danger blink mt-2 w-100">BARU MASUK!</div>
                                    @else
                                        <span class="badge bg-secondary w-100 py-2"><i class="fa fa-check-double me-1"></i> Beres</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-5 text-muted">
                                    <i class="fa fa-coffee fa-3x mb-3"></i><br>
                                    Belum ada pesanan masuk. Santai dulu... ☕
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- AUDIO -->
    <audio id="notifSound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3"></audio>

    <!-- LOGIKA JAVASCRIPT DIPERBAIKI -->
    <script>
        function playSound() {
            var audio = document.getElementById("notifSound");
            audio.play().catch(error => { console.log("Klik Tes Suara dulu!"); });
        }

        function testAudio() {
            playSound();
            alert("🔔 Ting-nong! Kalau bunyi, berarti sistem aman!");
        }

        // Kita set -1 agar saat loading pertama kali tidak dianggap "tambah pesanan"
        let lastPendingCount = -1;

        setInterval(function() {
            $.ajax({
                url: "{{ route('admin.check') }}",
                method: "GET",
                success: function(response) {
                    console.log("Cek Order:", response.pending_count); // Cek Console browser (F12)

                    // 1. Jika ini pertama kali load (masih -1), simpan jumlahnya saja
                    if (lastPendingCount === -1) {
                        lastPendingCount = response.pending_count;
                        return;
                    }

                    // 2. Jika jumlah pending bertambah (misal dari 0 jadi 1, atau 2 jadi 3)
                    if (response.pending_count > lastPendingCount) {
                        playSound();
                        // Kita kasih delay dikit biar suaranya kedengeran dulu baru reload
                        setTimeout(function() {
                            alert("🔔 PESANAN BARU MASUK! Cek Dapur!");
                            window.location.reload();
                        }, 500);
                    }

                    lastPendingCount = response.pending_count;
                },
                error: function(xhr) {
                    console.log("Gagal koneksi ke server", xhr);
                }
            });
        }, 3000); // Saya percepat jadi 3 detik sekali biar lebih responsif
    </script>

</body>
</html>
