<?php
require_once '../api/auth.php';
requireLogin();
require_once '../api/reports.php';

// Default periode
$report_type = isset($_GET['type']) ? $_GET['type'] : 'sales';
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-01');
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d');

// Ambil data berdasarkan tipe laporan
if ($report_type == 'sales') {
    $report = getSalesReport($date_from, $date_to);
    $transactions = $report['transactions'];
    $summary = $report['summary'];
} 

if ($report_type == 'product') {
    $report = getProductReport($date_from, $date_to);
    $products = $report['products'];
    $summary = $report['summary'];
} 
if ($report_type == 'stock') {
    $report = getStockReport();
    $stocks = $report['products'];
    $summary = $report['summary'];
}

$page_title = "Laporan";
$page = 'laporan';
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active">Laporan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Filter Periode</h3>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="">
                                <div class="form-group">
                                    <label>Jenis Laporan</label>
                                    <select class="form-control" name="type">
                                        <option value="sales" <?php echo ($report_type == 'sales') ? 'selected' : ''; ?>>Penjualan</option>
                                        <option value="product" <?php echo ($report_type == 'product') ? 'selected' : ''; ?>>Produk</option>
                                        <option value="stock" <?php echo ($report_type == 'stock') ? 'selected' : ''; ?>>Stok</option>
                                    </select>
                                </div>
                                
                                <?php if ($report_type != 'stock'): ?>
                                <div class="form-group">
                                    <label>Dari Tanggal</label>
                                    <input type="date" class="form-control" name="date_from" value="<?php echo $date_from; ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Sampai Tanggal</label>
                                    <input type="date" class="form-control" name="date_to" value="<?php echo $date_to; ?>" required>
                                </div>
                                <?php endif; ?>
                                
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-chart-bar"></i> Generate Laporan
                                </button>

                                <button type="button" class="btn btn-danger btn-block" onclick="exportToPDF()">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                                
                                <button type="button" class="btn btn-success btn-block" onclick="exportToExcel()">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </button>
                                
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-9">
                    <?php if ($report_type == 'sales'): ?>
                        
                        <!-- Laporan Penjualan -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Laporan Penjualan</h3>
                                <div class="card-tools">
                                    <span class="badge badge-primary">
                                        <?php echo date('d M Y', strtotime($date_from)); ?> - <?php echo date('d M Y', strtotime($date_to)); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <div class="info-box bg-info">
                                            <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Total Transaksi</span>
                                                <span class="info-box-number"><?php echo $summary['total_transactions']; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-success">
                                            <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Total Penjualan</span>
                                                <span class="info-box-number">Rp <?php echo number_format($summary['total_sales'], 0, ',', '.'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-warning">
                                            <span class="info-box-icon"><i class="fas fa-percent"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Total Diskon</span>
                                                <span class="info-box-number">Rp <?php echo number_format($summary['total_discount'], 0, ',', '.'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-danger">
                                            <span class="info-box-icon"><i class="fas fa-receipt"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Total Pajak</span>
                                                <span class="info-box-number">Rp <?php echo number_format($summary['total_tax'], 0, ',', '.'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table id="salesTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Transaksi</th>
                                                <th>Tanggal</th>
                                                <th>Kasir</th>
                                                <th>Pelanggan</th>
                                                <th>Subtotal</th>
                                                <th>Diskon</th>
                                                <th>Pajak</th>
                                                <th>Total</th>
                                                <th>Metode</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($transactions)): ?>
                                                <tr>
                                                    <td colspan="10" class="text-center">Tidak ada data transaksi</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php $no = 1; foreach ($transactions as $trx): ?>
                                                    <tr>
                                                        <td><?php echo $no++; ?></td>
                                                        <td><?php echo $trx['transaction_code']; ?></td>
                                                        <td><?php echo date('d/m/Y H:i', strtotime($trx['transaction_date'])); ?></td>
                                                        <td><?php echo $trx['cashier_name']; ?></td>
                                                        <td><?php echo $trx['customer_name'] ? $trx['customer_name'] : '-'; ?></td>
                                                        <td>Rp <?php echo number_format($trx['subtotal'], 0, ',', '.'); ?></td>
                                                        <td>Rp <?php echo number_format($trx['discount'], 0, ',', '.'); ?></td>
                                                        <td>Rp <?php echo number_format($trx['tax'], 0, ',', '.'); ?></td>
                                                        <td><strong>Rp <?php echo number_format($trx['total'], 0, ',', '.'); ?></strong></td>
                                                        <td><?php echo $trx['payment_method']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                    <!-- Chart Penjualan -->
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-chart-line"></i> Grafik Penjualan Harian</h3>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="salesChart" style="height: 250px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    <?php elseif ($report_type == 'product'): ?>
                        
                        <!-- Laporan Produk -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Laporan Produk</h3>
                                <div class="card-tools">
                                    <span class="badge badge-primary">
                                        <?php echo date('d M Y', strtotime($date_from)); ?> - <?php echo date('d M Y', strtotime($date_to)); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <div class="info-box bg-info">
                                            <span class="info-box-icon"><i class="fas fa-box"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Total Terjual</span>
                                                <span class="info-box-number"><?php echo number_format($summary['total_sold'], 0, ',', '.'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-success">
                                            <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Total Pendapatan</span>
                                                <span class="info-box-number">Rp <?php echo number_format($summary['total_revenue'], 0, ',', '.'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-warning">
                                            <span class="info-box-icon"><i class="fas fa-coins"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Total Modal</span>
                                                <span class="info-box-number">Rp <?php echo number_format($summary['total_cost'], 0, ',', '.'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-danger">
                                            <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Total Profit</span>
                                                <span class="info-box-number">Rp <?php echo number_format($summary['total_profit'], 0, ',', '.'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table id="productTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Barcode</th>
                                                <th>Nama Produk</th>
                                                <th>Kategori</th>
                                                <th>Harga Jual</th>
                                                <th>Harga Modal</th>
                                                <th>Stok</th>
                                                <th>Terjual</th>
                                                <th>Pendapatan</th>
                                                <th>Profit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($products)): ?>
                                                <tr>
                                                    <td colspan="10" class="text-center">Tidak ada data produk</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php $no = 1; foreach ($products as $prod): ?>
                                                    <tr>
                                                        <td><?php echo $no++; ?></td>
                                                        <td><?php echo $prod['barcode']; ?></td>
                                                        <td><?php echo $prod['name']; ?></td>
                                                        <td><?php echo $prod['category_name']; ?></td>
                                                        <td>Rp <?php echo number_format($prod['price'], 0, ',', '.'); ?></td>
                                                        <td>Rp <?php echo number_format($prod['cost'], 0, ',', '.'); ?></td>
                                                        <td><?php echo $prod['stock']; ?></td>
                                                        <td><?php echo number_format($prod['total_sold'], 0, ',', '.'); ?></td>
                                                        <td>Rp <?php echo number_format($prod['total_revenue'], 0, ',', '.'); ?></td>
                                                        <td><strong>Rp <?php echo number_format($prod['total_profit'], 0, ',', '.'); ?></strong></td>
                                                 </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                    <!-- Chart Produk Terlaris -->
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-chart-bar"></i> Top 10 Produk Terlaris</h3>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="productChart" style="height: 300px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    <?php elseif ($report_type == 'stock'): ?>
                        <!-- Laporan Stok -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Laporan Stok Produk</h3>
                                <div class="card-tools">
                                    <span class="badge badge-primary">Per <?php echo date('d M Y'); ?></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="info-box bg-info">
                                            <span class="info-box-icon"><i class="fas fa-box"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Total Item</span>
                                                <span class="info-box-number"><?php echo $summary['total_items']; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-box bg-danger">
                                            <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Stok Menipis</span>
                                                <span class="info-box-number"><?php echo $summary['low_stock']; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-box bg-success">
                                            <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Nilai Stok</span>
                                                <span class="info-box-number">Rp <?php echo number_format($summary['total_value'], 0, ',', '.'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table id="stockTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Barcode</th>
                                                <th>Nama Produk</th>
                                                <th>Kategori</th>
                                                <th>Stok</th>
                                                <th>Harga Beli</th>
                                                <th>Harga Jual</th>
                                                <th>Nilai Stok</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($stocks)): ?>
                                                <tr>
                                                    <td colspan="9" class="text-center">Tidak ada data stok</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php $no = 1; foreach ($stocks as $stock): ?>
                                                    <tr>
                                                        <td><?php echo $no++; ?></td>
                                                        <td><?php echo $stock['barcode']; ?></td>
                                                        <td><?php echo $stock['name']; ?></td>
                                                        <td><?php echo $stock['category_name']; ?></td>
                                                        <td>
                                                            <?php
                                                            $stock_class = '';
                                                            if ($stock['stock'] == 0) $stock_class = 'text-danger font-weight-bold';
                                                            elseif ($stock['stock'] <= 10) $stock_class = 'text-warning font-weight-bold';
                                                            ?>
                                                            <span class="<?php echo $stock_class; ?>"><?php echo $stock['stock']; ?></span>
                                                        </td>
                                                        <td>Rp <?php echo number_format($stock['cost'], 0, ',', '.'); ?></td>
                                                        <td>Rp <?php echo number_format($stock['price'], 0, ',', '.'); ?></td>
                                                        <td>Rp <?php echo number_format($stock['stock_value'], 0, ',', '.'); ?></td>
                                                        <td>
                                                            <?php
                                                            if ($stock['stock'] == 0) {
                                                                echo '<span class="badge badge-danger">Habis</span>';
                                                            } elseif ($stock['stock'] <= 10) {
                                                                echo '<span class="badge badge-warning">Stok Menipis</span>';
                                                            } else {
                                                                echo '<span class="badge badge-success">Aman</span>';
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-chart-pie"></i> Status Stok Produk</h3>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="stockChart" style="height: 300px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
<?php if ($report_type == 'sales' && !empty($transactions)): ?>
// Chart Penjualan per Hari
var salesData = {};
<?php foreach ($transactions as $trx): ?>
    var date = '<?php echo date('d/m', strtotime($trx['transaction_date'])); ?>';
    if (!salesData[date]) {
        salesData[date] = 0;
    }
    salesData[date] += <?php echo $trx['total']; ?>;
<?php endforeach; ?>

const salesCtx = document.getElementById('salesChart').getContext('2d');
new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: Object.keys(salesData),
        datasets: [{
            label: 'Total Penjualan (Rp)',
            data: Object.values(salesData),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
<?php endif; ?>

<?php if ($report_type == 'product' && !empty($products)): ?>
// Chart Top 10 Produk Terlaris
const topProducts = <?php echo json_encode(array_slice($products, 0, 10)); ?>;
const productNames = topProducts.map(p => p.name.length > 20 ? p.name.substring(0, 20) + '...' : p.name);
const productSold = topProducts.map(p => parseInt(p.total_sold));

const productCtx = document.getElementById('productChart').getContext('2d');
new Chart(productCtx, {
    type: 'bar',
    data: {
        labels: productNames,
        datasets: [{
            label: 'Jumlah Terjual',
            data: productSold,
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)',
                'rgba(255, 159, 64, 0.7)',
                'rgba(199, 199, 199, 0.7)',
                'rgba(83, 102, 255, 0.7)',
                'rgba(255, 99, 255, 0.7)',
                'rgba(99, 255, 132, 0.7)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(199, 199, 199, 1)',
                'rgba(83, 102, 255, 1)',
                'rgba(255, 99, 255, 1)',
                'rgba(99, 255, 132, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
<?php endif; ?>

<?php if ($report_type == 'stock' && !empty($stocks)): ?>
// Chart Status Stok
let habis = 0;
let menipis = 0;
let aman = 0;

<?php foreach ($stocks as $stock): ?>
    <?php if ($stock['stock'] == 0): ?>
        habis++;
    <?php elseif ($stock['stock'] <= 10): ?>
        menipis++;
    <?php else: ?>
        aman++;
    <?php endif; ?>
<?php endforeach; ?>

const stockCtx = document.getElementById('stockChart').getContext('2d');
new Chart(stockCtx, {
    type: 'doughnut',
    data: {
        labels: ['Habis', 'Stok Menipis', 'Aman'],
        datasets: [{
            data: [habis, menipis, aman],
            backgroundColor: [
                'rgba(220, 53, 69, 0.7)',
                'rgba(255, 193, 7, 0.7)',
                'rgba(40, 167, 69, 0.7)'
            ],
            borderColor: [
                'rgba(220, 53, 69, 1)',
                'rgba(255, 193, 7, 1)',
                'rgba(40, 167, 69, 1)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed || 0;
                        const total = habis + menipis + aman;
                        const percentage = ((value / total) * 100).toFixed(1);
                        return label + ': ' + value + ' produk (' + percentage + '%)';
                    }
                }
            }
        }
    }
});
<?php endif; ?>

// ========== EXPORT FUNCTIONS ==========

// Export to Excel (CSV format)
function exportToExcel() {
    const reportType = '<?php echo $report_type; ?>';
    let table = null;
    let filename = '';
    
    // Get the appropriate table based on report type
    if (reportType === 'sales') {
        table = document.getElementById('salesTable');
        filename = 'Laporan_Penjualan_<?php echo date("Ymd", strtotime($date_from)); ?>_<?php echo date("Ymd", strtotime($date_to)); ?>.csv';
    } else if (reportType === 'product') {
        table = document.getElementById('productTable');
        filename = 'Laporan_Produk_<?php echo date("Ymd", strtotime($date_from)); ?>_<?php echo date("Ymd", strtotime($date_to)); ?>.csv';
    } else if (reportType === 'stock') {
        table = document.getElementById('stockTable');
        filename = 'Laporan_Stok_<?php echo date("Ymd"); ?>.csv';
    }
    
    if (!table) {
        alert('Tidak ada data untuk di-export');
        return;
    }
    
    // Convert table to CSV
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = [];
        const cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ');
            data = data.replace(/"/g, '""');
            row.push('"' + data + '"');
        }
        
        csv.push(row.join(','));
    }
    
    // Download CSV file
    const csvString = csv.join('\n');
    const blob = new Blob(['\ufeff' + csvString], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    
    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

// Export to PDF
function exportToPDF() {
    const reportType = '<?php echo $report_type; ?>';
    
    // Create a printable version
    const printWindow = window.open('', '', 'height=600,width=800');
    
    let title = '';
    let period = '';
    let tableHTML = '';
    
    if (reportType === 'sales') {
        title = 'LAPORAN PENJUALAN';
        period = 'Periode: <?php echo date("d/m/Y", strtotime($date_from)); ?> - <?php echo date("d/m/Y", strtotime($date_to)); ?>';
        tableHTML = document.getElementById('salesTable').outerHTML;
    } else if (reportType === 'product') {
        title = 'LAPORAN PRODUK';
        period = 'Periode: <?php echo date("d/m/Y", strtotime($date_from)); ?>- <?php echo date("d/m/Y", strtotime($date_to)); ?>';
        tableHTML = document.getElementById('productTable').outerHTML;
    } else if (reportType === 'stock') {
        title = 'LAPORAN STOK';
        period = 'Per Tanggal: <?php echo date("d/m/Y"); ?>';
        tableHTML = document.getElementById('stockTable').outerHTML;
    }
    
    printWindow.document.write('<html><head><title>' + title + '</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('body { font-family: Arial, sans-serif; margin: 20px; }');
    printWindow.document.write('h1 { text-align: center; margin-bottom: 5px; }');
    printWindow.document.write('.period { text-align: center; margin-bottom: 20px; font-size: 14px; }');
    printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
    printWindow.document.write('th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }');
    printWindow.document.write('th { background-color: #f2f2f2; font-weight: bold; }');
    printWindow.document.write('.text-center { text-align: center; }');
    printWindow.document.write('.text-right { text-align: right; }');
    printWindow.document.write('.badge { display: none; }');
    printWindow.document.write('@media print { button { display: none; } }');
    printWindow.document.write('</style></head><body>');
    printWindow.document.write('<h1>' + title + '</h1>');
    printWindow.document.write('<div class="period">' + period + '</div>');
    printWindow.document.write(tableHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    
    // Wait for content to load then print
    printWindow.onload = function() {
        printWindow.print();
        setTimeout(function() {
            printWindow.close();
        }, 250);
    };
}
</script>

<?php include '../includes/footer.php'; ?>