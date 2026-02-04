<?php
require_once '../api/auth.php';
requireLogin();
require_once '../config/database.php';

$db = Database::getInstance()->getConnection();

// Filter parameters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-d', strtotime('-7 days'));
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d');
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Query transaksi
$sql = "SELECT 
            t.id,
            t.transaction_code,
            t.transaction_date,
            t.total,
            u.name as cashier_name,
            c.name as customer_name,
            COUNT(ti.id) as total_items
        FROM transactions t
        LEFT JOIN users u ON t.user_id = u.id
        LEFT JOIN customers c ON t.customer_id = c.id
        LEFT JOIN transaction_items ti ON t.id = ti.transaction_id
        WHERE DATE(t.transaction_date) BETWEEN ? AND ?";

$params = [$date_from, $date_to];
$types = "ss";

if (!empty($search)) {
    $sql .= " AND (t.transaction_code LIKE ? OR c.name LIKE ? OR u.name LIKE ?)";
    $searchParam = "%{$search}%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= "sss";
}

$sql .= " GROUP BY t.id ORDER BY t.transaction_date DESC";

$stmt = $db->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

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
                                <a href="create.php" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Transaksi Baru
                                </a>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <form method="GET" action="" class="mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Cari</label>
                                            <input type="text" name="search" class="form-control" placeholder="Kode transaksi, pelanggan..." value="<?php echo $search; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tanggal Mulai</label>
                                            <input type="date" class="form-control" name="date_from" value="<?php echo $date_from; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tanggal Akhir</label>
                                            <input type="date" class="form-control" name="date_to" value="<?php echo $date_to; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary mt-4">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                        <a href="index.php" class="btn btn-secondary mt-4">
                                            <i class="fas fa-redo"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </form>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Kode Transaksi</th>
                                            <th>Tanggal</th>
                                            <th>Pelanggan</th>
                                            <th>Kasir</th>
                                            <th>Items</th>
                                            <th>Total</th>
                                            <th width="150">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($transactions)): ?>
                                            <tr>
                                                <td colspan="8" class="text-center">Tidak ada data transaksi</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php $no = 1; foreach ($transactions as $trx): ?>
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td><span class="badge badge-light"><?php echo $trx['transaction_code']; ?></span></td>
                                                    <td><?php echo date('d/m/Y H:i', strtotime($trx['transaction_date'])); ?></td>
                                                    <td><?php echo $trx['customer_name'] ? $trx['customer_name'] : 'Umum'; ?></td>
                                                    <td><?php echo $trx['cashier_name']; ?></td>
                                                    <td><?php echo $trx['total_items']; ?> items</td>
                                                    <td><strong>Rp <?php echo number_format($trx['total'], 0, ',', '.'); ?></strong></td>
                                                    <td>
                                                        <a href="detail.php?id=<?php echo $trx['id']; ?>" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="print.php?id=<?php echo $trx['id']; ?>" target="_blank" class="btn btn-sm btn-secondary">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
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