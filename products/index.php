<?php
require_once '../api/auth.php';
requireLogin();
require_once '../api/products.php';

// Proses delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $result = deleteProduct($id);
    if ($result['success']) {
        header('Location: index.php?msg=deleted');
        exit();
    }
}

// Ambil data produk
$search = isset($_GET['search']) ? $_GET['search'] : '';
$products = getProducts($search);

$page_title = "Manajemen Produk";
$page = 'produk';
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manajemen Produk</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active">Manajemen Produk</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Produk</h3>
                            <div class="card-tools">
                                <a href="add.php" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Produk
                                </a>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <?php if (isset($_GET['msg'])): ?>
                                <?php if ($_GET['msg'] == 'deleted'): ?>
                                    <div class="alert alert-success">Produk berhasil dihapus!</div>
                                <?php elseif ($_GET['msg'] == 'added'): ?>
                                    <div class="alert alert-success">Produk berhasil ditambahkan!</div>
                                <?php elseif ($_GET['msg'] == 'updated'): ?>
                                    <div class="alert alert-success">Produk berhasil diupdate!</div>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <form method="GET" action="">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="search" placeholder="Cari produk..." value="<?php echo $search; ?>">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="submit">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Barcode</th>
                                            <th>Nama Produk</th>
                                            <th>Kategori</th>
                                            <th>Harga</th>
                                            <th>Stok</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($products) > 0): ?>
                                            <?php $no = 1; ?>
                                            <?php foreach ($products as $product): ?>
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td><?php echo $product['barcode']; ?></td>
                                                    <td><?php echo $product['name']; ?></td>
                                                    <td><?php echo $product['category_name']; ?></td>
                                                    <td>Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                                                    <td><?php echo $product['stock']; ?></td>
                                                    <td>
                                                        <a href="edit.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-warning">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="index.php?delete=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">Tidak ada data produk</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include '../includes/footer.php'; ?>