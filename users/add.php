<?php
require_once '../includes/auth.php';
requireLogin();
checkRole('admin');

$page_title = "Tambah User";
$page = 'user';
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah User Baru</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php">User</a></li>
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
                            <h3 class="card-title">Form Tambah User</h3>
                        </div>
                        
                        <form id="addUserForm" method="post">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Nama Lengkap *</label>
                                            <input type="text" class="form-control" id="name" name="name" 
                                                   placeholder="Masukkan nama lengkap" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="username">Username *</label>
                                            <input type="text" class="form-control" id="username" name="username" 
                                                   placeholder="Masukkan username" required>
                                            <small class="text-muted">Username harus unik</small>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   placeholder="email@example.com">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password *</label>
                                            <input type="password" class="form-control" id="password" name="password" 
                                                   placeholder="Masukkan password" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="confirm_password">Konfirmasi Password *</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                                   placeholder="Konfirmasi password" required>
                                            <div id="passwordError" class="text-danger" style="display: none;">
                                                Password tidak cocok!
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="role">Role *</label>
                                            <select class="form-control" id="role" name="role" required>
                                                <option value="">Pilih Role</option>
                                                <option value="admin">Administrator</option>
                                                <option value="manager">Manager</option>
                                                <option value="kasir">Kasir</option>
                                                <option value="staff">Staff</option>
                                            </select>
                                        </div>
                                        
                                        <!-- <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="1" selected>Aktif</option>
                                                <option value="0">Nonaktif</option>
                                            </select>
                                        </div> -->
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
document.getElementById('addUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const passwordError = document.getElementById('passwordError');
    
    // Validasi password
    if (password !== confirmPassword) {
        passwordError.style.display = 'block';
        return;
    }
    
    passwordError.style.display = 'none';
    
    // Simulasi simpan data
    alert('User berhasil ditambahkan!');
    window.location.href = 'index.php';
});

// Validasi real-time password
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    const passwordError = document.getElementById('passwordError');
    
    if (password !== confirmPassword && confirmPassword !== '') {
        passwordError.style.display = 'block';
    } else {
        passwordError.style.display = 'none';
    }
});
</script>

<?php include '../includes/footer.php'; ?>