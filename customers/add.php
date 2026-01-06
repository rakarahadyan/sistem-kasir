<?php
require_once '../includes/auth.php';
requireLogin();

$page_title = "Tambah Pelanggan";
$page = 'pelanggan';
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Pelanggan Baru</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Pelanggan</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Form Data Pelanggan</h3>
                        </div>
                        
                        <form id="addCustomerForm">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Nama Lengkap *</label>
                                            <input type="text" class="form-control" id="name" 
                                                   placeholder="Masukkan nama lengkap" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="phone">Nomor Telepon *</label>
                                            <input type="text" class="form-control" id="phone" 
                                                   placeholder="0812xxxxxxxx" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" 
                                                   placeholder="email@example.com">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address">Alamat</label>
                                            <textarea class="form-control" id="address" rows="3" 
                                                      placeholder="Masukkan alamat lengkap"></textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="customer_type">Tipe Pelanggan</label>
                                            <select class="form-control" id="customer_type">
                                                <option value="regular">Regular</option>
                                                <option value="member">Member</option>
                                                <option value="vip">VIP</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" id="status">
                                                <option value="1" selected>Aktif</option>
                                                <option value="0">Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Catatan</label>
                                            <textarea class="form-control" id="notes" rows="2" 
                                                      placeholder="Catatan tambahan"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <a href="index.php" class="btn btn-default">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.getElementById('addCustomerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validasi nomor telepon
    const phone = document.getElementById('phone').value;
    const phoneRegex = /^[0-9]{10,13}$/;
    
    if (!phoneRegex.test(phone)) {
        alert('Nomor telepon harus 10-13 digit angka!');
        return;
    }
    
    // Simulasi simpan data
    alert('Pelanggan berhasil ditambahkan!');
    window.location.href = 'index.php';
});
</script>

<?php include '../includes/footer.php'; ?>