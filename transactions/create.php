<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}

$page_title = "Transaksi Baru";
$page = 'transaksi';
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Transaksi Baru</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active">Transaksi Baru</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Form Input Produk -->
                <div class="col-md-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Tambah Produk</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Scan Barcode</label>
                                <input type="text" class="form-control" id="scanBarcode" placeholder="Scan barcode atau ketik kode..." autofocus>
                            </div>
                            
                            <div class="form-group">
                                <label>Cari Produk (Nama/Barcode)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="searchProduct" placeholder="Ketik nama atau barcode produk...">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="clearSearch()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="productList" class="mb-3">
                                <!-- Daftar produk akan muncul di sini -->
                            </div>
                            
                            <div class="form-group">
                                <label for="quantity">Jumlah</label>
                                <input type="number" class="form-control" id="quantity" value="1" min="1">
                            </div>
                            
                            <button class="btn btn-primary btn-block" onclick="addToCart()">
                                <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
                    
                    <!-- Data Pelanggan -->
                    <div class="card card-info mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Data Pelanggan</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Pilih Pelanggan</label>
                                <select class="form-control" id="customer">
                                    <option value="">Umum</option>
                                    <!-- Pelanggan dari database akan dimuat otomatis -->
                                </select>
                            </div>
                            
                            <button class="btn btn-outline-info btn-sm btn-block" data-toggle="modal" data-target="#addCustomerModal">
                                <i class="fas fa-user-plus"></i> Tambah Pelanggan Baru
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Keranjang Belanja -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Keranjang Belanja</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Produk</th>
                                            <th>Harga</th>
                                            <th>Qty</th>
                                            <th>Diskon</th>
                                            <th>Subtotal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cartItems">
                                        <!-- Item cart akan ditampilkan di sini -->
                                        <tr>
                                            <td colspan="7" class="text-center">Belum ada item</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="note">Catatan Transaksi</label>
                                        <textarea class="form-control" id="note" rows="2" placeholder="Catatan..."></textarea>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="discount">Diskon (Rp)</label>
                                        <input type="number" class="form-control" id="discount" value="0" min="0">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="tax">Pajak (Rp)</label>
                                        <input type="number" class="form-control" id="tax" value="0" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ringkasan Pembayaran -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Ringkasan Pembayaran</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="subtotal">Subtotal</label>
                                        <input type="text" class="form-control" id="subtotal" value="Rp 0" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="total">Total</label>
                                        <input type="text" class="form-control" id="total" value="Rp 0" readonly>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cash">Bayar (Cash)</label>
                                        <input type="number" class="form-control" id="cash" value="0" min="0" onchange="calculateChange()">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="change">Kembalian</label>
                                        <input type="text" class="form-control" id="change" value="Rp 0" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Metode Pembayaran</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="cashMethod" value="cash" checked>
                                    <label class="form-check-label" for="cashMethod">Cash</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="debitMethod" value="debit">
                                    <label class="form-check-label" for="debitMethod">Debit Card</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="creditMethod" value="credit">
                                    <label class="form-check-label" for="creditMethod">Credit Card</label>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-warning" onclick="clearCart()">
                                    <i class="fas fa-trash"></i> Batalkan
                                </button>
                                
                                <button class="btn btn-success" onclick="processPayment()">
                                    <i class="fas fa-print"></i> Cetak & Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah Pelanggan -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pelanggan Baru</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" class="form-control" id="customerName">
                </div>
                <div class="form-group">
                    <label>Telepon</label>
                    <input type="text" class="form-control" id="customerPhone">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="addCustomer()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];
let products = [];

async function loadInitialData() {
    try {
        const [pRes, cRes] = await Promise.all([
            fetch('/sistem-kasir/api/products.php', { credentials: 'include' }),
            fetch('/sistem-kasir/api/customers.php', { credentials: 'include' })
        ]);
        const pData = await pRes.json();
        const cData = await cRes.json();
        products = (pData.success && Array.isArray(pData.data)) ? pData.data : [];
        const custSel = document.getElementById('customer');
        if (cData.success && Array.isArray(cData.data)) {            
            for (let i = custSel.options.length - 1; i >= 1; i--) custSel.remove(i);
            cData.data.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.text = `${c.name} (${c.phone || ''})`;
                custSel.appendChild(opt);
            });
        }
    } catch (e) {
        console.error('Failed to load data', e);
    }
}
document.addEventListener('DOMContentLoaded', loadInitialData);

