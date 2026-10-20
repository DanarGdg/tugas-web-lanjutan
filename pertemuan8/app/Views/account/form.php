<?php
$isEdit = !empty($account);
$action = $isEdit ? 'account/update/' . $account['id'] : 'account/store';
?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 fw-bold fs-5">
        <?= $isEdit ? 'Ubah Data Akun' : 'Tambah Data Akun Baru' ?>
    </div>
    <div class="card-body p-4">
        <!-- Pesan error flash -->
        <?php if (!empty($flash)): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>index.php?url=<?= $action ?>">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Masukkan Nama Lengkap"
                           value="<?= htmlspecialchars($account['name'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan Email"
                           value="<?= htmlspecialchars($account['email'] ?? '') ?>" required>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="password" class="form-label fw-semibold">
                        Password <?= $isEdit ? '<span class="text-muted fw-normal">(Kosongkan jika tidak ingin diubah)</span>' : '' ?>
                    </label>
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="<?= $isEdit ? 'Masukkan password baru (opsional)' : 'Masukkan Password (Minimal 6 karakter)' ?>"
                           <?= $isEdit ? '' : 'required' ?>>
                </div>
                <div class="col-md-3">
                    <label for="identification_type" class="form-label fw-semibold">Jenis Identitas</label>
                    <select id="identification_type" name="identification_type" class="form-select" required>
                        <option value="NIP" <?= ($account['identification_type'] ?? '') === 'NIP' ? 'selected' : '' ?>>NIP</option>
                        <option value="NIM" <?= ($account['identification_type'] ?? '') === 'NIM' ? 'selected' : '' ?>>NIM</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="identification_number" class="form-label fw-semibold">Nomor Identitas</label>
                    <input type="text" id="identification_number" name="identification_number" class="form-control"
                           placeholder="Masukkan Nomor Identitas"
                           value="<?= htmlspecialchars($account['identification_number'] ?? '') ?>" required>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="account_type_id" class="form-label fw-semibold">Tipe Akun</label>
                    <select id="account_type_id" name="account_type_id" class="form-select" required>
                        <option value="">-- Pilih Tipe Akun --</option>
                        <?php if (!empty($accountTypes)): ?>
                            <?php foreach ($accountTypes as $type): ?>
                                <?php 
                                $isSelected = ($account['account_type_id'] ?? '') === $type['id'];
                                ?>
                                <option value="<?= htmlspecialchars($type['id']) ?>"
                                    <?= $isSelected ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($type['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="Aktif" <?= ($account['status'] ?? 'Aktif') === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="Nonaktif" <?= ($account['status'] ?? '') === 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?= BASE_URL ?>index.php?url=account" class="btn btn-secondary px-4">Batal</a>
                <button type="submit" class="btn btn-primary px-4">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
