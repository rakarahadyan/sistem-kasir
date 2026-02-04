<?php
require_once '../api/auth.php';
requireLogin();
require_once '../api/customers.php';

$error = '';

// Proses tambah pelanggan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    
    if (empty($name) || empty($phone)) {
        $error = 'Nama dan telepon harus diisi!';
    } else {
        $result = createCustomer($name, $phone);
        if ($result['success']) {
            header('Location: index.php?msg=added');
            exit();
        } else {
            $error = $result['message'];
        }
    }
}

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
                        <li class="breadcrumb-item"><a href="../index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Data Pelanggan</a></li>
                        <li class="breadcrumb-item active">Tambah Pelanggan</li>
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
                                            <label for="phone">Nomor Telepon *</label>
                                            <input type="text" class="form-control" name="phone" id="phone" 
                                                   placeholder="0812xxxxxxxx" required
                                                   value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>">
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