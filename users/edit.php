<?php
require_once '../includes/auth.php';
requireLogin();
checkRole('admin');

// Simulasi data user
$user_id = $_GET['id'] ?? 1;
$users = [
    1 => [
        'id' => 1,
        'name' => 'Administrator',
        'username' => 'admin',
        'email' => 'admin@tokomakmur.com',
        'role' => 'admin',
        'status' => 1
    ],
    2 => [
        'id' => 2,
        'name' => 'Kasir 1',
        'username' => 'kasir1',
        'email' => 'kasir1@tokomakmur.com',
        'role' => 'kasir',
        'status' => 1
    ]
];

$user = $users[$user_id] ?? $users[1];

$page_title = "Edit User";
$page = 'user';
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php">User</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                            <h3 class="card-title">Form Edit User</h3>
                        </div>
                        
                        <form id="editUserForm" method="post">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Nama Lengkap *</label>
                                            <input type="text" class="form-control" id="name" name="name" 
                                                   value="<?php echo $user['name']; ?>" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="username">Username *</label>
                                            <input type="text" class="form-control" id="username" name="username" 
                                                   value="<?php echo $user['username']; ?>" required>
                                            <small class="text-muted">Username tidak bisa diubah</small>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   value="<?php echo $user['email']; ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password (kosongkan jika tidak diubah)</label>
                                            <input type="password" class="form-control" id="password" name="password" 
                                                   placeholder="Masukkan password baru">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="confirm_password">Konfirmasi Password</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                                   placeholder="Konfirmasi password baru">
                                            <div id="passwordError" class="text-danger" style="display: none;">
                                                Password tidak cocok!
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="role">Role *</label>
                                            <select class="form-control" id="role" name="role" required>
                                                <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Administrator</option>
                                                <option value="manager" <?php echo $user['role'] == 'manager' ? 'selected' : ''; ?>>Manager</option>
                                                <option value="kasir" <?php echo $user['role'] == 'kasir' ? 'selected' : ''; ?>>Kasir</option>
                                                <option value="staff" <?php echo $user['role'] == 'staff' ? 'selected' : ''; ?>>Staff</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="1" <?php echo $user['status'] == 1 ? 'selected' : ''; ?>>Aktif</option>
                                                <option value="0" <?php echo $user['status'] == 0 ? 'selected' : ''; ?>>Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update
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
document.getElementById('editUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const passwordError = document.getElementById('passwordError');
    
    // Validasi password jika diisi
    if (password !== '' && password !== confirmPassword) {
        passwordError.style.display = 'block';
        return;
    }
    
    passwordError.style.display = 'none';
    
    // Simulasi update data
    alert('Data user berhasil diupdate!');
    window.location.href = 'index.php';
});

// Validasi real-time password
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    const passwordError = document.getElementById('passwordError');
    
    if (password !== confirmPassword && confirmPassword !== '' && password !== '') {
        passwordError.style.display = 'block';
    } else {
        passwordError.style.display = 'none';
    }
});
</script>

<?php include '../includes/footer.php'; ?>