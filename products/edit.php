<?php
require_once '../includes/auth.php';
requireLogin();

// Simulasi data produk
$product_id = $_GET['id'] ?? 1;
$products = [
    1 => [
        'id' => 1,
        'barcode' => '899999900001',
        'name' => 'Kopi Kapal Api 200gr',
        'category_id' => 1,
        'price' => 15000,
        'cost' => 12500,
        'stock' => 45,
        'is_active' => 1
    ],
    2 => [
        'id' => 2,
        'barcode' => '899999900002',
        'name' => 'Indomie Goreng',
        'category_id' => 2,
        'price' => 3500,
        'cost' => 2800,
        'stock' => 12,
        'is_active' => 1
    ],
    3 => [
        'id' => 3,
        'barcode' => '899999900003',
        'name' => 'Aqua 600ml',
        'category_id' => 1,
        'price' => 3000,
        'cost' => 2200,
        'stock' => 3,
        'is_active' => 1
    ]
];

$product = $products[$product_id] ?? $products[1];

$page_title = "Edit Produk";
$page = 'produk';
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Produk</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Produk</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                            <h3 class="card-title">Form Edit Produk</h3>
                        </div>
                        
                        <form id="editProductForm">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" class="form-control" id="barcode" 
                                                   value="<?php echo $product['barcode']; ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="name">Nama Produk</label>
                                            <input type="text" class="form-control" id="name" 
                                                   value="<?php echo $product['name']; ?>" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="category_id">Kategori</label>
                                            <select class="form-control" id="category_id" required>
                                                <option value="1" <?php echo $product['category_id'] == 1 ? 'selected' : ''; ?>>Minuman</option>
                                                <option value="2" <?php echo $product['category_id'] == 2 ? 'selected' : ''; ?>>Makanan</option>
                                                <option value="3" <?php echo $product['category_id'] == 3 ? 'selected' : ''; ?>>Snack</option>
                                                <option value="4" <?php echo $product['category_id'] == 4 ? 'selected' : ''; ?>>Rokok</option>
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
                                                <input type="number" class="form-control" id="price" 
                                                       value="<?php echo $product['price']; ?>" min="0" required>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="cost">Harga Beli</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" class="form-control" id="cost" 
                                                       value="<?php echo $product['cost']; ?>" min="0" required>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="stock">Stok</label>
                                            <input type="number" class="form-control" id="stock" 
                                                   value="<?php echo $product['stock']; ?>" min="0" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="is_active">Status</label>
                                            <select class="form-control" id="is_active">
                                                <option value="1" <?php echo $product['is_active'] == 1 ? 'selected' : ''; ?>>Aktif</option>
                                                <option value="0" <?php echo $product['is_active'] == 0 ? 'selected' : ''; ?>>Tidak Aktif</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update
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
document.getElementById('editProductForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Produk berhasil diupdate!');
    window.location.href = 'index.php';
});
</script>

<?php include '../includes/footer.php'; ?>