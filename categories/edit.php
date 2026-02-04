<?php
require_once '../api/auth.php';
requireLogin();
require_once '../api/categories.php';

$error = '';
$category = null;

// Ambil data kategori
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $category = getCategory($id);
    
    if (!$category) {
        header('Location: index.php');
        exit();
    }
}

// Proses update kategori
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $name = trim($_POST['name']);
    
    if (empty($name)) {
        $error = 'Nama kategori harus diisi!';
    } else {
        $result = updateCategory($id, $name);
        if ($result['success']) {
            header('Location: index.php?msg=updated');
            exit();
        } else {
            $error = $result['message'];
        }
    }
}

$page_title = "Edit Kategori";
$page = 'kategori';
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Data Kategori</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Manajemen Kategori</a></li>
                        <li class="breadcrumb-item active">Edit Kategori</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Form Edit Kategori</h3>
                        </div>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger m-3"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Nama Kategori *</label>
                                    <input type="text" class="form-control" name="name" id="name" 
                                           placeholder="Masukkan nama kategori" required 
                                           value="<?php echo $category['name']; ?>">
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
