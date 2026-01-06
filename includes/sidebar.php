<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/sistem-kasir/index.php" class="brand-link">
        <span class="brand-text font-weight-light">Sistem Kasir</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="/sistem-kasir/index.php" class="nav-link <?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/sistem-kasir/transactions/create.php" class="nav-link <?php echo ($page == 'transaksi') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-cash-register"></i>
                        <p>Transaksi Baru</p>
                    </a>
                </li>
                
                <li class="nav-item <?php echo (in_array($page, ['produk', 'kategori'])) ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link <?php echo (in_array($page, ['produk', 'kategori'])) ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>Produk <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/sistem-kasir/products/index.php" class="nav-link <?php echo ($page == 'produk') ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Produk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/sistem-kasir/categories/index.php" class="nav-link <?php echo ($page == 'kategori') ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kategori</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a href="/sistem-kasir/customers/index.php" class="nav-link <?php echo ($page == 'pelanggan') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Data Pelanggan</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/sistem-kasir/transactions/index.php" class="nav-link <?php echo ($page == 'riwayat') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Riwayat Transaksi</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/sistem-kasir/reports/index.php" class="nav-link <?php echo ($page == 'laporan') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Laporan</p>
                    </a>
                </li>
                
                <?php if ($_SESSION['role'] == 'admin'): ?>
                <li class="nav-item">
                    <a href="/sistem-kasir/users/index.php" class="nav-link <?php echo ($page == 'user') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>Manajemen User</p>
                    </a>
                </li>
                <?php endif; ?>
                
                <li class="nav-item">
                    <a href="/sistem-kasir/logout.php" class="nav-link text-danger">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>