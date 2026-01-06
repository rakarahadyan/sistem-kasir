<?php
require_once '../includes/auth.php';
requireLogin();

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
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
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
                            <form id="reportFilter">
                                <div class="form-group">
                                    <label>Jenis Laporan</label>
                                    <select class="form-control" id="reportType">
                                        <option value="sales">Penjualan</option>
                                        <option value="product">Produk</option>
                                        <option value="customer">Pelanggan</option>
                                        <option value="stock">Stok</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label>Periode</label>
                                    <select class="form-control" id="period">
                                        <option value="today">Hari Ini</option>
                                        <option value="yesterday">Kemarin</option>
                                        <option value="week">Minggu Ini</option>
                                        <option value="month">Bulan Ini</option>
                                        <option value="custom">Custom</option>
                                    </select>
                                </div>
                                
                                <div class="form-group" id="customDate" style="display: none;">
                                    <div class="row">
                                        <div class="col-6">
                                            <label>Dari</label>
                                            <input type="date" class="form-control" id="startDate">
                                        </div>
                                        <div class="col-6">
                                            <label>Sampai</label>
                                            <input type="date" class="form-control" id="endDate">
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="button" class="btn btn-primary btn-block" onclick="generateReport()">
                                    <i class="fas fa-chart-bar"></i> Generate Laporan
                                </button>
                                
                                <button type="button" class="btn btn-success btn-block mt-2" onclick="exportReport()">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </button>
                                
                                <button type="button" class="btn btn-danger btn-block mt-2" onclick="printReport()">
                                    <i class="fas fa-print"></i> Print
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Laporan Penjualan</h3>
                            <div class="card-tools">
                                <span class="badge badge-primary" id="reportTitle">Hari Ini (15 Jan 2024)</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fas fa-shopping-cart"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Transaksi</span>
                                            <span class="info-box-number">150</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 col-sm-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success"><i class="fas fa-money-bill-wave"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Penjualan</span>
                                            <span class="info-box-number">Rp 12.5jt</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 col-sm-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning"><i class="fas fa-box"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Produk Terjual</span>
                                            <span class="info-box-number">890</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 col-sm-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-danger"><i class="fas fa-users"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Pelanggan</span>
                                            <span class="info-box-number">45</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Top 5 Produk</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Produk</th>
                                                            <th>Terjual</th>
                                                            <th>Pendapatan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Kopi Kapal Api</td>
                                                            <td>125</td>
                                                            <td>Rp 1.875.000</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Indomie Goreng</td>
                                                            <td>98</td>
                                                            <td>Rp 343.000</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Aqua 600ml</td>
                                                            <td>87</td>
                                                            <td>Rp 261.000</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Roti Tawar</td>
                                                            <td>45</td>
                                                            <td>Rp 540.000</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Susu Ultra</td>
                                                            <td>32</td>
                                                            <td>Rp 192.000</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Top 5 Pelanggan</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Pelanggan</th>
                                                            <th>Transaksi</th>
                                                            <th>Total Belanja</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Budi Santoso</td>
                                                            <td>15</td>
                                                            <td>Rp 2.450.000</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Ahmad Fauzi</td>
                                                            <td>12</td>
                                                            <td>Rp 1.890.000</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Siti Rahayu</td>
                                                            <td>8</td>
                                                            <td>Rp 1.230.000</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Dewi Lestari</td>
                                                            <td>6</td>
                                                            <td>Rp 980.000</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Rudi Hartono</td>
                                                            <td>5</td>
                                                            <td>Rp 850.000</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Grafik Penjualan Harian</h3>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="dailySalesChart" height="100"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Show/hide custom date
    $('#period').change(function() {
        if ($(this).val() === 'custom') {
            $('#customDate').show();
        } else {
            $('#customDate').hide();
        }
    });
    
    // Initialize chart
    initChart();
});

function generateReport() {
    const reportType = $('#reportType').val();
    const period = $('#period').val();
    
    // Update report title
    let title = '';
    switch(period) {
        case 'today':
            title = 'Hari Ini (' + new Date().toLocaleDateString('id-ID') + ')';
            break;
        case 'yesterday':
            const yesterday = new Date();
            yesterday.setDate(yesterday.getDate() - 1);
            title = 'Kemarin (' + yesterday.toLocaleDateString('id-ID') + ')';
            break;
        case 'week':
            title = 'Minggu Ini';
            break;
        case 'month':
            title = 'Bulan Ini';
            break;
        case 'custom':
            const start = $('#startDate').val();
            const end = $('#endDate').val();
            title = start + ' s/d ' + end;
            break;
    }
    
    $('#reportTitle').text(title);
    alert('Laporan ' + reportType + ' untuk periode ' + title + ' berhasil digenerate!');
}

function exportReport() {
    alert('Fitur export ke Excel akan segera tersedia!');
    // Implementasi export Excel bisa menggunakan SheetJS atau library lain
}

function printReport() {
    window.print();
}

function initChart() {
    const ctx = document.getElementById('dailySalesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['8-10', '10-12', '12-14', '14-16', '16-18', '18-20'],
            datasets: [{
                label: 'Jumlah Transaksi',
                data: [12, 19, 25, 32, 28, 18],
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }, {
                label: 'Pendapatan (Rp ribu)',
                data: [120, 190, 250, 320, 280, 180],
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                type: 'line',
                yAxisID: 'y1'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Transaksi'
                    }
                },
                y1: {
                    position: 'right',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Pendapatan (Rp ribu)'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
}
</script>

<?php include '../includes/footer.php'; ?>