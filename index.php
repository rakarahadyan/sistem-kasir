<?php
require_once 'includes/auth.php';
requireLogin();

$page_title = "Dashboard";
$page = 'dashboard';
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>150</h3>
                            <p>Transaksi Hari Ini</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="transactions/index.php" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Rp 12.5<sup style="font-size: 20px">jt</sup></h3>
                            <p>Pendapatan Hari Ini</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <a href="reports/index.php" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>45</h3>
                            <p>Produk Stok Rendah</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <a href="products/index.php" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>350</h3>
                            <p>Total Pelanggan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="customers/index.php" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main row -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Grafik Penjualan 7 Hari Terakhir</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="salesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Produk Terlaris</h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="products-list product-list-in-card pl-2 pr-2">
                                <li class="item">
                                    <div class="product-info">
                                        <a href="javascript:void(0)" class="product-title">
                                            Kopi Kapal Api
                                            <span class="badge badge-warning float-right">Rp 15.000</span>
                                        </a>
                                        <span class="product-description">
                                            Terjual: 125 unit
                                        </span>
                                    </div>
                                </li>
                                <li class="item">
                                    <div class="product-info">
                                        <a href="javascript:void(0)" class="product-title">
                                            Indomie Goreng
                                            <span class="badge badge-info float-right">Rp 3.500</span>
                                        </a>
                                        <span class="product-description">
                                            Terjual: 98 unit
                                        </span>
                                    </div>
                                </li>
                                <li class="item">
                                    <div class="product-info">
                                        <a href="javascript:void(0)" class="product-title">
                                            Aqua 600ml
                                            <span class="badge badge-danger float-right">Rp 3.000</span>
                                        </a>
                                        <span class="product-description">
                                            Terjual: 87 unit
                                        </span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>