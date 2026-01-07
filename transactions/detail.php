<?php
require_once '../includes/auth.php';
requireLogin();

$page_title = "Detail Transaksi";
$page = 'riwayat';
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Transaksi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Riwayat Transaksi</a></li>
                        <li class="breadcrumb-item active">Detail Transaksi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="invoice p-3 mb-3">
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <i class="fas fa-shopping-cart"></i> TOKO MAKMUR
                                    <small class="float-right">Tanggal: 15/01/2024</small>
                                </h4>
                            </div>
                        </div>
                        
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                Dari
                                <address>
                                    <strong>TOKO MAKMUR</strong><br>
                                    Jl. Raya No. 123<br>
                                    Jakarta, 12345<br>
                                    Telp: (021) 123-4567<br>
                                    Email: info@tokomakmur.com
                                </address>
                            </div>
                            
                            <div class="col-sm-4 invoice-col">
                                Kepada
                                <address>
                                    <strong>Budi Santoso</strong><br>
                                    Jl. Merdeka No. 123<br>
                                    Jakarta, 12345<br>
                                    Telp: 08123456789<br>
                                    Email: budi@email.com
                                </address>
                            </div>
                            
                            <div class="col-sm-4 invoice-col">
                                <b>Invoice #TRX-20240115-001</b><br>
                                <br>
                                <b>Order ID:</b> 4F3S8J<br>
                                <b>Tanggal Transaksi:</b> 15/01/2024 14:30:45<br>
                                <b>Kasir:</b> Admin<br>
                                <b>Status:</b> <span class="badge badge-success">Selesai</span>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Produk</th>
                                            <th>Barcode</th>
                                            <th>Harga</th>
                                            <th>Qty</th>
                                            <th>Diskon</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Kopi Kapal Api 200gr</td>
                                            <td>899999900001</td>
                                            <td>Rp 15.000</td>
                                            <td>2</td>
                                            <td>Rp 0</td>
                                            <td>Rp 30.000</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Indomie Goreng</td>
                                            <td>899999900002</td>
                                            <td>Rp 3.500</td>
                                            <td>10</td>
                                            <td>Rp 5.000</td>
                                            <td>Rp 30.000</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Aqua 600ml</td>
                                            <td>899999900003</td>
                                            <td>Rp 3.000</td>
                                            <td>5</td>
                                            <td>Rp 0</td>
                                            <td>Rp 15.000</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <p class="lead">Metode Pembayaran:</p>
                                <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                                    <i class="fas fa-money-bill-wave"></i> Cash
                                </p>
                                
                                <p class="lead">Catatan:</p>
                                <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                                    Terima kasih telah berbelanja
                                </p>
                            </div>
                            
                            <div class="col-6">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th style="width:50%">Subtotal:</th>
                                            <td>Rp 200.000</td>
                                        </tr>
                                        <tr>
                                            <th>Diskon:</th>
                                            <td>Rp 15.000</td>
                                        </tr>
                                        <tr>
                                            <th>Pajak:</th>
                                            <td>Rp 0</td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td><strong>Rp 185.000</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Bayar (Cash):</th>
                                            <td>Rp 200.000</td>
                                        </tr>
                                        <tr>
                                            <th>Kembalian:</th>
                                            <td><strong class="text-success">Rp 15.000</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row no-print">
                            <div class="col-12">
                                <button type="button" class="btn btn-default" onclick="window.print()">
                                    <i class="fas fa-print"></i> Print
                                </button>
                                <button type="button" class="btn btn-success float-right">
                                    <i class="far fa-credit-card"></i> Submit Payment
                                </button>
                                <a href="index.php" class="btn btn-primary float-right" style="margin-right: 5px;">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function printInvoice() {
    window.print();
}
</script>

<?php include '../includes/footer.php'; ?>