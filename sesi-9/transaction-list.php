<?php
session_start();
require_once __DIR__ . '/koneksi.php';

$title = 'Riwayat Transaksi';
$current_page = 'transactions';

// Fetch all transactions
$result = mysqli_query($conn, "SELECT * FROM transaction ORDER BY created_at DESC");
$transactions = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $transactions[] = $row;
    }
}

$content = '<div>';
$content .= '<h1 class="mb-4">Riwayat Transaksi</h1>';
$content .= '<a href="home.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali Berbelanja</a>';

if (isset($_GET['msg']) && $_GET['msg'] === 'success') {
    $content .= '<div class="alert alert-success alert-dismissible fade show">';
    $content .= '<strong>Transaksi berhasil!</strong> Pesanan Anda telah disimpan.';
    if (isset($_GET['tid'])) {
        $content .= ' ID Transaksi: <strong>#' . htmlspecialchars($_GET['tid']) . '</strong>';
    }
    $content .= '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

if (count($transactions) > 0) {
    $content .= '<div class="table-responsive">';
    $content .= '<table class="table table-striped table-hover">';
    $content .= '<thead class="table-dark"><tr>';
    $content .= '<th>ID</th><th>Status</th><th>Total</th><th>Tanggal</th><th>Aksi</th>';
    $content .= '</tr></thead><tbody>';

    foreach ($transactions as $txn) {
        $status_badge = $txn['status'] === 'completed' ? 'bg-success' : 'bg-warning';
        $content .= '<tr>';
        $content .= '<td><strong>#' . htmlspecialchars($txn['id']) . '</strong></td>';
        $content .= '<td><span class="badge ' . $status_badge . '">' . htmlspecialchars($txn['status']) . '</span></td>';
        $content .= '<td>Rp ' . number_format($txn['total'], 0, ',', '.') . '</td>';
        $content .= '<td>' . date('d M Y H:i', strtotime($txn['created_at'])) . '</td>';
        $content .= '<td><a href="transaction-detail.php?id=' . $txn['id'] . '" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Detail</a></td>';
        $content .= '</tr>';
    }

    $content .= '</tbody></table></div>';
} else {
    $content .= '<div class="alert alert-info">Belum ada transaksi</div>';
}

$content .= '</div>';

include __DIR__ . '/components/template.php';
