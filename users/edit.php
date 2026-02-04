<?php
require_once '../api/auth.php';
requireLogin();
checkRole('admin');
require_once '../api/users.php';

$error = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit();
}

// Proses update user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $role = trim($_POST['role']);
    
    if (empty($name) || empty($username) || empty($role)) {
        $error = 'Nama, username, dan role harus diisi!';
    } else {
        $result = updateUser($id, $name, $username, $role);
        if ($result['success']) {
            header('Location: index.php?msg=updated');
            exit();
        } else {
            $error = $result['message'];
        }
    }
}

// Ambil data user
$userResult = getUser($id);
if (!$userResult['success']) {
    header('Location: index.php');
    exit();
}
$user = $userResult['data'];

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
                        <li class="breadcrumb-item"><a href="../index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Manajemen User</a></li>
                        <li class="breadcrumb-item active">Edit User</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Form Edit User</h3>
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
                                                   required value="<?php echo isset($_POST['name']) ? $_POST['name'] : $user['name']; ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="username">Username *</label>
                                            <input type="text" class="form-control" name="username" id="username" 
                                                   required value="<?php echo isset($_POST['username']) ? $_POST['username'] : $user['username']; ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role">Role *</label>
                                            <select class="form-control" name="role" id="role" required>
                                                <?php
                                                $selected_role = isset($_POST['role']) ? $_POST['role'] : $user['role'];
                                                ?>
                                                <option value="admin" <?php echo ($selected_role == 'admin') ? 'selected' : ''; ?>>Administrator</option>
                                                <option value="kasir" <?php echo ($selected_role == 'kasir') ? 'selected' : ''; ?>>Kasir</option>
                                                <option value="pemilik" <?php echo ($selected_role == 'pemilik') ? 'selected' : ''; ?>>Pemilik</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <button type="submit" class="btn btn-warning">
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

<?php include '../includes/footer.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.getElementById('editUserForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const passwordError = document.getElementById('passwordError');
    if (password !== '' && password !== confirmPassword) { passwordError.style.display = 'block'; return; }
    passwordError.style.display = 'none';

    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id') || '<?php echo $user['id']; ?>';
    const payload = { id: parseInt(id) };
    const fields = ['name','email','role','status','password'];
    fields.forEach(f => {
        const el = document.getElementById(f);
        if (el) payload[f] = el.value;
    });

    const res = await fetch('/sistem-kasir/api/users.php', {
        method: 'PUT',
        credentials: 'include',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    });
    const data = await res.json();
    if (res.ok && data.success) {
        alert('Data user berhasil diupdate!');
        window.location.href = 'index.php';
    } else {
        alert(data.message || 'Gagal update user');
    }
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