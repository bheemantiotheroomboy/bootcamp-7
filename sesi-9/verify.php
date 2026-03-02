<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Setup Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Verifikasi Setup Produk</h1>
        <hr>

        <h2>Checklist Setup</h2>
        
        <?php
        $checks = [];

        // Check 1: Database connection
        $checks[] = [
            'name' => 'Koneksi Database',
            'passed' => file_exists('koneksi.php'),
            'note' => 'File koneksi.php ditemukan'
        ];

        // Check 2: Image folder exists
        $checks[] = [
            'name' => 'Folder Image',
            'passed' => is_dir('image'),
            'note' => 'Folder image/ sudah ada dan dapat ditulis'
        ];

        // Check 3: Template files
        $checks[] = [
            'name' => 'File Template',
            'passed' => file_exists('components/template.php') && file_exists('components/navbar.php'),
            'note' => 'File template.php dan navbar.php ditemukan'
        ];

        // Check 4: Create form exists
        $checks[] = [
            'name' => 'Form Tambah Produk',
            'passed' => file_exists('admin/products/create.php'),
            'note' => 'File create.php ditemukan'
        ];
        $checks[] = [
            'name' => 'Form Edit Produk',
            'passed' => file_exists('admin/products/edit.php'),
            'note' => 'File edit.php ditemukan'
        ];

        // Check 5: Admin home exists
        $checks[] = [
            'name' => 'Halaman Admin',
            'passed' => file_exists('admin/products/index.php'),
            'note' => 'File admin/products/index.php ditemukan'
        ];

        // Check 6: Main home exists
        $checks[] = [
            'name' => 'Halaman Utama',
            'passed' => file_exists('home.php'),
            'note' => 'File home.php ditemukan'
        ];

        // Check 7: Cart system
        $checks[] = [
            'name' => 'Sistem Keranjang',
            'passed' => file_exists('cart.php') && file_exists('checkout.php'),
            'note' => 'File cart.php & checkout.php ditemukan'
        ];

        // Check 8: Transaction files
        $checks[] = [
            'name' => 'Halaman Transaksi',
            'passed' => file_exists('transaction-list.php') && file_exists('transaction-detail.php'),
            'note' => 'File transaction-list.php & transaction-detail.php ditemukan'
        ];

        // Try to connect to database
        if (file_exists('koneksi.php')) {
            $db_connected = false;
            try {
                require 'koneksi.php';
                if ($conn) {
                    $db_connected = true;
                    
                    // Check if products table exists
                    $result = mysqli_query($conn, "SHOW TABLES LIKE 'products'");
                    $table_exists = mysqli_num_rows($result) > 0;
                    
                    $checks[] = [
                        'name' => 'Database Connected',
                        'passed' => true,
                        'note' => 'Koneksi database berhasil'
                    ];

                    $checks[] = [
                        'name' => 'Tabel Products',
                        'passed' => $table_exists,
                        'note' => $table_exists ? 'Tabel products sudah ada' : 'Tabel products belum dibuat - jalankan setup_products_table.sql'
                    ];

                    if ($table_exists) {
                        $count = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
                        $row = mysqli_fetch_assoc($count);
                        $total = $row['total'];
                        
                        $checks[] = [
                            'name' => 'Data Produk',
                            'passed' => $total > 0,
                            'note' => "Total produk: $total"
                        ];
                    }

                    // Check transaction tables
                    $txn_table = mysqli_query($conn, "SHOW TABLES LIKE 'transaction'");
                    $txn_exists = mysqli_num_rows($txn_table) > 0;
                    $txn_item_table = mysqli_query($conn, "SHOW TABLES LIKE 'transaction_item'");
                    $txn_item_exists = mysqli_num_rows($txn_item_table) > 0;

                    $checks[] = [
                        'name' => 'Tabel Transaction',
                        'passed' => $txn_exists,
                        'note' => $txn_exists ? 'Tabel transaction sudah ada' : 'Tabel transaction belum dibuat - jalankan setup_transaction_tables.sql'
                    ];

                    $checks[] = [
                        'name' => 'Tabel Transaction Item',
                        'passed' => $txn_item_exists,
                        'note' => $txn_item_exists ? 'Tabel transaction_item sudah ada' : 'Tabel transaction_item belum dibuat'
                    ];
                }
            } catch (Exception $e) {
                $checks[] = [
                    'name' => 'Database Connected',
                    'passed' => false,
                    'note' => 'Gagal terhubung ke database'
                ];
            }
        }

        // Display checks
        foreach ($checks as $check) {
            $status = $check['passed'] ? 'success' : 'warning';
            $icon = $check['passed'] ? '✓' : '⚠';
            echo '<div class="alert alert-' . $status . '">';
            echo '<strong>' . $icon . ' ' . $check['name'] . '</strong><br>';
            echo $check['note'];
            echo '</div>';
        }
        ?>

        <hr>
        <h2>Next Steps</h2>
        <ol>
            <li>Jalankan SQL setup untuk tabel produk pada phpMyAdmin atau MySQL CLI:
                <pre>mysql -u username -p database < setup_products_table.sql</pre>
            </li>
            <li>Jalankan SQL setup untuk tabel transaksi:
                <pre>mysql -u username -p database < setup_transaction_tables.sql</pre>
            </li>
            <li>Mulai server PHP:
                <pre>php -S localhost:8000 -t .</pre>
            </li>
            <li>Buka di browser:
                <ul>
                    <li><a href="home.php" target="_blank">Halaman Utama (home.php)</a></li>
                    <li><a href="cart.php" target="_blank">Keranjang (cart.php)</a></li>
                    <li><a href="transaction-list.php" target="_blank">Riwayat Transaksi (transaction-list.php)</a></li>
                    <li><a href="admin/products/index.php" target="_blank">Admin Produk (admin/products/index.php)</a></li>
                </ul>
            </li>
        </ol>

        <hr>
        <h2>Troubleshooting</h2>
        <ul>
            <li><strong>Gambar tidak terupload:</strong> Pastikan folder image/ memiliki permission 755</li>
            <li><strong>Database error:</strong> Cek koneksi.php sudah sesuai dengan credentials MySQL</li>
            <li><strong>Tabel tidak ada:</strong> Jalankan script SQL setup_products_table.sql</li>
        </ul>
    </div>
</body>
</html>
