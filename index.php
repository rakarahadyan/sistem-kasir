<?php
require_once 'api/auth.php';
requireLogin();
require_once 'config/database.php';

$db = Database::getInstance()->getConnection();

// Total transaksi hari ini
$stmt = $db->query("
    SELECT COUNT(*) as count, COALESCE(SUM(total), 0) as total 
    FROM transactions 
    WHERE DATE(transaction_date) = CURDATE()
");
$result = $stmt->fetch_assoc();
$today_transactions = $result['count'];
$today_sales = $result['total'];

// Produk stok rendah (stok <= 10)
$stmt = $db->query("SELECT COUNT(*) as count FROM products WHERE stock <= 10 AND is_active = 1");
$result = $stmt->fetch_assoc();
$low_stock = $result['count'];

// Total pelanggan
$stmt = $db->query("SELECT COUNT(*) as count FROM customers");
$result = $stmt->fetch_assoc();
$total_customers = $result['count'];

// Produk terlaris (7 hari terakhir)
$stmt = $db->query("
    SELECT 
        p.name,
        p.price,
        SUM(ti.qty) as total_sold
    FROM transaction_items ti
    JOIN products p ON ti.product_id = p.id
    JOIN transactions t ON ti.transaction_id = t.id
    WHERE t.transaction_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY p.id
    ORDER BY total_sold DESC
    LIMIT 5
");
$top_products = [];
while ($row = $stmt->fetch_assoc()) {
    $top_products[] = $row;
}

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
                        <li class="breadcrumb-item active"><i class="fas fa-home"></i> Dashboard</li>
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
                            <h3><?php echo $today_transactions; ?></h3>
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
                            <h3>Rp <?php echo number_format($today_sales / 1000000, 1); ?><sup style="font-size: 20px">jt</sup></h3>
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
                            <h3><?php echo $low_stock; ?></h3>
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
                            <h3><?php echo $total_customers; ?></h3>
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
                            <h3 class="card-title">Produk Terlaris (7 Hari)</h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="products-list product-list-in-card pl-2 pr-2">
                                <?php if (empty($top_products)): ?>
                                    <li class="item">
                                        <div class="product-info">
                                            <span class="product-description text-center">
                                                Belum ada data penjualan
                                            </span>
                                        </div>
                                    </li>
                                <?php else: ?>
                                    <?php 
                                    $badge_colors = ['warning', 'info', 'danger', 'success', 'primary'];
                                    $index = 0;
                                    foreach ($top_products as $product): 
                                    ?>
                                        <li class="item">
                                            <div class="product-info">
                                                <a href="javascript:void(0)" class="product-title">
                                                    <?php echo $product['name']; ?>
                                                    <span class="badge badge-<?php echo $badge_colors[$index % 5]; ?> float-right">
                                                        Rp <?php echo number_format($product['price'], 0, ',', '.'); ?>
                                                    </span>
                                                </a>
                                                <span class="product-description">
                                                    Terjual: <?php echo $product['total_sold']; ?> unit
                                                </span>
                                            </div>
                                        </li>
                                    <?php 
                                    $index++;
                                    endforeach; 
                                    ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>