<?php
require_once '../api/auth.php';
requireLogin();

$page_title = "Detail Transaksi";
$page = 'riwayat';

require_once '../config/database.php';
$db = Database::getInstance()->getConnection();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = "SELECT t.*, u.name as cashier_name, c.name as customer_name, c.phone as customer_phone, py.method as payment_method
          FROM transactions t
          LEFT JOIN users u ON t.user_id = u.id
          LEFT JOIN customers c ON t.customer_id = c.id
          LEFT JOIN payments py ON t.id = py.transaction_id
          WHERE t.id = $id";
$result = $db->query($query);
$transaction = $result->fetch_assoc();

if (!$transaction) {
    header('Location: index.php');
    exit;
}

$query_items = "SELECT ti.*, p.barcode as product_barcode, p.name as product_name
                FROM transaction_items ti
                LEFT JOIN products p ON ti.product_id = p.id
                WHERE ti.transaction_id = $id
                ORDER BY ti.id";
$result_items = $db->query($query_items);
$items = [];
while ($row = $result_items->fetch_assoc()) {
    $items[] = $row;
}
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
                                    <small class="float-right">Tanggal: <?php echo date('d/m/Y', strtotime($transaction['created_at'])); ?></small>
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
                                    <strong><?php echo $transaction['customer_name'] ?: 'Umum'; ?></strong><br>
                                    <?php if ($transaction['customer_phone']): ?>
                                    Telp: <?php echo $transaction['customer_phone']; ?><br>
                                    <?php endif; ?>
                                </address>
                            </div>
                            
                            <div class="col-sm-4 invoice-col">
                                <b>Invoice #<?php echo $transaction['invoice']; ?></b><br>
                                <br>
                                <b>Tanggal Transaksi:</b> <?php echo date('d/m/Y H:i:s', strtotime($transaction['created_at'])); ?><br>
                                <b>Kasir:</b> <?php echo $transaction['cashier_name']; ?><br>
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
                                        <?php 
                                        $no = 1;
                                        foreach ($items as $item): 
                                        ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $item['product_name']; ?></td>
                                            <td><?php echo $item['barcode'] ?: $item['product_barcode']; ?></td>
                                            <td>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                                            <td><?php echo $item['qty']; ?></td>
                                            <td>Rp <?php echo number_format($item['discount'], 0, ',', '.'); ?></td>
                                            <td>Rp <?php echo number_format($item['total'], 0, ',', '.'); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <p class="lead">Metode Pembayaran:</p>
                                <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                                    <i class="fas fa-money-bill-wave"></i> <?php echo ucfirst($transaction['payment_method']); ?>
                                </p>
                            </div>
                            
                            <div class="col-6">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th style="width:50%">Subtotal:</th>
                                            <td>Rp <?php echo number_format($transaction['total'], 0, ',', '.'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Diskon:</th>
                                            <td>Rp <?php echo number_format($transaction['discount'], 0, ',', '.'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Pajak:</th>
                                            <td>Rp <?php echo number_format($transaction['tax'], 0, ',', '.'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td><strong>Rp <?php echo number_format($transaction['total'] - ($transaction['tax'] + $transaction['discount']), 0, ',', '.'); ?></strong></td>
                                        </tr>
                                        <?php if ($transaction['cash_paid']): ?>
                                        <tr>
                                            <th>Bayar (<?php echo ucfirst($transaction['payment_method']); ?>):</th>
                                            <td>Rp <?php echo number_format($transaction['cash_paid'], 0, ',', '.'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Kembalian:</th>
                                            <td><strong class="text-success">Rp <?php echo number_format($transaction['change_amount'], 0, ',', '.'); ?></strong></td>
                                        </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row no-print">
                            <div class="col-12">
                                <a href="print.php?id=<?php echo $transaction['id']; ?>" target="_blank" class="btn btn-default">
                                    <i class="fas fa-print"></i> Print Struk
                                </a>
                                <a href="index.php" class="btn btn-primary float-right">
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

<?php include '../includes/footer.php'; ?>