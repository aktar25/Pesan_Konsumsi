<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- JQuery (Wajib untuk fitur Hapus) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🛒 Keranjang Pesanan</h2>
        <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Tambah Menu Lain
        </a>
    </div>

    <!-- Menampilkan pesan error validasi (misal lupa isi nama) -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40%" class="ps-4">Menu</th>
                            <th style="width:20%">Harga</th>
                            <th style="width:15%">Jumlah</th>
                            <th style="width:20%" class="text-end pe-4">Subtotal</th>
                            <th style="width:5%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0 @endphp
                        @if(session('cart'))
                            @foreach(session('cart') as $id => $details)
                                @php $total += $details['price'] * $details['quantity'] @endphp
                                <tr data-id="{{ $id }}">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <!-- Gambar Kecil (Thumbnail) -->
                                            <img src="{{ $details['image'] }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div class="fw-bold">{{ $details['name'] }}</div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-secondary rounded-pill px-3">{{ $details['quantity'] }}</span>
                                    </td>
                                    <td class="text-end pe-4 fw-bold">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <!-- Tombol Hapus -->
                                        <button class="btn btn-danger btn-sm remove-from-cart" title="Hapus Menu">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa fa-shopping-basket fa-2x mb-3 text-secondary"></i><br>
                                    Keranjang masih kosong nih. Yuk pesan dulu!
                                </td>
                            </tr>
                        @endif
                    </tbody>

                    <!-- FOOTER TABEL (Formulir Checkout) -->
                    @if(session('cart'))
                    <tfoot class="bg-white">
                        <tr>
                            <td colspan="5" class="text-end p-4 border-bottom-0">
                                <h4 class="mb-0">Total Bayar: <span class="text-success fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</span></h4>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="p-4 bg-light border-top">
                                <!-- MULAI FORM CHECKOUT -->
                                <form action="{{ route('checkout') }}" method="POST">
                                    @csrf
                                    <div class="row justify-content-end">
                                        <div class="col-md-5">
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-body">
                                                    <h6 class="card-title fw-bold mb-3"><i class="fa fa-user-edit me-2"></i>Data Pemesan</h6>

                                                    <div class="mb-3">
                                                        <input type="text" name="customer_name" class="form-control" placeholder="Nama Kamu (Cth: Rizky)" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <input type="text" name="table_number" class="form-control" placeholder="Posisi (Cth: Meja 3 / PC-05)" required>
                                                    </div>

                                                    <button type="submit" class="btn btn-success w-100 btn-lg fw-bold shadow-sm hover-effect">
                                                        🚀 KIRIM PESANAN
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!-- SELESAI FORM -->
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT PENGHAPUS DATA (AJAX) -->
<script type="text/javascript">
    $(".remove-from-cart").click(function (e) {
        e.preventDefault();

        var ele = $(this);

        if(confirm("Yakin mau menghapus menu ini dari keranjang?")) {
            $.ajax({
                url: "{{ route('remove.from.cart') }}",
                method: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: ele.parents("tr").attr("data-id")
                },
                success: function (response) {
                    window.location.reload();
                }
            });
        }
    });
</script>

<style>
    .hover-effect:hover { transform: translateY(-2px); transition: 0.3s; }
</style>

</body>
</html>
