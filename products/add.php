<?php
require_once '../includes/auth.php';
requireLogin();

$page_title = "Tambah Produk";
$page = 'produk';
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Produk Baru</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Produk</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Form Produk</h3>
                        </div>
                        
                        <form id="productForm">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" class="form-control" id="barcode" placeholder="Scan atau masukkan barcode">
                                            <button type="button" class="btn btn-sm btn-secondary mt-1" onclick="generateBarcode()">
                                                <i class="fas fa-barcode"></i> Generate Barcode
                                            </button>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="name">Nama Produk</label>
                                            <input type="text" class="form-control" id="name" placeholder="Nama produk" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="category_id">Kategori</label>
                                            <select class="form-control" id="category_id" required>
                                                <option value="">Pilih Kategori</option>
                                                <option value="1">Minuman</option>
                                                <option value="2">Makanan</option>
                                                <option value="3">Snack</option>
                                                <option value="4">Rokok</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price">Harga Jual</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" class="form-control" id="price" placeholder="0" min="0" required>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="cost">Harga Beli</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" class="form-control" id="cost" placeholder="0" min="0" required>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="stock">Stok Awal</label>
                                            <input type="number" class="form-control" id="stock" placeholder="0" min="0" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="is_active">Status</label>
                                            <select class="form-control" id="is_active">
                                                <option value="1">Aktif</option>
                                                <option value="0">Tidak Aktif</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <a href="index.php" class="btn btn-default">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function generateBarcode() {
    const barcode = Math.floor(Math.random() * 900000000000) + 100000000000;
    document.getElementById('barcode').value = barcode;
}

document.getElementById('productForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Produk berhasil disimpan!');
    window.location.href = 'index.php';
});
</script>

<?php include '../includes/footer.php'; ?>