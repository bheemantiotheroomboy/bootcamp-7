<?php
session_start();
require_once __DIR__ . '/koneksi.php';

// Get transaction ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: transaction-list.php');
    exit;
}

$transaction_id = intval($_GET['id']);

// Fetch transaction
$result = mysqli_query($conn, "SELECT * FROM transaction WHERE id=$transaction_id");
if (!$result || mysqli_num_rows($result) == 0) {
    header('Location: transaction-list.php');
    exit;
}
$transaction = mysqli_fetch_assoc($result);

// Fetch transaction items
$items_result = mysqli_query($conn, "
    SELECT ti.*, p.name, p.image 
    FROM transaction_item ti 
    JOIN products p ON ti.product_id = p.id 
    WHERE ti.transaction_id=$transaction_id
");
$items = [];
if ($items_result) {
    while ($row = mysqli_fetch_assoc($items_result)) {
        $items[] = $row;
    }
}

$title = 'Detail Transaksi #' . $transaction_id;
$current_page = 'transactions';

$content = '<div>';
$content .= '<h1 class="mb-4">Detail Transaksi #' . htmlspecialchars($transaction_id) . '</h1>';
$content .= '<a href="transaction-list.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>';

if (isset($_GET['msg']) && $_GET['msg'] === 'success') {
    $content .= '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    $content .= '<strong>Transaksi Berhasil!</strong> Pesanan Anda telah disimpan. Terima kasih atas pembelian Anda.';
    $content .= '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    $content .= '</div>';
}

// Transaction info
$content .= '<div class="row mb-4">';
$content .= '<div class="col-md-6">';
$content .= '<div class="card">';
$content .= '<div class="card-header"><h5>Informasi Transaksi</h5></div>';
$content .= '<div class="card-body">';
$content .= '<p><strong>ID:</strong> #' . htmlspecialchars($transaction_id) . '</p>';
$status_badge = $transaction['status'] === 'completed' ? 'bg-success' : 'bg-warning';
$content .= '<p><strong>Status:</strong> <span class="badge ' . $status_badge . '">' . htmlspecialchars($transaction['status']) . '</span></p>';
$content .= '<p><strong>Total:</strong> <span class="text-success">Rp ' . number_format($transaction['total'], 0, ',', '.') . '</span></p>';
$content .= '<p><strong>Tanggal:</strong> ' . date('d M Y H:i', strtotime($transaction['created_at'])) . '</p>';
$content .= '</div></div></div>';
$content .= '</div>';

// Items
$content .= '<h5 class="mb-3">Detail Produk</h5>';
$content .= '<div class="table-responsive">';
$content .= '<table class="table table-striped">';
$content .= '<thead class="table-dark"><tr>';
$content .= '<th>Produk</th><th>Jumlah</th><th>Harga</th><th>Total</th>';
$content .= '</tr></thead><tbody>';

foreach ($items as $item) {
    $unit_price = $item['total_price'] / $item['quantity'];
    $content .= '<tr>';
    $content .= '<td><strong>' . htmlspecialchars($item['name']) . '</strong></td>';
    $content .= '<td>' . $item['quantity'] . '</td>';
    $content .= '<td>Rp ' . number_format($unit_price, 0, ',', '.') . '</td>';
    $content .= '<td>Rp ' . number_format($item['total_price'], 0, ',', '.') . '</td>';
    $content .= '</tr>';
}

$content .= '</tbody></table></div>';

$content .= '<hr>';
$content .= '<div class="text-end">';
$content .= '<a href="transaction-list.php" class="btn btn-primary">Lihat Semua Transaksi</a>';
$content .= '</div>';

$content .= '</div>';

include __DIR__ . '/components/template.php';
