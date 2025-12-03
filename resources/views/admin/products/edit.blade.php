<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">Edit Menu: {{ $product->name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- Wajib untuk Update -->

                        <div class="mb-3">
                            <label>Nama Makanan</label>
                            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Harga</label>
                            <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label>Ganti Foto (Kosongkan jika tidak ingin ganti)</label>
                            <input type="file" name="image" class="form-control">
                            <small class="text-muted">Foto saat ini: <img src="{{ asset($product->image) }}" width="50"></small>
                        </div>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Update Menu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
