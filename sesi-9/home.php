<?php
session_start();
require 'koneksi.php';

// Initialize variables
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$current_page = 'home';
$title = 'Daftar Produk';

// Build query with filters
$query = "SELECT * FROM products WHERE 1=1";

// Add search filter for name
if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $query .= " AND name LIKE '%$search%'";
}

// Add category filter
if (!empty($category)) {
    $category = mysqli_real_escape_string($conn, $category);
    $query .= " AND category = '$category'";
}

//show data from database in products table
$result = mysqli_query($conn, $query);
//debug result
if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

// Get all categories for dropdown
$categoryResult = mysqli_query($conn, "SELECT DISTINCT category FROM products ORDER BY category");

// Build content HTML
$content = '<div class="d-flex justify-content-between align-items-center mb-3">';
$content .= '<h1>Daftar Produk</h1>';
$content .= '<div>';
$content .= '<a href="cart.php" class="btn btn-sm btn-warning me-2"><i class="bi bi-cart"></i> Keranjang (' . (isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0) . ')</a>';
$content .= '<a href="transaction-list.php" class="btn btn-sm btn-info me-2"><i class="bi bi-receipt"></i> Riwayat</a>';
$content .= '<a href="admin/products/index.php" class="btn btn-sm btn-outline-secondary">Admin</a>';
$content .= '</div>';
$content .= '</div>';

// Filter Form
$content .= '<div class="card mt-4 mb-4">';
$content .= '<div class="card-body">';
$content .= '<form method="GET" class="row g-3">';
$content .= '<div class="col-md-6">';
$content .= '<label for="search" class="form-label">Cari Nama Produk</label>';
$content .= '<input type="text" class="form-control" id="search" name="search" placeholder="Masukkan nama produk..." value="' . htmlspecialchars($search) . '">';
$content .= '</div>';
$content .= '<div class="col-md-4">';
$content .= '<label for="category" class="form-label">Filter Kategori</label>';
$content .= '<select class="form-select" id="category" name="category">';
$content .= '<option value="">-- Semua Kategori --</option>';

// Add category options
if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
    while ($cat = mysqli_fetch_assoc($categoryResult)) {
        $selected = ($category === $cat['category']) ? 'selected' : '';
        $content .= '<option value="' . htmlspecialchars($cat['category']) . '" ' . $selected . '>' . htmlspecialchars($cat['category']) . '</option>';
    }
}

$content .= '</select>';
$content .= '</div>';
$content .= '<div class="col-md-2 d-flex align-items-end">';
$content .= '<button type="submit" class="btn btn-primary w-100">Cari</button>';
$content .= '</div>';
$content .= '</form>';

if (!empty($search) || !empty($category)) {
    $content .= '<div class="mt-2">';
    $content .= '<a href="home.php" class="btn btn-secondary btn-sm">Reset Filter</a>';
    $content .= '</div>';
}

$content .= '</div>';
$content .= '</div>';

// Results Info
$content .= '<p class="text-muted">';
$content .= 'Ditemukan <strong>' . count($products) . '</strong> produk';
if (!empty($search)) $content .= " dengan nama '<strong>" . htmlspecialchars($search) . "</strong>'";
if (!empty($category)) $content .= " dari kategori '<strong>" . htmlspecialchars($category) . "</strong>'";
$content .= '</p>';

// Products Grid
$content .= '<div class="row mt-4">';

if (count($products) > 0) {
    foreach ($products as $product) {
        $content .= '<div class="col-md-4 mb-4">';
        $content .= '<div class="card h-100">';
        $content .= '<img src="' . htmlspecialchars($product['image']) . '" class="card-img-top" alt="' . htmlspecialchars($product['name']) . '" style="height:250px;object-fit:cover;">';
        $content .= '<div class="card-body d-flex flex-column">';
        $content .= '<h5 class="card-title">' . htmlspecialchars($product['name']) . '</h5>';
        $content .= '<p class="card-text text-muted small">' . htmlspecialchars(substr($product['description'], 0, 100)) . '...</p>';
        $content .= '<p class="card-text"><span class="badge bg-info">' . htmlspecialchars($product['category']) . '</span></p>';
        $content .= '<p class="card-text mt-auto"><strong class="text-success">Rp ' . number_format($product['price'], 0, ',', '.') . '</strong></p>';
        $content .= '<form method="POST" action="cart.php" class="mt-3">';
        $content .= '<input type="hidden" name="product_id" value="' . $product['id'] . '">';
        $content .= '<div class="input-group input-group-sm mb-2">';
        $content .= '<input type="number" name="quantity" value="1" min="1" class="form-control" placeholder="Jumlah">';
        $content .= '<button type="submit" class="btn btn-primary"><i class="bi bi-cart-plus"></i> Cart</button>';
        $content .= '</div>';
        $content .= '</form>';
        $content .= '</div></div></div>';
    }
} else {
    $content .= '<div class="col-12"><div class="alert alert-info">Tidak ada produk yang ditemukan</div></div>';
}

$content .= '</div>';
$content .= '<div class="mt-4"></div>';
$content .= '</div>';

include __DIR__ . '/components/template.php';
