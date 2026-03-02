<?php
require_once __DIR__ . '/../../koneksi.php';

// Initialize variables
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$current_page = 'products';
$title = 'Kelola Produk';

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
$content = '<div>';
$content .= '<h1 class="mb-4">Kelola Produk</h1>';
$content .= '<a href="create.php" class="btn btn-success mb-3"><i class="bi bi-plus"></i> Tambah Produk</a>';

// Filter Form
$content .= '<div class="card mt-4 mb-4">';
$content .= '<div class="card-body">';
$content .= '<form method="GET" class="row g-3">';
$content .= '<div class="col-md-6">';
$content .= '<label for="search" class="form-label">Cari Produk</label>';
$content .= '<input type="text" class="form-control" id="search" name="search" placeholder="Cari nama produk..." value="' . htmlspecialchars($search) . '">';
$content .= '</div>';
$content .= '<div class="col-md-4">';
$content .= '<label for="category" class="form-label">Filter Kategori</label>';
$content .= '<select class="form-select" id="category" name="category">';
$content .= '<option value="">-- Semua Kategori --</option>';
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

// Products Table
$content .= '<table class="table table-bordered table-hover mt-3">';
$content .= '<thead class="table-dark">';
$content .= '<tr>';
$content .= '<th>ID</th>';
$content .= '<th>Nama</th>';
$content .= '<th>Kategori</th>';
$content .= '<th>Harga</th>';
$content .= '<th>Gambar</th>';
$content .= '<th>Aksi</th>';
$content .= '</tr>';
$content .= '</thead>';
$content .= '<tbody>';
if (count($products) > 0) {
    foreach ($products as $product) {
        $content .= '<tr>';
        $content .= '<td>' . htmlspecialchars($product['id']) . '</td>';
        $content .= '<td>' . htmlspecialchars($product['name']) . '</td>';
        $content .= '<td><span class="badge bg-info">' . htmlspecialchars($product['category']) . '</span></td>';
        $content .= '<td>Rp ' . number_format($product['price'], 0, ',', '.') . '</td>';
        $content .= '<td><img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '" width="80" class="img-thumbnail"></td>';
        $content .= '<td>';
        $content .= '<a href="#" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a> ';
        $content .= '<a href="#" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Hapus</a>';
        $content .= '</td>';
        $content .= '</tr>';
    }
} else {
    $content .= '<tr>';
    $content .= '<td colspan="6" class="text-center text-muted py-4">Tidak ada produk yang ditemukan</td>';
    $content .= '</tr>';
}
$content .= '</tbody>';
$content .= '</table>';
$content .= '</div>';

// Include template
include __DIR__ . '/../../components/template.php';
