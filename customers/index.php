<?php
require_once '../includes/auth.php';
requireLogin();

$page_title = "Data Pelanggan";
$page = 'pelanggan';
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Pelanggan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Pelanggan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Pelanggan</h3>
                            <div class="card-tools">
                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addCustomerModal">
                                    <i class="fas fa-plus"></i> Tambah Pelanggan
                                </button>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari pelanggan..." id="searchCustomer">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Telepon</th>
                                            <!-- <th>Total Transaksi</th>
                                            <th>Terakhir Transaksi</th> -->
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Budi Santoso</td>
                                            <td>08123456789</td>
                                            <!-- <td>15 transaksi</td>
                                            <td>2024-01-15</td> -->
                                            <td>
                                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#viewCustomerModal">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editCustomerModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Siti Rahayu</td>
                                            <td>08234567890</td>
                                            <!-- <td>8 transaksi</td>
                                            <td>2024-01-14</td> -->
                                            <td>
                                                <button class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Ahmad Fauzi</td>
                                            <td>08345678901</td>
                                            <!-- <td>22 transaksi</td>
                                            <td>2024-01-13</td> -->
                                            <td>
                                                <button class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#">Previous</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
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
                <form id="customerForm">
                    <div class="form-group">
                        <label>Nama Pelanggan *</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Nomor Telepon *</label>
                        <input type="text" class="form-control" name="phone" required>
                    </div>
                    <!-- <div class="form-group">
                        <label>Alamat</label>
                        <textarea class="form-control" name="address" rows="2"></textarea>
                    </div> -->
                    <!-- <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email">
                    </div> -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveCustomer()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Pelanggan -->
<div class="modal fade" id="editCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Pelanggan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editCustomerForm">
                    <div class="form-group">
                        <label>Nama Pelanggan *</label>
                        <input type="text" class="form-control" name="name" value="Budi Santoso" required>
                    </div>
                    <div class="form-group">
                        <label>Nomor Telepon *</label>
                        <input type="text" class="form-control" name="phone" value="08123456789" required>
                    </div>
                    <!-- <div class="form-group">
                        <label>Alamat</label>
                        <textarea class="form-control" name="address" rows="2">Jl. Merdeka No. 123</textarea>
                    </div> -->
                    <!-- <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="budi@email.com">
                    </div> -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="updateCustomer()">Update</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal View Pelanggan -->
<div class="modal fade" id="viewCustomerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pelanggan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Nama</th>
                                <td>: Budi Santoso</td>
                            </tr>
                            <tr>
                                <th>Telepon</th>
                                <td>: 08123456789</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>: budi@email.com</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>: Jl. Merdeka No. 123</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th>Total Transaksi</th>
                                <td>: 15</td>
                            </tr>
                            <tr>
                                <th>Total Belanja</th>
                                <td>: Rp 2.450.000</td>
                            </tr>
                            <tr>
                                <th>Member Sejak</th>
                                <td>: 2023-05-10</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>: <span class="badge badge-success">Aktif</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <h6 class="mt-3">Riwayat Transaksi Terakhir</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>TRX-20240115-001</td>
                                <td>2024-01-15 14:30</td>
                                <td>Rp 185.000</td>
                                <td><span class="badge badge-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td>TRX-20240110-002</td>
                                <td>2024-01-10 11:20</td>
                                <td>Rp 92.500</td>
                                <td><span class="badge badge-success">Selesai</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function saveCustomer() {
    const form = document.getElementById('customerForm');
    if (form.checkValidity()) {
        alert('Pelanggan berhasil ditambahkan!');
        $('#addCustomerModal').modal('hide');
        form.reset();
    } else {
        form.reportValidity();
    }
}

function updateCustomer() {
    const form = document.getElementById('editCustomerForm');
    if (form.checkValidity()) {
        alert('Data pelanggan berhasil diperbarui!');
        $('#editCustomerModal').modal('hide');
    } else {
        form.reportValidity();
    }
}
</script>

<?php include '../includes/footer.php'; ?>