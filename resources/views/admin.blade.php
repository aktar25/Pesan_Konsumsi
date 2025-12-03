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
        #start-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.9); z-index: 9999;
            display: flex; justify-content: center; align-items: center; flex-direction: column;
        }
    </style>
</head>
<body>

    <div id="start-overlay">
        <h1 class="text-warning mb-4"><i class="fa fa-utensils"></i> ADMIN DAPUR</h1>
        <button class="btn btn-danger btn-lg fw-bold px-5 py-3 shadow" onclick="startMonitoring()">
            <i class="fa fa-power-off me-2"></i> KLIK UNTUK MULAI MONITORING
        </button>
        <p class="text-white mt-3">Wajib diklik agar suara notifikasi aktif.</p>
    </div>

    <nav class="navbar navbar-dark bg-secondary mb-4 shadow">
        <div class="container">
            <span class="navbar-brand fw-bold"><i class="fa fa-utensils me-2"></i> ADMIN DAPUR</span>
            <div class="d-flex">
                <a href="{{ route('products.index') }}" class="btn btn-outline-light btn-sm fw-bold me-2">
                    <i class="fa fa-edit"></i> Kelola Menu
                </a>
                <button class="btn btn-success btn-sm fw-bold shadow-sm disabled" id="status-btn">
                    <i class="fa fa-volume-up"></i> Suara Aktif
                </button>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Info Monitor -->
        <div class="alert alert-info d-flex align-items-center mb-4 text-dark shadow-sm">
            <i class="fa fa-info-circle fa-2x me-3"></i>
            <div>
                <strong>Sistem siap!</strong> Jangan tutup halaman ini. Notifikasi akan muncul otomatis.
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
        // 1. FUNGSI MULAI (Dipanggil saat klik tombol besar)
        function startMonitoring() {
            // Sembunyikan overlay
            $('#start-overlay').fadeOut();

            // Coba putar suara sebentar (pancingan)
            var audio = document.getElementById("notifSound");
            audio.play().then(() => {
                audio.pause();
                audio.currentTime = 0;
            }).catch(e => alert("Gagal akses audio. Pastikan izin browser diberikan."));

            // Minta Izin Notifikasi Desktop (Biar muncul pop-up di Windows)
            if (Notification.permission !== "granted") {
                Notification.requestPermission();
            }
        }

        // 2. FUNGSI BUNYIKAN ALARM & NOTIFIKASI
        function triggerAlarm() {
            // A. Mainkan Suara
            var audio = document.getElementById("notifSound");
            audio.currentTime = 0;
            audio.play().catch(e => console.log("Suara error:", e));

            // B. Tampilkan Notifikasi Desktop (Windows/Mac Notification)
            if (Notification.permission === "granted") {
                new Notification("PESANAN BARU! 🍜", {
                    body: "Ada pesanan masuk di dapur. Cek sekarang!",
                    icon: "https://cdn-icons-png.flaticon.com/512/3075/3075977.png"
                });
            }
        }

        // 3. UPDATE TABEL TANPA RELOAD
        function updateTableContent() {
            $.get("{{ route('admin.index') }}", function(htmlData) {
                var newTbody = $(htmlData).find('#order-table-body').html();
                $('#order-table-body').html(newTbody);
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

                    // JIKA ADA PESANAN BARU
                    if (response.pending_count > lastPendingCount) {
                        triggerAlarm(); // Panggil fungsi alarm komplit
                        updateTableContent(); // Update tabel
                    }

                   // Jika ada perubahan status (selesai/batal), tetap update tabel
                    if (response.pending_count !== lastPendingCount) {
                        updateTableContent();
                    }

                    // Simpan jumlah terakhir
                    lastPendingCount = response.pending_count;
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
