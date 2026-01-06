<?php
require_once '../includes/auth.php';
requireLogin();

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
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Produk</li>
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
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari produk..." id="searchProduct">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
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
                                            <th>Harga Beli</th>
                                            <th>Stok</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td><span class="badge badge-light">899999900001</span></td>
                                            <td>Kopi Kapal Api 200gr</td>
                                            <td>Minuman</td>
                                            <td>Rp 15.000</td>
                                            <td>Rp 12.500</td>
                                            <td>
                                                <span class="badge badge-success">45</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">Aktif</span>
                                            </td>
                                            <td>
                                                <a href="edit.php?id=1" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td><span class="badge badge-light">899999900002</span></td>
                                            <td>Indomie Goreng</td>
                                            <td>Makanan</td>
                                            <td>Rp 3.500</td>
                                            <td>Rp 2.800</td>
                                            <td>
                                                <span class="badge badge-warning">12</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">Aktif</span>
                                            </td>
                                            <td>
                                                <a href="edit.php?id=2" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td><span class="badge badge-light">899999900003</span></td>
                                            <td>Aqua 600ml</td>
                                            <td>Minuman</td>
                                            <td>Rp 3.000</td>
                                            <td>Rp 2.200</td>
                                            <td>
                                                <span class="badge badge-danger">3</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">Aktif</span>
                                            </td>
                                            <td>
                                                <a href="edit.php?id=3" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#">Previous</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include '../includes/footer.php'; ?>