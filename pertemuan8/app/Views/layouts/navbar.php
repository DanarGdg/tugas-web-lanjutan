<!-- Navbar atas Bootstrap 5 -->
<header class="navbar navbar-expand bg-white border-bottom shadow-sm px-4 py-2">
    <div class="container-fluid p-0 justify-content-end">
        <div class="d-flex align-items-center gap-3">
            <div class="text-end lh-sm">
                <div class="fw-bold small text-dark"><?= isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Administrator' ?></div>
                <div class="text-muted" style="font-size: 0.78rem;"><?= isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : 'admin@pnj.ac.id' ?></div>
            </div>
            <a href="<?= BASE_URL ?>index.php?url=auth/logout" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</header>
