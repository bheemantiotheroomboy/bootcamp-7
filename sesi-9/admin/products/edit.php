<?php
require_once __DIR__ . '/../../koneksi.php';

$title = 'Edit Produk';
$current_page = 'products';
$message = '';
$error = '';

// fetch product
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$id = intval($_GET['id']);

$result = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
if (!$result || mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit;
}
$product = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $price = isset($_POST['price']) ? trim($_POST['price']) : '';
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';

    if (empty($name)) {
        $error = 'Nama produk tidak boleh kosong';
    } elseif (empty($description)) {
        $error = 'Deskripsi tidak boleh kosong';
    } elseif (empty($price) || !is_numeric($price) || $price < 0) {
        $error = 'Harga harus berupa angka positif';
    } elseif (empty($category)) {
        $error = 'Kategori tidak boleh kosong';
    }

    // image upload
    $image_path = $product['image'];
    if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed)) {
            $error = 'Format file tidak didukung. Gunakan JPG, PNG, atau GIF';
        } elseif ($_FILES['image']['size'] > 5000000) {
            $error = 'Ukuran file terlalu besar. Maksimal 5MB';
        } else {
            $image_dir = __DIR__ . '/../../image';
            if (!is_dir($image_dir)) {
                mkdir($image_dir, 0755, true);
            }
            $new_filename = uniqid('product_') . '.' . $file_ext;
            $new_path = $image_dir . '/' . $new_filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $new_path)) {
                // delete old image if exists and not empty
                if (!empty($product['image']) && file_exists(__DIR__ . '/../../' . $product['image'])) {
                    @unlink(__DIR__ . '/../../' . $product['image']);
                }
                $image_path = 'image/' . $new_filename;
            } else {
                $error = 'Gagal mengupload gambar';
            }
        }
    }

    if (empty($error)) {
        $name = mysqli_real_escape_string($conn, $name);
        $description = mysqli_real_escape_string($conn, $description);
        $category = mysqli_real_escape_string($conn, $category);
        $image_path = mysqli_real_escape_string($conn, $image_path);

        $query = "UPDATE products SET name='$name', description='$description', price=$price, category='$category', image='$image_path' WHERE id=$id";
        if (mysqli_query($conn, $query)) {
            header('Location: index.php?msg=updated');
            exit;
        } else {
            $error = 'Gagal memperbarui produk: ' . mysqli_error($conn);
        }
    }
}

// build content
$content = '<div>';
$content .= '<h1 class="mb-4">Edit Produk</h1>';
$content .= '<a href="index.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>';

if (!empty($message)) {
    $content .= '<div class="alert alert-success alert-dismissible fade show" role="alert">' . $message . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}
if (!empty($error)) {
    $content .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

$content .= '<div class="card">';
$content .= '<div class="card-body">';
$content .= '<form method="POST" enctype="multipart/form-data" class="row g-3">';

$content .= '<div class="col-md-6">';
$content .= '<label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>';
$content .= '<input type="text" class="form-control" id="name" name="name" required value="' . htmlspecialchars(isset($_POST['name']) ? $_POST['name'] : $product['name']) . '">';
$content .= '</div>';

$content .= '<div class="col-md-6">';
$content .= '<label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>';
$content .= '<input type="text" class="form-control" id="category" name="category" required value="' . htmlspecialchars(isset($_POST['category']) ? $_POST['category'] : $product['category']) . '">';
$content .= '</div>';

$content .= '<div class="col-md-6">';
$content .= '<label for="price" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>';
$content .= '<input type="number" class="form-control" id="price" name="price" required min="0" value="' . htmlspecialchars(isset($_POST['price']) ? $_POST['price'] : $product['price']) . '">';
$content .= '</div>';

$content .= '<div class="col-md-6">';
$content .= '<label for="image" class="form-label">Gambar Produk</label>';
$content .= '<input type="file" class="form-control" id="image" name="image" accept="image/*">';
if (!empty($product['image'])) {
    $content .= '<img src="' . htmlspecialchars($product['image']) . '" alt="" class="img-thumbnail mt-2" width="120">';
}
$content .= '<small class="text-muted">Kosongkan jika tidak ingin mengganti</small>';
$content .= '</div>';

$content .= '<div class="col-12">';
$content .= '<label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>';
$content .= '<textarea class="form-control" id="description" name="description" rows="4" required>' . htmlspecialchars(isset($_POST['description']) ? $_POST['description'] : $product['description']) . '</textarea>';
$content .= '</div>';

$content .= '<div class="col-12">';
$content .= '<button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Produk</button>';
$content .= '<button type="reset" class="btn btn-secondary"><i class="bi bi-arrow-clockwise"></i> Reset</button>';
$content .= '</div>';

$content .= '</form>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';

include __DIR__ . '/../../components/template.php';
