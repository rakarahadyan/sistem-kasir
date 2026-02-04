<?php
require_once '../api/auth.php';
requireLogin();

require_once '../config/database.php';
$db = Database::getInstance()->getConnection();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = "SELECT t.*, u.name as cashier_name, c.name as customer_name
          FROM transactions t
          LEFT JOIN users u ON t.user_id = u.id
          LEFT JOIN customers c ON t.customer_id = c.id
          WHERE t.id = $id";
$result = $db->query($query);
$transaction = $result->fetch_assoc();

if (!$transaction) {
    echo "Transaksi tidak ditemukan";
    exit;
}

$query_items = "SELECT ti.*, p.barcode as product_barcode
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
<!DOCTYPE html>
<html>
<head>
    <title>Struk Transaksi - <?php echo $transaction['transaction_code']; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            padding: 10px;
            width: 300px;
            margin: 0 auto;
        }
        .receipt {
            width: 100%;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .separator {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .separator-double {
            border-top: 2px solid #000;
            margin: 10px 0;
        }
        h2 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 3px 0;
            vertical-align: top;
        }
        .item-name {
            width: 50%;
        }
        .item-qty {
            width: 25%;
            text-align: right;
        }
        .item-total {
            width: 25%;
            text-align: right;
        }
        .summary td {
            padding: 2px 0;
        }
        .total-row {
            font-weight: bold;
            font-size: 13px;
        }
        @media print {
            body {
                padding: 0;
            }
            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt">
        <h2 class="text-center">TOKO MAKMUR</h2>
        <p class="text-center">Jl. Raya No. 123<br>Telp: (021) 123-4567</p>
        
        <div class="separator"></div>
        
        <table>
            <tr>
                <td>No. Transaksi</td>
                <td class="text-right"><?php echo $transaction['transaction_code']; ?></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td class="text-right"><?php echo date('d/m/Y H:i', strtotime($transaction['created_at'])); ?></td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td class="text-right"><?php echo $transaction['cashier_name']; ?></td>
            </tr>
            <?php if ($transaction['customer_name']): ?>
            <tr>
                <td>Pelanggan</td>
                <td class="text-right"><?php echo $transaction['customer_name']; ?></td>
            </tr>
            <?php endif; ?>
        </table>
        
        <div class="separator"></div>
        
        <table>
            <?php foreach ($items as $item): ?>
            <tr>
                <td colspan="3" class="item-name"><?php echo $item['name']; ?></td>
            </tr>
            <tr>
                <td class="item-name">  <?php echo number_format($item['price'], 0, ',', '.'); ?> x <?php echo $item['qty']; ?></td>
                <td class="item-qty">
                    <?php if ($item['discount'] > 0): ?>
                    -<?php echo number_format($item['discount'], 0, ',', '.'); ?>
                    <?php endif; ?>
                </td>
                <td class="item-total"><?php echo number_format($item['total'], 0, ',', '.'); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <div class="separator"></div>
        
        <table class="summary">
            <tr>
                <td>Subtotal</td>
                <td class="text-right"><?php echo number_format($transaction['total'], 0, ',', '.'); ?></td>
            </tr>
            <?php if ($transaction['discount'] > 0): ?>
            <tr>
                <td>Diskon</td>
                <td class="text-right">-<?php echo number_format($transaction['discount'], 0, ',', '.'); ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($transaction['tax'] > 0): ?>
            <tr>
                <td>Pajak</td>
                <td class="text-right"><?php echo number_format($transaction['tax'], 0, ',', '.'); ?></td>
            </tr>
            <?php endif; ?>
        </table>
        
        <div class="separator-double"></div>
        
        <table class="summary total-row">
            <tr>
                <td>TOTAL</td>
                <td class="text-right">Rp <?php echo number_format($transaction['total'] - ($transaction['discount'] + $transaction['tax']), 0, ',', '.'); ?></td>
            </tr>
        </table>
        
        <?php if ($transaction['cash_paid']): ?>
        <div class="separator"></div>
        <table class="summary">
            <tr>
                <td>Bayar (<?php echo ucfirst($transaction['payment_method']); ?>)</td>
                <td class="text-right"><?php echo number_format($transaction['cash_paid'], 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td>Kembalian</td>
                <td class="text-right"><?php echo number_format($transaction['change_amount'], 0, ',', '.'); ?></td>
            </tr>
        </table>
        <?php endif; ?>
        
        <div class="separator"></div>
        
        <p class="text-center">Terima kasih atas kunjungan Anda</p>
        <p class="text-center">*** STRUK INI ASLI ***</p>
        
        <div class="separator"></div>
        
        <p class="text-center" style="font-size: 10px;">Dicetak: <?php echo date('d/m/Y H:i:s'); ?></p>
    </div>
</body>
</html>
