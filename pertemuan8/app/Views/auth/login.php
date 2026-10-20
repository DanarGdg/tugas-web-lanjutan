<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= APP_NAME ?></title>
    <!-- CSS Bootstrap 5 -->
    <link rel="stylesheet" href="<?= BASE_URL ?>bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <!-- Icon Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 py-5">
    <div class="card shadow-sm border-0" style="width: 100%; max-width: 420px;">
        <div class="card-body p-4 p-md-5">
            <!-- Header Card -->
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 mb-3 p-3 shadow-sm">
                    <i class="bi bi-shield-check fs-2"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1">Manajemen Akun</h4>
                <p class="text-muted small">Masuk dengan email dan password Anda</p>
            </div>

            <!-- Notifikasi Pesan Error / Sukses -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-2 px-3 small text-center mb-3" role="alert">
                    <i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success py-2 px-3 small text-center mb-3" role="alert">
                    <i class="bi bi-check-circle me-1"></i><?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <!-- Form Login -->
            <form action="<?= BASE_URL ?>index.php?url=auth/login" method="POST" autocomplete="off">
                <div class="mb-3">
                    <label for="email" class="form-label small fw-bold text-secondary">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        placeholder="nama@email.com"
                        required
                        autofocus>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label small fw-bold text-secondary">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="••••••••"
                        required>
                </div>


                <div class="mb-3">
                    <label for="captcha" class="form-label small fw-bold text-secondary">
                        Kode CAPTCHA
                    </label>

                    <div class="d-flex align-items-center gap-2 mb-2">
                        <img
                            src="<?= BASE_URL ?>index.php?url=auth/captcha"
                            alt="Kode CAPTCHA"
                            width="180"
                            height="60"
                            class="border rounded bg-light">

                        <!-- <button
                            type="button"
                            class="btn btn-outline-secondary"
                            onclick="refreshCaptcha()"
                            aria-label="Muat ulang CAPTCHA">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button> -->
                    </div>

                    <input
                        type="text"
                        id="captcha"
                        name="captcha"
                        class="form-control"
                        maxlength="6"
                        autocomplete="off"
                        required>

                    <div class="form-text">
                        Masukkan 6 karakter yang terlihat pada gambar.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Login</span>
                </button>
            </form>
        </div>
    </div>

    <!-- JS Bootstrap -->
    <script src="<?= BASE_URL ?>bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function refreshCaptcha() {
            const captchaImage = document.querySelector('img[alt="Kode CAPTCHA"]');
            captchaImage.src =
                '<?= BASE_URL ?>index.php?url=auth/captcha&refresh=' +
                Date.now();
        }
    </script>
</body>

</html>