function searchProduct() {
    const barcodeSearch = document.getElementById('scanBarcode').value.trim();
    const nameSearch = document.getElementById('searchProduct').value.toLowerCase().trim();
    const productList = document.getElementById('productList');
        
    const search = barcodeSearch || nameSearch;
    
    productList.innerHTML = '';
    
    if (search.length >= 2) {
        let filtered = [];
                
        if (barcodeSearch) {
            filtered = products.filter(p => p.barcode.includes(barcodeSearch));
        }         
        else if (nameSearch) {
            filtered = products.filter(p => 
                p.name.toLowerCase().includes(nameSearch) || 
                p.barcode.toLowerCase().includes(nameSearch)
            );
        }
        
        if (filtered.length > 0) {            
            if (filtered.length === 1 && filtered[0].barcode === barcodeSearch) {
                selectProduct(filtered[0].id);
                return;
            }
            
            filtered.forEach(product => {
                productList.innerHTML += `
                    <div class="card mb-2 cursor-pointer" style="cursor: pointer;" onclick="selectProduct(${product.id})">
                        <div class="card-body p-2">
                            <h6 class="mb-1">${product.name}</h6>
                            <small class="text-muted">Barcode: ${product.barcode} | Stok: ${product.stock}</small>
                            <div class="mt-2">
                                <strong class="text-primary">Rp ${product.price.toLocaleString()}</strong>
                                <button class="btn btn-sm btn-outline-primary float-right" 
                                        onclick="event.stopPropagation(); selectProduct(${product.id})">
                                    <i class="fas fa-plus"></i> Pilih
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
        } else {
            productList.innerHTML = '<p class="text-center text-muted">Produk tidak ditemukan</p>';
        }
    } else if (search.length === 0) {
        productList.innerHTML = '';
    }
}

function clearSearch() {
    document.getElementById('searchProduct').value = '';
    document.getElementById('scanBarcode').value = '';
    document.getElementById('productList').innerHTML = '';
    document.getElementById('scanBarcode').focus();
}

function selectProduct(productId) {
    const product = products.find(p => p.id === productId);
    if (product) {        
        document.getElementById('scanBarcode').value = product.barcode;        
        document.getElementById('searchProduct').value = product.name;
        document.getElementById('productList').innerHTML = '';
                
        const barcodeInput = document.getElementById('scanBarcode');
        barcodeInput.classList.add('bg-success', 'text-white');
        setTimeout(() => {
            barcodeInput.classList.remove('bg-success', 'text-white');
        }, 500);
                
        document.getElementById('quantity').focus();
        document.getElementById('quantity').select();
    }
}

function addToCart() {
    const barcode = document.getElementById('scanBarcode').value;
    const quantity = parseInt(document.getElementById('quantity').value);
    
    if (!barcode) {
        alert('Silakan pilih produk terlebih dahulu!');
        return;
    }
    
    const product = products.find(p => p.barcode === barcode || String(p.id) === String(barcode));
    if (!product) {
        alert('Produk tidak ditemukan!');
        return;
    }
    
    if (quantity > product.stock) {
        alert('Stok tidak mencukupi!');
        return;
    }
        
    const existingItem = cart.find(item => item.id === product.id);
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({
            id: product.id,
            product_id: product.id,
            barcode: product.barcode,
            name: product.name,
            price: parseFloat(product.price),
            quantity: quantity,
            discount: 0
        });
    }
    
    updateCartDisplay();
    clearProductInput();
}

function updateCartDisplay() {
    const cartItems = document.getElementById('cartItems');
    
    if (cart.length === 0) {
        cartItems.innerHTML = '<tr><td colspan="7" class="text-center">Belum ada item</td></tr>';
    } else {
        let html = '';
        cart.forEach((item, index) => {
            const subtotal = item.price * item.quantity - item.discount;
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.name}</td>
                    <td>Rp ${item.price.toLocaleString()}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm" 
                               value="${item.quantity}" min="1" 
                               onchange="updateQuantity(${index}, this.value)"
                               style="width: 70px; display: inline-block;">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" 
                               value="${item.discount}" min="0" 
                               onchange="updateDiscount(${index}, this.value)"
                               style="width: 100px; display: inline-block;">
                    </td>
                    <td>Rp ${subtotal.toLocaleString()}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        cartItems.innerHTML = html;
    }
    
    updateCartSummary();
}

function updateQuantity(index, newQuantity) {
    if (newQuantity > 0) {
        cart[index].quantity = parseInt(newQuantity);
        updateCartDisplay();
    }
}

function updateDiscount(index, newDiscount) {
    cart[index].discount = parseInt(newDiscount) || 0;
    updateCartDisplay();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCartDisplay();
}

function clearCart() {
    if (confirm('Yakin ingin membatalkan transaksi?')) {
        cart = [];
        updateCartDisplay();
        clearForm();
    }
}

function clearProductInput() {
    document.getElementById('scanBarcode').value = '';
    document.getElementById('searchProduct').value = '';
    document.getElementById('quantity').value = 1;
    document.getElementById('productList').innerHTML = '';
}

function clearForm() {
    clearProductInput();
    document.getElementById('customer').value = '';
    document.getElementById('note').value = '';
    document.getElementById('discount').value = 0;
    document.getElementById('tax').value = 0;
    document.getElementById('cash').value = 0;
    document.getElementById('change').value = 'Rp 0';
}

function updateCartSummary() {
    let subtotal = 0;
    cart.forEach(item => {
        subtotal += (item.price * item.quantity) - item.discount;
    });
    
    const discount = parseInt(document.getElementById('discount').value) || 0;
    const tax = parseInt(document.getElementById('tax').value) || 0;
    const total = subtotal - discount + tax;
    
    document.getElementById('subtotal').value = `Rp ${subtotal.toLocaleString()}`;
    document.getElementById('total').value = `Rp ${total.toLocaleString()}`;
    
    calculateChange();
}

function calculateChange() {
    const total = parseFloat(document.getElementById('total').value.replace(/[^0-9]/g, '')) || 0;
    const cash = parseFloat(document.getElementById('cash').value) || 0;
    const change = cash - total;
    
    document.getElementById('change').value = change >= 0 ? `Rp ${change.toLocaleString()}` : 'Rp 0';
}

function processPayment() {
    if (cart.length === 0) {
        alert('Keranjang belanja kosong!');
        return;
    }
    
    const cash = parseFloat(document.getElementById('cash').value) || 0;
    const total = parseFloat(document.getElementById('total').value.replace(/[^0-9]/g, '')) || 0;
    
    if (cash < total) {
        alert('Jumlah pembayaran kurang!');
        return;
    }
    
    if (!confirm('Simpan transaksi dan cetak struk?')) return;

    const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
    const transactionPayload = {
        customer_id: document.getElementById('customer').value || null,
        items: cart,
        discount: parseFloat(document.getElementById('discount').value) || 0,
        tax: parseFloat(document.getElementById('tax').value) || 0,
        payment: {
            method: paymentMethod,
            cash: cash,
            change: cash - total
        }
    };

    fetch('/sistem-kasir/api/transactions.php', {
        method: 'POST',
        credentials: 'include',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(transactionPayload)
    }).then(r => {
        return r.json();
    }).then(data => {
        if (data.success) {
            alert('Transaksi berhasil disimpan! Kode: ' + data.transaction_code);            
            window.open('print.php?id=' + data.transaction_id, '_blank');
            cart = [];
            clearForm();
            updateCartDisplay();
        } else {
            alert(data.message || 'Gagal menyimpan transaksi');
        }
    }).catch(err => {
        console.error('Error:', err);
        alert('Network error saat menyimpan transaksi. Cek console untuk detail.');
    });
}

function addCustomer() {
    const name = document.getElementById('customerName').value.trim();
    const phone = document.getElementById('customerPhone').value.trim();
    if (!name || !phone) { alert('Harap isi semua field!'); return; }
    fetch('/sistem-kasir/api/customers.php', {
        method: 'POST',
        credentials: 'include',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, phone })
    }).then(r => r.json()).then(data => {
        if (data.success) {
            alert(`Pelanggan ${name} berhasil ditambahkan!`);
            $('#addCustomerModal').modal('hide');
            document.getElementById('customerName').value = '';
            document.getElementById('customerPhone').value = '';            
            loadInitialData();
        } else {
            alert(data.message || 'Gagal menambahkan pelanggan');
        }
    }).catch(err => alert('Network error'));
}

document.getElementById('scanBarcode').addEventListener('input', function(e) {    
    searchProduct();
});

document.getElementById('scanBarcode').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addToCart();
    }
});

let searchTimeout;
document.getElementById('searchProduct').addEventListener('input', function(e) {
    const name = e.target.value.trim();
        
    if (searchTimeout) clearTimeout(searchTimeout);
        
    searchProduct();
        
    if (name.length >= 3) {
        searchTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`/sistem-kasir/api/products.php?action=get_barcode&name=${encodeURIComponent(name)}`, {
                    credentials: 'include'
                });
                const data = await response.json();
                
                if (data.success && data.data) {                    
                    document.getElementById('scanBarcode').value = data.data.barcode;
                                        
                    const barcodeInput = document.getElementById('scanBarcode');
                    barcodeInput.classList.add('bg-info', 'text-white');
                    setTimeout(() => {
                        barcodeInput.classList.remove('bg-info', 'text-white');
                    }, 300);
                }
            } catch (err) {
                console.error('Error fetching barcode:', err);
            }
        }, 500);
    }
});

document.getElementById('searchProduct').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();        
        const firstProduct = document.querySelector('#productList .card');
        if (firstProduct) {
            firstProduct.click();
        }
    }
});

document.getElementById('discount').addEventListener('input', updateCartSummary);
document.getElementById('tax').addEventListener('input', updateCartSummary);
</script>

<?php include '../includes/footer.php'; ?>