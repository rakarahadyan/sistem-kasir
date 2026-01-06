<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$page_title = 'Manajemen Kategori';
$page = 'kategori';
$breadcrumbs = [ ['title' => 'Dashboard', 'url' => '/sistem-kasir/index.php'], ['title' => 'Kategori'] ];
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manajemen Kategori</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/sistem-kasir/index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Kategori</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Daftar Kategori</h3>
                    <div>
                        <button id="btnAdd" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Kategori</button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="categoriesTable">
                        <thead>
                            <tr>
                                <th style="width:80px">ID</th>
                                <th>Nama</th>
                                <th style="width:120px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Kategori</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="categoryForm">
          <input type="hidden" name="id" />
          <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" required />
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" id="saveBtn" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<script>
$(function(){
    const apiUrl = '/sistem-kasir/api/categories.php';

    function load(){
        fetch(apiUrl).then(r=>r.json()).then(res=>{
            const tbody = $('#categoriesTable tbody').empty();
            if(res.success){
                res.data.forEach(c=>{
                    const tr = $('<tr>');
                    tr.append($('<td>').text(c.id));
                    tr.append($('<td>').text(c.name));
                    const actions = $('<td>');
                    actions.append(`<button class="btn btn-sm btn-info mr-1 btn-edit" data-id="${c.id}" data-name="${escapeHtml(c.name)}"><i class="fas fa-edit"></i></button>`);
                    actions.append(`<button class="btn btn-sm btn-danger btn-delete" data-id="${c.id}"><i class="fas fa-trash"></i></button>`);
                    tr.append(actions);
                    tbody.append(tr);
                });
            }
        });
    }

    function escapeHtml(str){ return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

    load();

    $('#btnAdd').on('click', function(){
        $('#categoryModal .modal-title').text('Tambah Kategori');
        $('#categoryForm')[0].reset();
        $('#categoryForm input[name=id]').val('');
        $('#categoryModal').modal('show');
    });

    $(document).on('click', '.btn-edit', function(){
        const id = $(this).data('id');
        const name = $(this).data('name');
        $('#categoryModal .modal-title').text('Edit Kategori');
        $('#categoryForm input[name=id]').val(id);
        $('#categoryForm input[name=name]').val(name);
        $('#categoryModal').modal('show');
    });

    $(document).on('click', '.btn-delete', function(){
        if(!confirm('Hapus kategori ini?')) return;
        const id = $(this).data('id');
        fetch(apiUrl + '?id=' + id, { method: 'DELETE' }).then(r=>r.json()).then(res=>{
            if(res.success) load(); else alert(res.message || 'Gagal');
        });
    });

    $('#saveBtn').on('click', function(){
        const id = $('#categoryForm input[name=id]').val();
        const name = $('#categoryForm input[name=name]').val().trim();
        if(!name) return alert('Nama wajib diisi');
        const payload = JSON.stringify({ name });
        if(id){
            fetch(apiUrl + '?id=' + id, { method: 'PUT', body: payload }).then(r=>r.json()).then(res=>{ if(res.success){ $('#categoryModal').modal('hide'); load(); } else alert(res.message || 'Gagal'); });
        } else {
            fetch(apiUrl, { method: 'POST', headers: {'Content-Type':'application/json'}, body: payload }).then(r=>r.json()).then(res=>{ if(res.success){ $('#categoryModal').modal('hide'); load(); } else alert(res.message || 'Gagal'); });
        }
    });
});
</script>
