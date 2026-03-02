<?php
require_once __DIR__ . '/../../koneksi.php';

// handle deletion if requested
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM products WHERE id=$id");
    header('Location: index.php?msg=deleted');
    exit;
}

// fetch products
$result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
$products = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}

// page meta
$title = 'Daftar Produk';
$current_page = 'products';

// include DataTables styles/js
$extra_css = '<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">';
$extra_js = '<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-3gJwYp6vS3U3kz5CGdb9KjpGhjl7xfW5v0vZoMVFZfk=" crossorigin="anonymous"></script>';
$extra_js .= '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
$extra_js .= '<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>';
$extra_js .= "<script>\n$(document).ready(function() { $('#productsTable').DataTable(); });\n</script>";

// build content
$content = '<div class="d-flex justify-content-between align-items-center mb-3">';
$content .= '<h1 class="h3">Daftar Produk</h1>';
$content .= '<a href="create.php" class="btn btn-primary"><i class="bi bi-plus"></i> Create New</a>';
$content .= '</div>';

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'deleted') {
        $content .= '<div class="alert alert-success">Produk berhasil dihapus</div>';
    } elseif ($_GET['msg'] === 'created') {
        $content .= '<div class="alert alert-success">Produk berhasil dibuat</div>';
    } elseif ($_GET['msg'] === 'updated') {
        $content .= '<div class="alert alert-success">Produk berhasil diperbarui</div>';
    }
}

$content .= '<div class="table-responsive">';
$content .= '<table id="productsTable" class="table table-striped table-hover">';
$content .= '<thead class="table-dark"><tr>';
$content .= '<th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Image</th><th>Actions</th>';
$content .= '</tr></thead><tbody>';

if (count($products) > 0) {
    foreach ($products as $p) {
        $content .= '<tr>';
        $content .= '<td>' . htmlspecialchars($p['id']) . '</td>';
        $content .= '<td>' . htmlspecialchars($p['name']) . '</td>';
        $content .= '<td>' . htmlspecialchars($p['category']) . '</td>';
        $content .= '<td>Rp ' . number_format($p['price'],0,',','.') . '</td>';
        $content .= '<td><img src="' . htmlspecialchars($p['image']) . '" width="80" class="img-thumbnail"></td>';
        $content .= '<td>';
        $content .= '<a href="edit.php?id=' . $p['id'] . '" class="btn btn-sm btn-warning me-1"><i class="bi bi-pencil"></i></a>';
        $content .= '<a href="index.php?delete_id=' . $p['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Apakah Anda yakin ingin menghapus?\')"><i class="bi bi-trash"></i></a>';
        $content .= '</td>';
        $content .= '</tr>';
    }
} else {
    $content .= '<tr><td colspan="6" class="text-center text-muted">Tidak ada produk</td></tr>';
}

$content .= '</tbody></table></div>';

// render
include __DIR__ . '/../../components/template.php';
?>