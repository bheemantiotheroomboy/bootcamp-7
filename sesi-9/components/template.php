<?php
/**
 * Bootstrap 5 Template dengan Navbar
 * 
 * Cara penggunaan:
 *   $title = 'Halaman Produk';
 *   $current_page = 'home';  // untuk set active nav
 *   $content = '<h1>Welcome</h1>';
 *   include __DIR__ . '/components/template.php';
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) . ' - MySite' : 'MySite'; ?></title>
    
    <!-- Bootstrap 5.3.8 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
        rel="stylesheet" 
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" 
        crossorigin="anonymous">
    
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        footer {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            margin-top: 3rem;
        }
    </style>
    <?php
    // additional CSS inserted by pages
    if (isset($extra_css)) {
        echo $extra_css;
    }
    ?>
</head>
<body>
    <!-- Navbar -->
    <?php include __DIR__ . '/navbar.php'; ?>

    <!-- Main Content -->
    <main>
        <div class="container mt-4">
            <?php
            if (isset($content)) {
                echo $content;
            }
            ?>
        </div>
    </main>

    <!-- Footer (optional) -->
    <footer class="mt-5 py-4 text-center text-muted">
        <div class="container">
            <p>&copy; 2026 MySite. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap 5.3.8 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" 
        crossorigin="anonymous"></script>
    <?php
    // additional scripts inserted by pages
    if (isset($extra_js)) {
        echo $extra_js;
    }
    ?>
</body>
</html>
