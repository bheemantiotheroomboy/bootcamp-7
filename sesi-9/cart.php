<?php
session_start();
require_once __DIR__ . '/koneksi.php';

// Initialize cart session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart via GET/POST
if (isset($_POST['product_id']) || isset($_GET['product_id'])) {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : intval($_GET['product_id']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Fetch product
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id=$product_id");
    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);

        // Add or update cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity
            ];
        }

        // Redirect to cart if POST, else return JSON for AJAX
        if (isset($_POST['product_id'])) {
            header('Location: cart.php?msg=added');
            exit;
        } else {
            echo json_encode(['status' => 'success', 'message' => 'Produk ditambahkan ke keranjang']);
            exit;
        }
    }
}

// Remove from cart
if (isset($_GET['remove_id'])) {
    $product_id = intval($_GET['remove_id']);
    unset($_SESSION['cart'][$product_id]);
    header('Location: cart.php?msg=removed');
    exit;
}

// Update quantity
if (isset($_POST['update_quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    if ($quantity > 0 && isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
    header('Location: cart.php?msg=updated');
    exit;
}

// Save user data
if (isset($_POST['save_user_data'])) {
    $_SESSION['user_data'] = [
        'name' => isset($_POST['name']) ? trim($_POST['name']) : '',
        'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
        'phone' => isset($_POST['phone']) ? trim($_POST['phone']) : '',
        'address' => isset($_POST['address']) ? trim($_POST['address']) : ''
    ];
    header('Location: cart.php?msg=user_saved');
    exit;
}

$title = 'Keranjang Belanja';
$current_page = 'cart';

// Calculate cart totals
$cart_total = 0;
$cart_items_count = 0;
foreach ($_SESSION['cart'] as $item) {
    $cart_total += $item['price'] * $item['quantity'];
    $cart_items_count += $item['quantity'];
}

$content = '<div>';
$content .= '<h1 class="mb-4">Keranjang Belanja</h1>';
$content .= '<a href="home.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Lanjut Belanja</a>';

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'added') {
        $content .= '<div class="alert alert-success">Produk ditambahkan ke keranjang</div>';
    } elseif ($_GET['msg'] === 'removed') {
        $content .= '<div class="alert alert-warning">Produk dihapus dari keranjang</div>';
    } elseif ($_GET['msg'] === 'updated') {
        $content .= '<div class="alert alert-info">Keranjang diperbarui</div>';
    } elseif ($_GET['msg'] === 'user_saved') {
        $content .= '<div class="alert alert-success">Data pembeli berhasil disimpan</div>';
    }
}

