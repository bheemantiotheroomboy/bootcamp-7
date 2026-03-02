<?php
session_start();
require_once __DIR__ . '/koneksi.php';

// Redirect if cart empty
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header('Location: cart.php');
    exit;
}

$title = 'Checkout';
$current_page = 'checkout';

// Calculate total
$cart_total = 0;
foreach ($_SESSION['cart'] as $item) {
    $cart_total += $item['price'] * $item['quantity'];
}

// Handle checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';

    // Validation
    $error = '';
    if (empty($name)) {
        $error = 'Nama harus diisi';
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email harus valid';
    } elseif (empty($phone)) {
        $error = 'No. Telpon harus diisi';
    } elseif (empty($address)) {
        $error = 'Alamat harus diisi';
    }

    if (empty($error)) {
        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            // Insert transaction
            $query = "INSERT INTO transaction (status, total) VALUES ('pending', $cart_total)";
            mysqli_query($conn, $query) or throw new Exception(mysqli_error($conn));
            $transaction_id = mysqli_insert_id($conn);

            // Insert transaction items
            foreach ($_SESSION['cart'] as $product_id => $item) {
                $item_total = $item['price'] * $item['quantity'];
                $quantity = $item['quantity'];
                $query = "INSERT INTO transaction_item (transaction_id, product_id, quantity, total_price) 
                         VALUES ($transaction_id, $product_id, $quantity, $item_total)";
                mysqli_query($conn, $query) or throw new Exception(mysqli_error($conn));
            }

            // Commit transaction
            mysqli_commit($conn);

            // Clear cart
            unset($_SESSION['cart']);

            // Redirect to transaction detail (not list)
            header('Location: transaction-detail.php?id=' . $transaction_id . '&msg=success');
            exit;
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = 'Gagal memproses transaksi: ' . $e->getMessage();
        }
    }
}

$content = '<div>';
$content .= '<h1 class="mb-4">Checkout</h1>';

if (isset($error)) {
    $content .= '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
}

$content .= '<div class="row">';
$content .= '<div class="col-md-8">';
$content .= '<div class="card mb-3">';
$content .= '<div class="card-header"><h5>Informasi Pemesan</h5></div>';
$content .= '<div class="card-body">';
$content .= '<form method="POST" class="row g-3">';

// Pre-fill from session or POST
$form_name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : (isset($_SESSION['user_data']['name']) ? htmlspecialchars($_SESSION['user_data']['name']) : '');
$form_email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : (isset($_SESSION['user_data']['email']) ? htmlspecialchars($_SESSION['user_data']['email']) : '');
$form_phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : (isset($_SESSION['user_data']['phone']) ? htmlspecialchars($_SESSION['user_data']['phone']) : '');
$form_address = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : (isset($_SESSION['user_data']['address']) ? htmlspecialchars($_SESSION['user_data']['address']) : '');

$content .= '<div class="col-md-6"><label class="form-label">Nama <span class="text-danger">*</span></label>';
$content .= '<input type="text" class="form-control" name="name" value="' . $form_name . '" required></div>';

$content .= '<div class="col-md-6"><label class="form-label">Email <span class="text-danger">*</span></label>';
$content .= '<input type="email" class="form-control" name="email" value="' . $form_email . '" required></div>';

$content .= '<div class="col-md-6"><label class="form-label">No. Telpon <span class="text-danger">*</span></label>';
$content .= '<input type="text" class="form-control" name="phone" value="' . $form_phone . '" required></div>';

$content .= '<div class="col-md-6"><label class="form-label">Alamat <span class="text-danger">*</span></label>';
$content .= '<textarea class="form-control" name="address" rows="2" required>' . $form_address . '</textarea></div>';

$content .= '<div class="col-12">';
$content .= '<button type="submit" class="btn btn-success"><i class="bi bi-credit-card"></i> Proses Pembayaran</button>';
$content .= '<a href="cart.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali ke Keranjang</a>';
$content .= '</div>';
$content .= '</form>';
$content .= '</div></div>';
$content .= '</div>';

// Order summary
$content .= '<div class="col-md-4">';
$content .= '<div class="card">';
$content .= '<div class="card-header"><h5>Ringkasan Pesanan</h5></div>';
$content .= '<div class="card-body">';

foreach ($_SESSION['cart'] as $item) {
    $item_total = $item['price'] * $item['quantity'];
    $content .= '<div class="d-flex justify-content-between mb-2">';
    $content .= '<span>' . htmlspecialchars($item['name']) . ' (x' . $item['quantity'] . ')</span>';
    $content .= '<span>Rp ' . number_format($item_total, 0, ',', '.') . '</span>';
    $content .= '</div>';
}

$content .= '<hr>';
$content .= '<div class="d-flex justify-content-between">';
$content .= '<strong>Total:</strong>';
$content .= '<strong class="text-success">Rp ' . number_format($cart_total, 0, ',', '.') . '</strong>';
$content .= '</div>';
$content .= '</div></div></div></div></div>';

include __DIR__ . '/components/template.php';
