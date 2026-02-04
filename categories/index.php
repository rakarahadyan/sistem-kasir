<?php
require_once '../api/auth.php';
requireLogin();
require_once '../api/categories.php';

// Proses delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $result = deleteCategory($id);
    if ($result['success']) {
        header('Location: index.php?msg=deleted');
        exit();
    }
}

// Ambil data kategori
$search = isset($_GET['search']) ? $_GET['search'] : '';
$categories = getCategories($search);

$page_title = 'Manajemen Kategori';
$page = 'kategori';
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manajemen Kategori</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active">Manajemen Kategori</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Kategori</h3>
                    <div class="card-tools">
                        <a href="add.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Kategori
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] == 'deleted'): ?>
                            <div class="alert alert-success">Kategori berhasil dihapus!</div>
                        <?php elseif ($_GET['msg'] == 'added'): ?>
                            <div class="alert alert-success">Kategori berhasil ditambahkan!</div>
                        <?php elseif ($_GET['msg'] == 'updated'): ?>
                            <div class="alert alert-success">Kategori berhasil diupdate!</div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" placeholder="Cari kategori..." value="<?php echo htmlspecialchars($search); ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width:80px">#</th>
                                <th>Nama Kategori</th>
                                <th style="width:150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($categories) > 0): ?>
                                <?php $no = 1; ?>
                                <?php foreach ($categories as $category): ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                                        <td>
                                            <a href="edit.php?id=<?php echo $category['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="index.php?delete=<?php echo $category['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus kategori ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data kategori</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include '../includes/footer.php'; ?>
