<!-- Header halaman manajemen akun -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0 fw-bold text-dark">Manajemen Akun</h2>
    <!-- Tombol tambah akun baru -->
    <?php if ($isAdmin ?? false): ?>
        <a href="<?= BASE_URL ?>index.php?url=account/create" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Akun</span>
        </a>
    <?php endif; ?>
</div>

<!-- Notifikasi pesan -->
<?php if (!empty($flash)): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show mb-4" role="alert">
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Bar pencarian data akun (Server-Side Search) -->
<form method="GET" action="<?= BASE_URL ?>index.php" class="mb-4">
    <input type="hidden" name="url" value="account">
    <div class="input-group">
        <span class="input-group-text bg-white border-end-0 text-muted">
            <i class="bi bi-search"></i>
        </span>
        <input 
            type="text" 
            name="q" 
            class="form-control border-start-0 ps-0" 
            placeholder="Cari nama, email, atau NIM/NIP"
            value="<?= htmlspecialchars($keyword ?? '') ?>"
            autocomplete="off"
        >
        <button type="submit" class="btn btn-outline-primary">Cari</button>
    </div>
</form>

<!-- Tabel daftar data akun -->
<div class="card shadow-sm border-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="fw-bold">Nama</th>
                    <th class="fw-bold">Email</th>
                    <th class="fw-bold">Identitas</th>
                    <th class="fw-bold">Tipe Akun</th>
                    <th class="fw-bold">Status</th>
                    <?php if ($isAdmin ?? false): ?>
                        <th class="fw-bold text-center" style="width: 100px;">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($accounts)): ?>
                    <?php foreach ($accounts as $acc): ?>
                    <tr>
                        <td class="fw-bold text-dark"><?= htmlspecialchars($acc['name']) ?></td>
                        <td><?= htmlspecialchars($acc['email']) ?></td>
                        <td>
                            <span class="badge bg-secondary me-1"><?= htmlspecialchars($acc['identification_type']) ?></span>
                            <span class="small font-monospace"><?= htmlspecialchars($acc['identification_number']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($acc['account_type_name'] ?? 'User') ?></td>
                        <td>
                            <?php if (strtolower($acc['status']) === 'aktif' || strtolower($acc['status']) === 'active'): ?>
                                <span class="badge rounded-pill bg-success px-3 py-2">Aktif</span>
                            <?php else: ?>
                                <span class="badge rounded-pill bg-danger px-3 py-2"><?= htmlspecialchars($acc['status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <?php if ($isAdmin ?? false): ?>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="<?= BASE_URL ?>index.php?url=account/edit/<?= $acc['id'] ?>"
                                       class="btn btn-outline-warning btn-sm" title="Ubah Akun">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="<?= BASE_URL ?>index.php?url=account/delete/<?= $acc['id'] ?>" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus Akun">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= ($isAdmin ?? false) ? '6' : '5' ?>" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                            <span>Data akun tidak ditemukan.</span>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
