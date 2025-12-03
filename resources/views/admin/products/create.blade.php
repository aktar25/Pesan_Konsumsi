<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tambah Menu Baru</h5>
                </div>
                <div class="card-body">
                    <!-- ENCTYPE WAJIB ADA UNTUK UPLOAD GAMBAR -->
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label>Nama Makanan</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Harga (Rupiah)</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Foto Makanan</label>
                            <input type="file" name="image" class="form-control" required>
                        </div>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-success">Simpan Menu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
