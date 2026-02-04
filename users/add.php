<?php
require_once '../api/auth.php';
requireLogin();
checkRole('admin');
require_once '../api/users.php';

$error = '';

// Proses tambah user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = trim($_POST['role']);
    
    if (empty($name) || empty($username) || empty($password) || empty($role)) {
        $error = 'Nama, username, password, dan role harus diisi!';
    } elseif ($password !== $confirm_password) {
        $error = 'Password dan konfirmasi password tidak cocok!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } else {
        $result = createUser($name, $username, $password, $role);
        if ($result['success']) {
            header('Location: index.php?msg=added');
            exit();
        } else {
            $error = $result['message'];
        }
    }
}

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
                        <li class="breadcrumb-item"><a href="../index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Manajemen User</a></li>
                        <li class="breadcrumb-item active">Tambah User</li>
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
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger m-3"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Nama Lengkap *</label>
                                            <input type="text" class="form-control" name="name" id="name" 
                                                   placeholder="Masukkan nama lengkap" required
                                                   value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="username">Username *</label>
                                            <input type="text" class="form-control" name="username" id="username" 
                                                   placeholder="Masukkan username" required
                                                   value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>">
                                            <small class="text-muted">Username harus unik</small>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="role">Role *</label>
                                            <select class="form-control" name="role" id="role" required>
                                                <option value="">Pilih Role</option>
                                                <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] == 'admin') ? 'selected' : ''; ?>>Administrator</option>
                                                <option value="kasir" <?php echo (isset($_POST['role']) && $_POST['role'] == 'kasir') ? 'selected' : ''; ?>>Kasir</option>
                                                <option value="pemilik" <?php echo (isset($_POST['role']) && $_POST['role'] == 'pemilik') ? 'selected' : ''; ?>>Pemilik</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password *</label>
                                            <input type="password" class="form-control" name="password" id="password" 
                                                   placeholder="Masukkan password (minimal 6 karakter)" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="confirm_password">Konfirmasi Password *</label>
                                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" 
                                                   placeholder="Konfirmasi password" required>
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

<?php include '../includes/footer.php'; ?>
                                        
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
document.getElementById('addUserForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const passwordError = document.getElementById('passwordError');
    if (password !== confirmPassword) { passwordError.style.display = 'block'; return; }
    passwordError.style.display = 'none';

    const payload = {
        name: document.getElementById('name').value,
        username: document.getElementById('username').value,
        password: password,
        email: document.getElementById('email').value || null,
        role: document.getElementById('role').value
    };

    const res = await fetch('/sistem-kasir/api/users.php', {
        method: 'POST',
        credentials: 'include',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    });
    const data = await res.json();
    if (res.ok && data.success) {
        alert('User berhasil ditambahkan!');
        window.location.href = 'index.php';
    } else {
        alert(data.message || 'Gagal menambah user');
    }
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