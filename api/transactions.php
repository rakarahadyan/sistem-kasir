<?php
require_once __DIR__ . '/../config/database.php';

// Function untuk get semua transactions
function getTransactions($search = '', $dateFrom = '', $dateTo = '', $status = '') {
    $db = Database::getInstance()->getConnection();
    
    $query = "SELECT t.*, u.name as cashier_name, c.name as customer_name
              FROM transactions t
              LEFT JOIN users u ON t.user_id = u.id
              LEFT JOIN customers c ON t.customer_id = c.id
              WHERE 1=1";
    
    if (!empty($search)) {
        $search = $db->real_escape_string($search);
        $query .= " AND (t.transaction_code LIKE '%$search%' OR c.name LIKE '%$search%')";
    }
    
    if (!empty($dateFrom)) {
        $dateFrom = $db->real_escape_string($dateFrom);
        $query .= " AND DATE(t.transaction_date) >= '$dateFrom'";
    }
    
    if (!empty($dateTo)) {
        $dateTo = $db->real_escape_string($dateTo);
        $query .= " AND DATE(t.transaction_date) <= '$dateTo'";
    }
    
    if (!empty($status)) {
        $status = $db->real_escape_string($status);
        $query .= " AND t.status = '$status'";
    }
    
    $query .= " ORDER BY t.transaction_date DESC";
    
    $result = $db->query($query);
    
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

// Function untuk get 1 transaction by ID dengan detail items
function getTransaction($id) {
    $db = Database::getInstance()->getConnection();
    $id = intval($id);
    
    $query = "SELECT t.*, u.name as cashier_name, c.name as customer_name, c.phone as customer_phone
              FROM transactions t
              LEFT JOIN users u ON t.user_id = u.id
              LEFT JOIN customers c ON t.customer_id = c.id
              WHERE t.id = $id";
    $result = $db->query($query);
    
    if ($row = $result->fetch_assoc()) {
        // Get transaction items
        $query_items = "SELECT ti.*, p.name as product_name, p.barcode
                        FROM transaction_items ti
                        LEFT JOIN products p ON ti.product_id = p.id
                        WHERE ti.transaction_id = $id";
        $itemsResult = $db->query($query_items);
        
        $items = [];
        while ($item = $itemsResult->fetch_assoc()) {
            $items[] = $item;
        }
        $row['items'] = $items;
        
        // Get payment info
        $query_payment = "SELECT * FROM payments WHERE transaction_id = $id LIMIT 1";
        $paymentResult = $db->query($query_payment);
        if ($paymentRow = $paymentResult->fetch_assoc()) {
            $row['payment'] = $paymentRow;
        }
        
        return $row;
    }
    return null;
}

// Function untuk create transaction
function createTransaction($customerId, $items, $discount = 0, $tax = 0, $paymentMethod = 'cash', $cashPaid = 0) {
    $db = Database::getInstance()->getConnection();
    
    if (empty($items) || !is_array($items)) {
        return ['success' => false, 'message' => 'Items harus diisi'];
    }
    
    session_start();
    if (!isset($_SESSION['user_id'])) {
        return ['success' => false, 'message' => 'User belum login'];
    }
    
    $db->begin_transaction();
    
    try {
        // Generate transaction code
        $date = date('Ymd');
        $query = "SELECT COUNT(*) as count FROM transactions WHERE DATE(transaction_date) = CURDATE()";
        $result = $db->query($query);
        $row = $result->fetch_assoc();
        $sequence = str_pad($row['count'] + 1, 3, '0', STR_PAD_LEFT);
        $transactionCode = "TRX-{$date}-{$sequence}";
        
        // Calculate totals
        $subtotal = 0;
        foreach ($items as $item) {
            $itemTotal = ($item['price'] * $item['quantity']) - ($item['discount'] ?? 0);
            $subtotal += $itemTotal;
        }
        
        $discount = floatval($discount);
        $tax = floatval($tax);
        $total = $subtotal - $discount + $tax;
        $cashPaid = floatval($cashPaid);
        $changeAmount = $cashPaid - $total;
        
        // Insert transaction header
        $userId = $_SESSION['user_id'];
        $customerId = !empty($customerId) ? intval($customerId) : null;
        $status = 'selesai';
        
        if ($customerId) {
            $query = "INSERT INTO transactions 
                      (transaction_code, user_id, customer_id, transaction_date, subtotal, discount, tax, total, status) 
                      VALUES ('$transactionCode', $userId, $customerId, NOW(), $subtotal, $discount, $tax, $total, '$status')";
        } else {
            $query = "INSERT INTO transactions 
                      (transaction_code, user_id, customer_id, transaction_date, subtotal, discount, tax, total, status) 
                      VALUES ('$transactionCode', $userId, NULL, NOW(), $subtotal, $discount, $tax, $total, '$status')";
        }
        
        $db->query($query);
        $transactionId = $db->insert_id;
        
        // Insert transaction items and update stock
        foreach ($items as $item) {
            $productId = intval($item['product_id']);
            
            // Cek stok
            $query = "SELECT stock, name FROM products WHERE id = $productId";
            $result = $db->query($query);
            $product = $result->fetch_assoc();
            
            if (!$product) {
                throw new Exception("Produk ID {$productId} tidak ditemukan");
            }
            
            if ($product['stock'] < $item['quantity']) {
                throw new Exception("Stok tidak mencukupi untuk produk {$product['name']}");
            }
            
            // Insert item
            $price = floatval($item['price']);
            $qty = intval($item['quantity']);
            $itemDiscount = floatval($item['discount'] ?? 0);
            $itemTotal = ($price * $qty) - $itemDiscount;
            
            $query = "INSERT INTO transaction_items (transaction_id, product_id, price, qty, discount, total) 
                      VALUES ($transactionId, $productId, $price, $qty, $itemDiscount, $itemTotal)";
            $db->query($query);
            
            // Update stock
            $query = "UPDATE products SET stock = stock - $qty WHERE id = $productId";
            $db->query($query);
        }
        
        // Insert payment
        $paymentMethod = $db->real_escape_string($paymentMethod);
        $query = "INSERT INTO payments (transaction_id, method, amount, cash, `change`) 
                  VALUES ($transactionId, '$paymentMethod', $total, $cashPaid, $changeAmount)";
        $db->query($query);
        
        $db->commit();
        
        return [
            'success' => true,
            'message' => 'Transaksi berhasil disimpan',
            'transaction_id' => $transactionId,
            'transaction_code' => $transactionCode
        ];
        
    } catch (Exception $e) {
        $db->rollback();
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

// Function untuk update status transaction (cancel)
function updateTransactionStatus($id, $status) {
    $db = Database::getInstance()->getConnection();
    $id = intval($id);
    
    // Cek transaction exists
    $query = "SELECT status, transaction_code FROM transactions WHERE id = $id";
    $result = $db->query($query);
    
    if ($result->num_rows === 0) {
        return ['success' => false, 'message' => 'Transaksi tidak ditemukan'];
    }
    
    $transaction = $result->fetch_assoc();
    $oldStatus = $transaction['status'];
    
    // Jika dibatalkan, kembalikan stok
    if ($status === 'batal' && $oldStatus !== 'batal') {
        $db->begin_transaction();
        
        try {
            // Get items
            $query = "SELECT product_id, qty FROM transaction_items WHERE transaction_id = $id";
            $result = $db->query($query);
            
            // Kembalikan stok
            while ($item = $result->fetch_assoc()) {
                $productId = $item['product_id'];
                $qty = $item['qty'];
                $query = "UPDATE products SET stock = stock + $qty WHERE id = $productId";
                $db->query($query);
            }
            
            // Update status
            $status = $db->real_escape_string($status);
            $query = "UPDATE transactions SET status = '$status' WHERE id = $id";
            $db->query($query);
            
            $db->commit();
            
            return ['success' => true, 'message' => 'Transaksi berhasil dibatalkan'];
        } catch (Exception $e) {
            $db->rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    } else {
        // Update status saja
        $status = $db->real_escape_string($status);
        $query = "UPDATE transactions SET status = '$status' WHERE id = $id";
        
        if ($db->query($query)) {
            return ['success' => true, 'message' => 'Status transaksi berhasil diupdate'];
        } else {
            return ['success' => false, 'message' => 'Gagal update status transaksi'];
        }
    }
}

// Handle sebagai API endpoint
if (basename($_SERVER['PHP_SELF']) == 'transactions.php') {
    header('Content-Type: application/json');
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
    
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($method == 'GET') {
        if (isset($_GET['id'])) {
            $data = getTransaction($_GET['id']);
            if ($data) {
                echo json_encode(['success' => true, 'data' => $data]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Transaction not found']);
            }
        } else {
            $search = $_GET['search'] ?? '';
            $dateFrom = $_GET['date_from'] ?? '';
            $dateTo = $_GET['date_to'] ?? '';
            $status = $_GET['status'] ?? '';
            $data = getTransactions($search, $dateFrom, $dateTo, $status);
            echo json_encode(['success' => true, 'data' => $data]);
        }
    } elseif ($method == 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($input['action']) && $input['action'] == 'cancel') {
            // Update status ke batal
            $result = updateTransactionStatus($input['id'], 'batal');
            echo json_encode($result);
        } else {
            // Create new transaction
            $customerId = $input['customer_id'] ?? null;
            $items = $input['items'] ?? [];
            $discount = $input['discount'] ?? 0;
            $tax = $input['tax'] ?? 0;
            $paymentMethod = $input['payment']['method'] ?? 'cash';
            $cashPaid = $input['payment']['cash'] ?? 0;
            
            $result = createTransaction($customerId, $items, $discount, $tax, $paymentMethod, $cashPaid);
            echo json_encode($result);
        }
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
}
?>
