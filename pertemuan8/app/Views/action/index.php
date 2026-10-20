<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0 fw-bold text-dark">Jenis Aksi</h2>
    <?php if ($isAdmin ?? false): ?>
        <a href="<?= BASE_URL ?>index.php?url=action/create" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Aksi</span>
        </a>
    <?php endif; ?>
</div>

<!-- Pesan sukses/gagal -->
<?php if (!empty($flash)): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show mb-4" role="alert">
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Form pencarian -->
<form method="GET" action="<?= BASE_URL ?>index.php" class="mb-4">
    <input type="hidden" name="url" value="action">
    <div class="input-group">
        <span class="input-group-text bg-white border-end-0 text-muted">
            <i class="bi bi-search"></i>
        </span>
        <input type="text" name="q" class="form-control border-start-0 ps-0" placeholder="Cari nama atau deskripsi..."
               value="<?= htmlspecialchars($keyword ?? '') ?>">
        <button class="btn btn-outline-primary" type="submit">Cari</button>
    </div>
</form>

<!-- Tabel daftar jenis aksi -->
<div class="card shadow-sm border-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="fw-bold">Nama</th>
                    <th class="fw-bold">Deskripsi</th>
                    <th class="fw-bold">Dibuat</th>
                    <?php if ($isAdmin ?? false): ?>
                        <th class="fw-bold text-end" style="width: 100px;">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($actions)): ?>
                    <tr>
                        <td colspan="<?= ($isAdmin ?? false) ? '4' : '3' ?>" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                            <span>Data jenis aksi tidak ditemukan.</span>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($actions as $item): ?>
                        <tr>
                            <td class="fw-bold text-dark"><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= htmlspecialchars($item['description'] ?? '-') ?></td>
                            <td><?= date('d M Y H:i', strtotime($item['created_at'])) ?></td>
                            <?php if ($isAdmin ?? false): ?>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="<?= BASE_URL ?>index.php?url=action/edit/<?= $item['id'] ?>"
                                           class="btn btn-outline-warning btn-sm" title="Ubah">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST"
                                              action="<?= BASE_URL ?>index.php?url=action/delete/<?= $item['id'] ?>"
                                              class="d-inline"
                                              onsubmit="return confirm('Hapus jenis aksi ini?');">
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>