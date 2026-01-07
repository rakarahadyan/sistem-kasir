<?php
require_once '../includes/auth.php';
requireLogin();

$page_title = "Riwayat Transaksi";
$page = 'riwayat';
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Riwayat Transaksi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active">Riwayat Transaksi</li>
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
                            <h3 class="card-title">Daftar Transaksi</h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 250px;">
                                    <input type="text" name="table_search" class="form-control float-right" placeholder="Cari transaksi...">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tanggal Mulai</label>
                                        <input type="date" class="form-control" id="startDate" value="<?php echo date('Y-m-d', strtotime('-7 days')); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tanggal Akhir</label>
                                        <input type="date" class="form-control" id="endDate" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" id="statusFilter">
                                            <option value="">Semua</option>
                                            <option value="success">Selesai</option>
                                            <option value="pending">Pending</option>
                                            <option value="canceled">Dibatalkan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-primary mt-4" onclick="filterTransactions()">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <button class="btn btn-secondary mt-4" onclick="resetFilter()">
                                        <i class="fas fa-redo"></i> Reset
                                    </button>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Kode Transaksi</th>
                                            <th>Tanggal</th>
                                            <th>Pelanggan</th>
                                            <th>Kasir</th>
                                            <th>Items</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td><span class="badge badge-light">TRX-20240115-001</span></td>
                                            <td>2024-01-15 14:30</td>
                                            <td>Budi Santoso</td>
                                            <td>Admin</td>
                                            <td>5 items</td>
                                            <td>Rp 185.000</td>
                                            <td><span class="badge badge-success">Selesai</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-info" onclick="viewTransaction(1)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button class="btn btn-sm btn-secondary" onclick="printReceipt(1)">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td><span class="badge badge-light">TRX-20240114-002</span></td>
                                            <td>2024-01-14 10:15</td>
                                            <td>Siti Rahayu</td>
                                            <td>Kasir</td>
                                            <td>3 items</td>
                                            <td>Rp 92.500</td>
                                            <td><span class="badge badge-success">Selesai</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button class="btn btn-sm btn-secondary">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td><span class="badge badge-light">TRX-20240113-003</span></td>
                                            <td>2024-01-13 16:45</td>
                                            <td>Umum</td>
                                            <td>Admin</td>
                                            <td>2 items</td>
                                            <td>Rp 45.000</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button class="btn btn-sm btn-secondary">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" class="text-right">Total:</th>
                                            <th colspan="3">Rp 322.500</th>
                                        </tr>
                                    </tfoot>
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

<!-- Modal Detail Transaksi -->
<div class="modal fade" id="transactionDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Kode Transaksi</th>
                                <td>: TRX-20240115-001</td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td>: 2024-01-15 14:30:45</td>
                            </tr>
                            <tr>
                                <th>Pelanggan</th>
                                <td>: Budi Santoso</td>
                            </tr>
                            <tr>
                                <th>Kasir</th>
                                <td>: Admin</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Subtotal</th>
                                <td>: Rp 200.000</td>
                            </tr>
                            <tr>
                                <th>Diskon</th>
                                <td>: Rp 15.000</td>
                            </tr>
                            <tr>
                                <th>Pajak</th>
                                <td>: Rp 0</td>
                            </tr>
                            <tr>
                                <th><strong>Total</strong></th>
                                <td>: <strong>Rp 185.000</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <h6>Detail Items</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Diskon</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Kopi Kapal Api 200gr</td>
                                <td>Rp 15.000</td>
                                <td>2</td>
                                <td>Rp 0</td>
                                <td>Rp 30.000</td>
                            </tr>
                            <tr>
                                <td>Indomie Goreng</td>
                                <td>Rp 3.500</td>
                                <td>10</td>
                                <td>Rp 5.000</td>
                                <td>Rp 30.000</td>
                            </tr>
                            <tr>
                                <td>Aqua 600ml</td>
                                <td>Rp 3.000</td>
                                <td>5</td>
                                <td>Rp 0</td>
                                <td>Rp 15.000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <h6>Pembayaran</h6>
                <table class="table table-sm table-borderless">
                    <tr>
                        <th width="40%">Metode</th>
                        <td>: Cash</td>
                    </tr>
                    <tr>
                        <th>Bayar</th>
                        <td>: Rp 200.000</td>
                    </tr>
                    <tr>
                        <th>Kembalian</th>
                        <td>: Rp 15.000</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="printReceipt()">
                    <i class="fas fa-print"></i> Cetak Ulang
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function viewTransaction(id) {
    $('#transactionDetailModal').modal('show');
}

function filterTransactions() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const status = document.getElementById('statusFilter').value;
    
    // Simulasi filter
    alert(`Filter: ${startDate} s/d ${endDate} - Status: ${status || 'Semua'}`);
}

function resetFilter() {
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    document.getElementById('statusFilter').value = '';
}

function printReceipt(id) {
    // Simulasi cetak struk
    const receiptWindow = window.open('', '_blank');
    receiptWindow.document.write(`
        <html>
        <head>
            <title>Struk Transaksi</title>
            <style>
                body { font-family: monospace; padding: 10px; }
                .receipt { width: 300px; }
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                .separator { border-top: 1px dashed #000; margin: 10px 0; }
                table { width: 100%; }
                td { padding: 3px 0; }
            </style>
        </head>
        <body>
            <div class="receipt">
                <h3 class="text-center">TOKO MAKMUR</h3>
                <p class="text-center">Jl. Raya No. 123<br>Telp: 021-1234567</p>
                <div class="separator"></div>
                <p>TRX-20240115-001<br>15/01/2024 14:30:45<br>Kasir: Admin</p>
                <div class="separator"></div>
                <table>
                    <tr>
                        <td>Kopi Kapal Api</td>
                        <td class="text-right">2 x 15.000</td>
                        <td class="text-right">30.000</td>
                    </tr>
                    <tr>
                        <td>Indomie Goreng</td>
                        <td class="text-right">10 x 3.500</td>
                        <td class="text-right">30.000</td>
                    </tr>
                    <tr>
                        <td>Aqua 600ml</td>
                        <td class="text-right">5 x 3.000</td>
                        <td class="text-right">15.000</td>
                    </tr>
                </table>
                <div class="separator"></div>
                <table>
                    <tr>
                        <td>Subtotal</td>
                        <td class="text-right">200.000</td>
                    </tr>
                    <tr>
                        <td>Diskon</td>
                        <td class="text-right">-15.000</td>
                    </tr>
                    <tr>
                        <td><strong>TOTAL</strong></td>
                        <td class="text-right"><strong>185.000</strong></td>
                    </tr>
                </table>
                <div class="separator"></div>
                <table>
                    <tr>
                        <td>Bayar (Cash)</td>
                        <td class="text-right">200.000</td>
                    </tr>
                    <tr>
                        <td>Kembalian</td>
                        <td class="text-right">15.000</td>
                    </tr>
                </table>
                <div class="separator"></div>
                <p class="text-center">Terima kasih atas kunjungan Anda</p>
                <p class="text-center">*** STRUK INI ASLI ***</p>
            </div>
        </body>
        </html>
    `);
    receiptWindow.document.close();
    receiptWindow.print();
}
</script>

<?php include '../includes/footer.php'; ?>