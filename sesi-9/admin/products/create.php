<?php
require_once __DIR__ . '/../../koneksi.php';

$title = 'Tambah Produk';
$current_page = 'products';
$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $price = isset($_POST['price']) ? trim($_POST['price']) : '';
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    
    // Validation
    if (empty($name)) {
        $error = 'Nama produk tidak boleh kosong';
    } elseif (empty($description)) {
        $error = 'Deskripsi tidak boleh kosong';
    } elseif (empty($price) || !is_numeric($price) || $price < 0) {
        $error = 'Harga harus berupa angka positif';
    } elseif (empty($category)) {
        $error = 'Kategori tidak boleh kosong';
    }
    
    // Handle image upload
    $image_path = '';
    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (!in_array($file_ext, $allowed)) {
            $error = 'Format file tidak didukung. Gunakan JPG, PNG, atau GIF';
        } elseif ($_FILES['image']['size'] > 5000000) {
            $error = 'Ukuran file terlalu besar. Maksimal 5MB';
        } else {
            // Create image folder if not exists
            $image_dir = __DIR__ . '/../../image';
            if (!is_dir($image_dir)) {
                mkdir($image_dir, 0755, true);
            }
            
            // Generate unique filename
            $new_filename = uniqid('product_') . '.' . $file_ext;
            $image_path = 'image/' . $new_filename;
            $file_path = $image_dir . '/' . $new_filename;
            
            // Move uploaded file
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
                $error = 'Gagal mengupload gambar';
            }
        }
    } else {
        $error = 'Gambar tidak boleh kosong';
    }
    
    // Save to database if no error
    if (empty($error)) {
        $name = mysqli_real_escape_string($conn, $name);
        $description = mysqli_real_escape_string($conn, $description);
        $category = mysqli_real_escape_string($conn, $category);
        $image_path = mysqli_real_escape_string($conn, $image_path);
        
        $query = "INSERT INTO products (name, description, price, category, image) 
                  VALUES ('$name', '$description', $price, '$category', '$image_path')";
        
        if (mysqli_query($conn, $query)) {
            // redirect back to list with message
            header('Location: index.php?msg=created');
            exit;
        } else {
            $error = 'Gagal menyimpan produk: ' . mysqli_error($conn);
        }
    }
}

// Build content
$content = '<div>';
$content .= '<h1 class="mb-4">Tambah Produk Baru</h1>';
$content .= '<a href="index.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>';

// Show messages
if (!empty($message)) {
    $content .= '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    $content .= $message;
    $content .= '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    $content .= '</div>';
}

if (!empty($error)) {
    $content .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
    $content .= $error;
    $content .= '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    $content .= '</div>';
}

$content .= '<div class="card">';
$content .= '<div class="card-body">';
$content .= '<form method="POST" enctype="multipart/form-data" class="row g-3">';

$content .= '<div class="col-md-6">';
$content .= '<label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>';
$content .= '<input type="text" class="form-control" id="name" name="name" required value="' . (isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '') . '">';
$content .= '</div>';

$content .= '<div class="col-md-6">';
$content .= '<label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>';
$content .= '<input type="text" class="form-control" id="category" name="category" required value="' . (isset($_POST['category']) ? htmlspecialchars($_POST['category']) : '') . '">';
$content .= '</div>';

$content .= '<div class="col-md-6">';
$content .= '<label for="price" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>';
$content .= '<input type="number" class="form-control" id="price" name="price" required min="0" value="' . (isset($_POST['price']) ? htmlspecialchars($_POST['price']) : '') . '">';
$content .= '</div>';

$content .= '<div class="col-md-6">';
$content .= '<label for="image" class="form-label">Gambar Produk <span class="text-danger">*</span></label>';
$content .= '<input type="file" class="form-control" id="image" name="image" accept="image/*" required>';
$content .= '<small class="text-muted">Format: JPG, PNG, GIF. Maksimal 5MB</small>';
$content .= '</div>';

$content .= '<div class="col-12">';
$content .= '<label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>';
$content .= '<textarea class="form-control" id="description" name="description" rows="4" required>' . (isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '') . '</textarea>';
$content .= '</div>';

$content .= '<div class="col-12">';
$content .= '<button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Produk</button>';
$content .= '<button type="reset" class="btn btn-secondary"><i class="bi bi-arrow-clockwise"></i> Reset</button>';
$content .= '</div>';

$content .= '</form>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';

include __DIR__ . '/../../components/template.php';