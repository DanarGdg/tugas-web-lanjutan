<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' . APP_NAME : APP_NAME ?></title>
    <!-- CSS Bootstrap 5 -->
    <link rel="stylesheet" href="<?= BASE_URL ?>bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <!-- Icon Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <div class="d-flex min-vh-100">
        <!-- Sidebar navigasi -->
        <?php require_once __DIR__ . '/sidebar.php'; ?>

        <!-- Pembungkus konten utama -->
        <div class="d-flex flex-column flex-grow-1 w-100 min-vh-100 overflow-hidden">
            <!-- Navbar atas -->
            <?php require_once __DIR__ . '/navbar.php'; ?>

            <!-- Konten halaman -->
            <main class="container-fluid p-4 flex-grow-1">
                <?php 
                if (isset($contentView) && file_exists($contentView)) {
                    require_once $contentView;
                }
                ?>
            </main>
        </div>
    </div>

    <!-- JS Bootstrap -->
    <script src="<?= BASE_URL ?>bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
