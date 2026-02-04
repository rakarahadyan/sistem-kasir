<?php
require_once '../api/auth.php';
requireLogin();
checkRole('admin');
require_once '../api/users.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$users = getUsers($search);

$page_title = "Manajemen User";
$page = 'user';
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manajemen User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active">Manajemen User</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php
                    if ($_GET['msg'] == 'added') echo 'User berhasil ditambahkan!';
                    elseif ($_GET['msg'] == 'updated') echo 'User berhasil diupdate!';
                    elseif ($_GET['msg'] == 'deleted') echo 'User berhasil dihapus!';
                    ?>
                </div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar User</h3>
                            <div class="card-tools">
                                <a href="add.php" class="btn btn-sm btn-primary">
                                    <i class="fas fa-user-plus"></i> Tambah User
                                </a>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <form method="GET" action="" class="mb-3">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Cari nama atau username..." 
                                           value="<?php echo $search; ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                        <?php if ($search): ?>
                                            <a href="index.php" class="btn btn-secondary">
                                                <i class="fas fa-times"></i> Reset
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Nama</th>
                                            <th>Username</th>
                                            <th>Role</th>
                                            <th>Tanggal Dibuat</th>
                                            <th width="150">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($users)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center">Tidak ada data user</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php $no = 1; foreach ($users as $user): ?>
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td><?php echo $user['name']; ?></td>
                                                    <td><?php echo $user['username']; ?></td>
                                                    <td>
                                                        <?php
                                                        if ($user['role'] == 'admin') {
                                                            echo '<span class="badge badge-danger">Admin</span>';
                                                        } elseif ($user['role'] == 'kasir') {
                                                            echo '<span class="badge badge-primary">Kasir</span>';
                                                        } else {
                                                            echo '<span class="badge badge-info">Pemilik</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                                                    <td>
                                                        <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                            <a href="delete.php?id=<?php echo $user['id']; ?>" 
                                                               class="btn btn-sm btn-danger"
                                                               onclick="return confirm('Yakin ingin menghapus user ini?')">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        <?php endif; ?>
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
                                            <td><span class="badge badge-primary">Kasir</span></td>
                                            <td><span class="badge badge-success">Aktif</span></td>
                                            <td>2024-01-14 10:15</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info">
                                                    <i class="fas fa-key"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Kasir 2</td>
                                            <td>kasir2</td>
                                            <td><span class="badge badge-primary">Kasir</span></td>
                                            <td><span class="badge badge-warning">Nonaktif</span></td>
                                            <td>2024-01-10 09:20</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info">
                                                    <i class="fas fa-key"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Manager</td>
                                            <td>manager</td>
                                            <td><span class="badge badge-success">Manager</span></td>
                                            <td><span class="badge badge-success">Aktif</span></td>
                                            <td>2024-01-12 16:45</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info">
                                                    <i class="fas fa-key"></i>
                                                </button>
                                            </td>
                                        </tr>
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

<!-- Modal Tambah User -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User Baru</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <div class="form-group">
                        <label>Nama Lengkap *</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Username *</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password *</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                    <div class="form-group">
                        <label>Role *</label>
                        <select class="form-control" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                            <option value="kasir">Kasir</option>
                        </select>
                    </div>
                    <!-- <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div> -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveUser()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit User -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <div class="form-group">
                        <label>Nama Lengkap *</label>
                        <input type="text" class="form-control" name="name" value="Administrator" required>
                    </div>
                    <div class="form-group">
                        <label>Username *</label>
                        <input type="text" class="form-control" name="username" value="admin" required>
                    </div>
                    <div class="form-group">
                        <label>Password (kosongkan jika tidak diubah)</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <input type="password" class="form-control" name="confirm_password">
                    </div>
                    <div class="form-group">
                        <label>Role *</label>
                        <select class="form-control" name="role" required>
                            <option value="admin" selected>Admin</option>
                            <option value="manager">Manager</option>
                            <option value="kasir">Kasir</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="1" selected>Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="updateUser()">Update</button>
            </div>
        </div>
    </div>
</div>

<script>
function saveUser() {
    const form = document.getElementById('userForm');
    const password = form.password.value;
    const confirmPassword = form.confirm_password.value;
    
    if (form.checkValidity()) {
        if (password !== confirmPassword) {
            alert('Password dan konfirmasi password tidak cocok!');
            return;
        }
        
        alert('User berhasil ditambahkan!');
        $('#addUserModal').modal('hide');
        form.reset();
    } else {
        form.reportValidity();
    }
}

function updateUser() {
    const form = document.getElementById('editUserForm');
    const password = form.password.value;
    const confirmPassword = form.confirm_password.value;
    
    if (form.checkValidity()) {
        if (password && password !== confirmPassword) {
            alert('Password dan konfirmasi password tidak cocok!');
            return;
        }
        
        alert('Data user berhasil diperbarui!');
        $('#editUserModal').modal('hide');
    } else {
        form.reportValidity();
    }
}

function resetPassword(userId) {
    if (confirm('Reset password user ini ke default (123456)?')) {
        alert('Password berhasil direset ke 123456');
    }
}
</script>

<?php include '../includes/footer.php'; ?>