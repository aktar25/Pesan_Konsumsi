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
        .row-cancelled { opacity: 0.5; background-color: #343a40 !important; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-secondary mb-4 shadow">
        <div class="container">
            <span class="navbar-brand fw-bold"><i class="fa fa-utensils me-2"></i> ADMIN DAPUR</span>
            <div class="d-flex">
                <a href="{{ route('products.index') }}" class="btn btn-outline-light btn-sm fw-bold me-2"><i class="fa fa-edit"></i> Kelola Menu</a>
                <button class="btn btn-warning btn-sm fw-bold shadow-sm" onclick="testAudio()"><i class="fa fa-volume-up me-1"></i> Tes Suara</button>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Info Monitor -->
        <div class="alert alert-info d-flex align-items-center mb-4 text-dark shadow-sm">
            <i class="fa fa-info-circle fa-2x me-3"></i>
            <div>
                <strong>Monitor Aktif!</strong> Halaman ini akan mengecek pesanan baru setiap 3 detik. <br>
                Halaman <b>tidak akan reload</b>, tabel akan berubah sendiri secara otomatis.
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
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <!-- PENTING: ID ini target update kita -->
                        <tbody id="order-table-body">
                            @include('admin.table_content')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <audio id="notifSound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3"></audio>

    <script>
        // 1. SETUP SUARA
        function playSound() {
            var audio = document.getElementById("notifSound");
            audio.currentTime = 0;
            return audio.play();
        }

        function testAudio() {
            playSound().then(() => {
                alert("🔔 Ting-nong! Sistem suara aman.");
            }).catch(error => {
                alert("Browser memblokir suara. Klik OK lalu klik sembarang tempat.");
            });
        }

        // 2. LOGIKA UTAMA
        let lastPendingCount = -1;

        setInterval(function() {
            $.ajax({
                url: "{{ route('admin.check') }}",
                method: "GET",
                success: function(response) {

                    // Inisialisasi awal (supaya gak bunyi pas baru dibuka)
                    if (lastPendingCount === -1) {
                        lastPendingCount = response.pending_count;
                        return;
                    }

                    // JIKA ADA PERUBAHAN JUMLAH PESANAN (Entah nambah atau berkurang/selesai)
                    if (response.pending_count !== lastPendingCount) {

                        // Kalau jumlahnya BERTAMBAH, bunyikan suara!
                        if (response.pending_count > lastPendingCount) {
                            playSound().catch(e => console.log("Suara gagal"));
                        }

                        // UPDATE TABEL OTOMATIS (Pakai teknik .load)
                        // Ini akan mengambil isi tabel terbaru dari server dan menempelkannya di sini
                        $('#order-table-body').load(location.href + " #order-table-body > *", function() {
                            console.log("Tabel berhasil diupdate!");
                        });
                    }

                    // Simpan jumlah terakhir
                    lastPendingCount = response.pending_count;
                },
                error: function(xhr) {
                    console.log("Gagal koneksi, coba refresh manual.");
                }
            });
        }, 3000); // Cek setiap 3 detik

        // Set awal
        $(document).ready(function() {
            $.ajax({ url: "{{ route('admin.check') }}", method: "GET", success: function(res) { lastPendingCount = res.pending_count; } });
        });
    </script>
</body>
</html>