if (count($_SESSION['cart']) > 0) {
    $content .= '<div class="table-responsive">';
    $content .= '<table class="table table-striped">';
    $content .= '<thead class="table-dark"><tr>';
    $content .= '<th>Produk</th><th>Harga</th><th>Jumlah</th><th>Total</th><th>Aksi</th>';
    $content .= '</tr></thead><tbody>';

    foreach ($_SESSION['cart'] as $product_id => $item) {
        $item_total = $item['price'] * $item['quantity'];
        $content .= '<tr>';
        $content .= '<td><strong>' . htmlspecialchars($item['name']) . '</strong></td>';
        $content .= '<td>Rp ' . number_format($item['price'], 0, ',', '.') . '</td>';
        $content .= '<td>';
        $content .= '<form method="POST" style="display:inline;">';
        $content .= '<input type="hidden" name="product_id" value="' . $product_id . '">';
        $content .= '<input type="number" name="quantity" value="' . $item['quantity'] . '" min="1" class="form-control form-control-sm" style="width:80px;display:inline;">';
        $content .= '<button type="submit" name="update_quantity" class="btn btn-sm btn-info">Update</button>';
        $content .= '</form>';
        $content .= '</td>';
        $content .= '<td>Rp ' . number_format($item_total, 0, ',', '.') . '</td>';
        $content .= '<td><a href="cart.php?remove_id=' . $product_id . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Hapus dari keranjang?\')"><i class="bi bi-trash"></i></a></td>';
        $content .= '</tr>';
    }

    $content .= '</tbody></table></div>';
    $content .= '<hr>';
    
    // User Data Form
    $content .= '<div class="row">';
    $content .= '<div class="col-md-6">';
    $content .= '<div class="card mb-3">';
    $content .= '<div class="card-header"><h5>Data Pembeli</h5></div>';
    $content .= '<div class="card-body">';
    $content .= '<form method="POST" class="row g-2">';
    
    $name = isset($_SESSION['user_data']['name']) ? htmlspecialchars($_SESSION['user_data']['name']) : '';
    $email = isset($_SESSION['user_data']['email']) ? htmlspecialchars($_SESSION['user_data']['email']) : '';
    $phone = isset($_SESSION['user_data']['phone']) ? htmlspecialchars($_SESSION['user_data']['phone']) : '';
    $address = isset($_SESSION['user_data']['address']) ? htmlspecialchars($_SESSION['user_data']['address']) : '';
    
    $content .= '<div class="col-12"><label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>';
    $content .= '<input type="text" class="form-control" name="name" value="' . $name . '" required></div>';
    
    $content .= '<div class="col-12"><label class="form-label">Email <span class="text-danger">*</span></label>';
    $content .= '<input type="email" class="form-control" name="email" value="' . $email . '" required></div>';
    
    $content .= '<div class="col-12"><label class="form-label">No. Telpon <span class="text-danger">*</span></label>';
    $content .= '<input type="text" class="form-control" name="phone" value="' . $phone . '" required></div>';
    
    $content .= '<div class="col-12"><label class="form-label">Alamat <span class="text-danger">*</span></label>';
    $content .= '<textarea class="form-control" name="address" rows="2" required>' . $address . '</textarea></div>';
    
    $content .= '<div class="col-12">';
    $content .= '<button type="submit" name="save_user_data" class="btn btn-info w-100"><i class="bi bi-save"></i> Simpan Data</button>';
    $content .= '</div>';
    $content .= '</form>';
    $content .= '</div></div></div>';
    
    // Summary
    $content .= '<div class="col-md-6">';
    $content .= '<div class="card">';
    $content .= '<div class="card-body">';
    $content .= '<h5 class="card-title">Ringkasan</h5>';
    $content .= '<p>Total Item: <strong>' . $cart_items_count . '</strong></p>';
    $content .= '<p>Total Harga: <strong class="text-success">Rp ' . number_format($cart_total, 0, ',', '.') . '</strong></p>';
    
    // Check if user data is complete
    $user_data_complete = isset($_SESSION['user_data']) && 
                         !empty($_SESSION['user_data']['name']) &&
                         !empty($_SESSION['user_data']['email']) &&
                         !empty($_SESSION['user_data']['phone']) &&
                         !empty($_SESSION['user_data']['address']);
    
    if ($user_data_complete) {
        $content .= '<div class="alert alert-success alert-sm py-2"><small><i class="bi bi-check-circle"></i> Data pembeli lengkap</small></div>';
        $content .= '<a href="checkout.php" class="btn btn-success w-100"><i class="bi bi-credit-card"></i> Lanjut Checkout</a>';
    } else {
        $content .= '<div class="alert alert-warning alert-sm py-2"><small><i class="bi bi-exclamation-triangle"></i> Isi data pembeli dulu</small></div>';
        $content .= '<button type="button" class="btn btn-success w-100 disabled"><i class="bi bi-credit-card"></i> Lanjut Checkout</button>';
    }
    
    $content .= '</div></div></div>';
    $content .= '</div>';
} else {
    $content .= '<div class="alert alert-info">Keranjang belanja Anda kosong</div>';
    $content .= '<a href="home.php" class="btn btn-primary">Mulai Belanja</a>';
}

$content .= '</div>';

include __DIR__ . '/components/template.php';
