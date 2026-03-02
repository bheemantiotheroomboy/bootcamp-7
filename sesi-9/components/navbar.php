<?php
/**
 * Bootstrap 5 Navbar Component
 * Menentukan halaman aktif berdasarkan URL atau variable $current_page
 */

// Tentukan halaman saat ini
$current = basename($_SERVER['PHP_SELF']);
if (isset($current_page)) {
    $current = $current_page;
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="<?php echo strpos($_SERVER['PHP_SELF'], '/admin/') !== false ? '../../home.php' : 'home.php'; ?>">
      <i class="bi bi-shop"></i> MySite Store
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
      data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?php echo ($current == 'home.php' || $current == 'index.php') ? 'active' : ''; ?>" 
             href="<?php echo strpos($_SERVER['PHP_SELF'], '/admin/') !== false ? '../../home.php' : 'home.php'; ?>">
            <i class="bi bi-house"></i> Beranda
          </a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link <?php echo (strpos($current, 'products') !== false || $current == 'admin') ? 'active' : ''; ?>" 
             href="<?php echo strpos($_SERVER['PHP_SELF'], '/admin/') !== false ? 'index.php' : 'admin/products/index.php'; ?>">
            <i class="bi bi-box"></i> 
            <?php echo strpos($_SERVER['PHP_SELF'], '/admin/') !== false ? 'Kelola Produk' : 'Admin'; ?>
          </a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-gear"></i> Menu
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="<?php echo strpos($_SERVER['PHP_SELF'], '/admin/') !== false ? '../../cart.php' : 'cart.php'; ?>"><i class="bi bi-cart"></i> Keranjang</a></li>
            <li><a class="dropdown-item" href="<?php echo strpos($_SERVER['PHP_SELF'], '/admin/') !== false ? '../../transaction-list.php' : 'transaction-list.php'; ?>"><i class="bi bi-receipt"></i> Riwayat Transaksi</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Dashboard</a></li>
            <li><a class="dropdown-item" href="#">Pengaturan</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
