<?php
$isEdit = !empty($actionItem);
$action = $isEdit ? 'action/update/' . $actionItem['id'] : 'action/store';
?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 fw-bold fs-5">
        <?= $isEdit ? 'Ubah Jenis Aksi' : 'Tambah Jenis Aksi Baru' ?>
    </div>
    <div class="card-body p-4">
        <!-- Pesan error flash -->
        <?php if (!empty($flash)): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show mb-4" role="alert">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>index.php?url=<?= $action ?>">
            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Nama Jenis Aksi</label>
                <input type="text" id="name" name="name" class="form-control" maxlength="128" placeholder="Masukkan nama jenis aksi" required
                       value="<?= htmlspecialchars($actionItem['name'] ?? '') ?>">
            </div>

            <div class="mb-4">
                <label for="description" class="form-label fw-semibold">Deskripsi</label>
                <textarea id="description" name="description" rows="4" placeholder="Masukkan deskripsi jenis aksi"
                          class="form-control"><?= htmlspecialchars($actionItem['description'] ?? '') ?></textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?= BASE_URL ?>index.php?url=action" class="btn btn-secondary px-4">Batal</a>
                <button type="submit" class="btn btn-primary px-4">Simpan Data</button>
            </div>
        </form>
    </div>
</div>