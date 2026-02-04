<?php
require_once '../api/auth.php';
requireLogin();
require_once '../api/products.php';
require_once '../api/categories.php';

$error = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit();
}

// Proses update produk
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $barcode = trim($_POST['barcode']);
    $name = trim($_POST['name']);
    $category_id = (int)$_POST['category_id'];
    $price = (float)$_POST['price'];
    $cost = (float)$_POST['cost'];
    $stock = (int)$_POST['stock'];
    $is_active = (int)$_POST['is_active'];

    // echo $id;
    // echo "<br>";
    // echo $barcode;
    // echo "<br>";
    // echo $name;
    // echo "<br>";
    // echo $category_id;
    // echo "<br>";
    // echo $price;
    // echo "<br>";
    // echo $cost;
    // echo "<br>";
    // echo $stock;
    // echo "<br>";
    // echo $is_active;
    // die();
    
    if (empty($barcode) || empty($name) || empty($category_id)) {
        $error = 'Barcode, nama produk, dan kategori harus diisi!';
    } else {
        $result = updateProduct($id, $barcode, $name, $category_id, $price, $cost, $stock, $is_active);
        if ($result['success']) {
            header('Location: index.php?msg=updated');
            exit();
        } else {
            $error = $result['message'];
        }
    }
}

// Ambil data produk
$product = getProduct($id);
if (!$product) {
    header('Location: index.php');
    exit();
}

// Ambil list kategori
$categories = getCategories();

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
                        <li class="breadcrumb-item"><a href="../index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Manajemen Produk</a></li>
                        <li class="breadcrumb-item active">Edit Produk</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Form Edit Produk</h3>
                        </div>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger m-3"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="barcode">Barcode *</label>
                                            <input type="text" class="form-control" name="barcode" id="barcode" 
                                                   placeholder="Masukkan barcode" required 
                                                   value="<?php echo isset($_POST['barcode']) ? $_POST['barcode'] : $product['barcode']; ?>">
                                            <button type="button" class="btn btn-sm btn-secondary mt-1" onclick="generateBarcode()">
                                                <i class="fas fa-barcode"></i> Generate Barcode
                                            </button>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="name">Nama Produk *</label>
                                            <input type="text" class="form-control" name="name" id="name" 
                                                   placeholder="Nama produk" required
                                                   value="<?php echo isset($_POST['name']) ? $_POST['name'] : $product['name']; ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="category_id">Kategori *</label>
                                            <select class="form-control" name="category_id" id="category_id" required>
                                                <option value="">Pilih Kategori</option>
                                                <?php foreach ($categories as $cat): ?>
                                                    <?php
                                                    $selected = '';
                                                    if (isset($_POST['category_id'])) {
                                                        $selected = ($_POST['category_id'] == $cat['id']) ? 'selected' : '';
                                                    } else {
                                                        $selected = ($product['category_id'] == $cat['id']) ? 'selected' : '';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $cat['id']; ?>" <?php echo $selected; ?>>
                                                        <?php echo $cat['name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price">Harga Jual *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" class="form-control" name="price" id="price" 
                                                       placeholder="0" min="0" required
                                                       value="<?php echo isset($_POST['price']) ? $_POST['price'] : $product['price']; ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="cost">Harga Beli *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" class="form-control" name="cost" id="cost" 
                                                       placeholder="0" min="0" required
                                                       value="<?php echo isset($_POST['cost']) ? $_POST['cost'] : $product['cost']; ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="stock">Stok *</label>
                                            <input type="number" class="form-control" name="stock" id="stock" 
                                                   placeholder="0" min="0" required
                                                   value="<?php echo isset($_POST['stock']) ? $_POST['stock'] : $product['stock']; ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="is_active">Status</label>
                                            <select class="form-control" name="is_active" id="is_active">
                                                <?php
                                                $active_status = isset($_POST['is_active']) ? $_POST['is_active'] : $product['is_active'];
                                                ?>
                                                <option value="1" <?php echo ($active_status == 1) ? 'selected' : ''; ?>>Aktif</option>
                                                <option value="0" <?php echo ($active_status == 0) ? 'selected' : ''; ?>>Tidak Aktif</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <button type="submit" class="btn btn-warning">
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
function generateBarcode() {
    var randomCode = Math.floor(Math.random() * 9000000000000) + 1000000000000;
    document.getElementById('barcode').value = randomCode;
}
</script>
<?php include '../includes/footer.php'; ?>
