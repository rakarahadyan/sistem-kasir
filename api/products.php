<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Simulasi data dari database
$products = [
    [
        'id' => 1,
        'barcode' => '899999900001',
        'name' => 'Kopi Kapal Api 200gr',
        'category' => 'Minuman',
        'price' => 15000,
        'cost' => 12500,
        'stock' => 45,
        'is_active' => true
    ],
    [
        'id' => 2,
        'barcode' => '899999900002',
        'name' => 'Indomie Goreng',
        'category' => 'Makanan',
        'price' => 3500,
        'cost' => 2800,
        'stock' => 12,
        'is_active' => true
    ],
    [
        'id' => 3,
        'barcode' => '899999900003',
        'name' => 'Aqua 600ml',
        'category' => 'Minuman',
        'price' => 3000,
        'cost' => 2200,
        'stock' => 3,
        'is_active' => true
    ]
];

// Simulasi filter/search
if (isset($_GET['search'])) {
    $search = strtolower($_GET['search']);
    $products = array_filter($products, function($product) use ($search) {
        return strpos(strtolower($product['name']), $search) !== false ||
               strpos($product['barcode'], $search) !== false;
    });
}

echo json_encode(array_values($products));
?>