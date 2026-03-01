<?php
require 'koneksi.php';

// Initialize variables
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
        rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>
<body>
    <div class="container">
        <h1 class="text-center mt-5">Products List</h1>
        
        <!-- Filter Form -->
        <div class="card mt-4 mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label">Cari Nama Produk</label>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Masukkan nama produk..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="category" class="form-label">Filter Kategori</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">-- Semua Kategori --</option>
                            <?php 
                            if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
                                while ($cat = mysqli_fetch_assoc($categoryResult)) {
                                    $selected = ($category === $cat['category']) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($cat['category']) . "' $selected>" . htmlspecialchars($cat['category']) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Cari</button>
                    </div>
                </form>
                <?php if (!empty($search) || !empty($category)) : ?>
                    <div class="mt-2">
                        <a href="home.php" class="btn btn-secondary btn-sm">Reset Filter</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Results Info -->
        <p class="text-muted">
            Ditemukan <strong><?= count($products) ?></strong> produk
            <?php if (!empty($search)) echo "dengan nama '<strong>$search</strong>'"; ?>
            <?php if (!empty($category)) echo "dari kategori '<strong>$category</strong>'"; ?>
        </p>

        <!-- Products Table -->
        <table class="table table-bordered table-hover mt-3">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Gambar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($products) > 0) : ?>
                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <td><?= htmlspecialchars($product['id']) ?></td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><span class="badge bg-info"><?= htmlspecialchars($product['category']) ?></span></td>
                            <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                            <td><img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="100" class="img-thumbnail"></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Tidak ada produk yang ditemukan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
     integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" 
     crossorigin="anonymous"></script>
</body>
</html>
