<!-- Navigasi sidebar Bootstrap 5 -->
<nav class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white" style="width: 250px; min-height: 100vh;">
    <a href="<?= BASE_URL ?>index.php?url=account" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none px-2 py-1">
        <i class="bi bi-shield-check fs-4 text-primary me-2"></i>
        <span class="fs-5 fw-bold">Manajemen Akun</span>
    </a>
    <hr class="text-secondary my-3">
    <ul class="nav nav-pills flex-column mb-auto gap-1">
        <li class="nav-item">
            <a href="<?= BASE_URL ?>index.php?url=account" class="nav-link text-white <?= (isset($activeMenu) && $activeMenu === 'account') ? 'active bg-primary' : 'opacity-75' ?>">
                <i class="bi bi-people me-2"></i>
                <span>Akun</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL ?>index.php?url=accountType" class="nav-link text-white <?= (isset($activeMenu) && $activeMenu === 'account_type') ? 'active bg-primary' : 'opacity-75' ?>">
                <i class="bi bi-phone me-2"></i>
                <span>Tipe Akun</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL ?>index.php?url=action" class="nav-link text-white <?= (isset($activeMenu) && $activeMenu === 'action') ? 'active bg-primary' : 'opacity-75' ?>">
                <i class="bi bi-lightning-charge me-2"></i>
                <span>Jenis Aksi</span>
            </a>
        </li>
    </ul>
</nav>
