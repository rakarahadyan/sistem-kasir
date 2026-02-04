<?php
require_once __DIR__ . '/../config/database.php';

function getSalesReport($date_from = null, $date_to = null) {
    $db = Database::getInstance()->getConnection();
    
    if (!$date_from) $date_from = date('Y-m-01');
    if (!$date_to) $date_to = date('Y-m-d');
    
    $date_from = $db->real_escape_string($date_from);
    $date_to = $db->real_escape_string($date_to);
    
    $query = "SELECT 
            t.id,
            t.transaction_code,
            t.transaction_date,
            t.subtotal,
            t.discount,
            t.tax,
            t.total,
            p.method as payment_method,
            u.name as cashier_name,
            c.name as customer_name
        FROM transactions t
        LEFT JOIN users u ON t.user_id = u.id
        LEFT JOIN customers c ON t.customer_id = c.id
        LEFT JOIN payments p ON t.id = p.transaction_id
        WHERE DATE(t.transaction_date) BETWEEN '$date_from' AND '$date_to'
        ORDER BY t.transaction_date DESC";
    
    $result = $db->query($query);
    
    $transactions = [];
    $summary = [
        'total_transactions' => 0,
        'total_subtotal' => 0,
        'total_discount' => 0,
        'total_tax' => 0,
        'total_sales' => 0
    ];
    
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
        $summary['total_transactions']++;
        $summary['total_subtotal'] += $row['subtotal'];
        $summary['total_discount'] += $row['discount'];
        $summary['total_tax'] += $row['tax'];
        $summary['total_sales'] += $row['total'];
    }
    
    return ['transactions' => $transactions, 'summary' => $summary];
}

function getProductReport($date_from = null, $date_to = null) {
    $db = Database::getInstance()->getConnection();
    
    if (!$date_from) $date_from = date('Y-m-01');
    if (!$date_to) $date_to = date('Y-m-d');
    
    $date_from = $db->real_escape_string($date_from);
    $date_to = $db->real_escape_string($date_to);
    
    $query = "SELECT 
            p.id,
            p.barcode,
            p.name,
            c.name as category_name,
            p.price,
            p.cost,
            p.stock,
            COALESCE(SUM(ti.qty), 0) as total_sold,
            COALESCE(SUM(ti.total), 0) as total_revenue,
            COALESCE(SUM(ti.qty * p.cost), 0) as total_cost,
            COALESCE(SUM(ti.total - (ti.qty * p.cost)), 0) as total_profit
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN transaction_items ti ON p.id = ti.product_id
        LEFT JOIN transactions t ON ti.transaction_id = t.id 
            AND DATE(t.transaction_date) BETWEEN '$date_from' AND '$date_to'
        WHERE p.is_active = 1
        GROUP BY p.id
        ORDER BY total_sold DESC";
    
    $result = $db->query($query);
    
    $products = [];
    $summary = [
        'total_sold' => 0,
        'total_revenue' => 0,
        'total_cost' => 0,
        'total_profit' => 0
    ];
    
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
        $summary['total_sold'] += $row['total_sold'];
        $summary['total_revenue'] += $row['total_revenue'];
        $summary['total_cost'] += $row['total_cost'];
        $summary['total_profit'] += $row['total_profit'];
    }
    
    return ['products' => $products, 'summary' => $summary];
}

function getStockReport() {
    $db = Database::getInstance()->getConnection();
    
    $query = "SELECT 
            p.id,
            p.barcode,
            p.name,
            c.name as category_name,
            p.stock,
            p.price,
            p.cost,
            (p.stock * p.cost) as stock_value
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.is_active = 1
        ORDER BY p.stock ASC";
    
    $result = $db->query($query);
    
    $products = [];
    $summary = [
        'total_items' => 0,
        'low_stock' => 0,
        'total_value' => 0
    ];
    
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
        $summary['total_items']++;
        if ($row['stock'] <= 10) {
            $summary['low_stock']++;
        }
        $summary['total_value'] += $row['stock_value'];
    }
    
    return ['products' => $products, 'summary' => $summary];
}

function getDashboardStats($db) {
    $stats = [];
    
    // Total transaksi hari ini
    $result = $db->query("
        SELECT COUNT(*) as count, COALESCE(SUM(total), 0) as total 
        FROM transactions 
        WHERE DATE(transaction_date) = CURDATE()
    ");
    $row = $result->fetch_assoc();
    $stats['today_transactions'] = $row['count'];
    $stats['today_sales'] = $row['total'];
    
    // Total pendapatan bulan ini
    $result = $db->query("
        SELECT COALESCE(SUM(total), 0) as total 
        FROM transactions 
        WHERE MONTH(transaction_date) = MONTH(CURDATE()) 
        AND YEAR(transaction_date) = YEAR(CURDATE())
    ");
    $row = $result->fetch_assoc();
    $stats['month_sales'] = $row['total'];
    
    // Total produk
    $result = $db->query("SELECT COUNT(*) as count FROM products WHERE is_active = 1");
    $row = $result->fetch_assoc();
    $stats['total_products'] = $row['count'];
    
    // Produk stok menipis (stok <= 10)
    $result = $db->query("SELECT COUNT(*) as count FROM products WHERE stock <= 10 AND is_active = 1");
    $row = $result->fetch_assoc();
    $stats['low_stock_products'] = $row['count'];
    
    // Total pelanggan
    $result = $db->query("SELECT COUNT(*) as count FROM customers");
    $row = $result->fetch_assoc();
    $stats['total_customers'] = $row['count'];
    
    echo json_encode(['success' => true, 'data' => $stats]);
}

// API Handler
if (basename($_SERVER['PHP_SELF']) == 'reports.php') {
    session_start();
    header('Content-Type: application/json');
    
    // Cek login
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
    
    $type = isset($_GET['type']) ? $_GET['type'] : '';
    $db = Database::getInstance()->getConnection();
    
    if ($type == 'dashboard') {
        getDashboardStats($db);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid report type']);
    }
    exit;
}

?>
