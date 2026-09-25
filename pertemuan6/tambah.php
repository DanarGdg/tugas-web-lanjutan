<?php
require __DIR__ . '/koneksi.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $judul = trim($_POST['judul'] ?? '');
    $isi = trim($_POST['isi'] ?? '');
    $penulis = trim($_POST['penulis'] ?? '');
    $tanggal = $_POST['tanggal'] ?? '';

    if ($judul === '' || $isi === '' || $penulis === '' || $tanggal === '') {
        $error = 'Semua data wajib diisi.';
    } else {
        try {
            $gambar = uploadGambar('gambar');

            $stmt = mysqli_prepare(
                $conn, 
                'INSERT INTO berita (judul, gambar, isi, penulis, tanggal) VALUES (?, ?, ?, ?, ?)'
            );

            mysqli_stmt_bind_param($stmt, 'sssss', $judul, $gambar, $isi, $penulis, $tanggal);

            mysqli_stmt_execute($stmt);

            redirect('index.php?pesan=Berita berhasil ditambahkan.');
        } catch (Throwable $exception) {
            $error = $exception->getMessage();
        }
    }
}
function old(string $key): string
{
    return e($_POST[$key] ?? '');
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Input Berita</title>
    <style>
        body {
            margin: 0;
            color: #172033;
            font: 15px Arial, sans-serif;
            background: linear-gradient(135deg, #f4fbff, #eef8ef);
        }

        .nav {
            max-width: 1100px;
            margin: 0 auto;
            padding: 14px 22px;
            background: #fff;
            display: flex;
            gap: 24px;
        }

        .nav a {
            color: #778294;
            text-decoration: none;
        }

        .nav a.active {
            color: #087cf5;
        }

        main {
            max-width: 1100px;
            margin: 18px auto;
            padding: 0 42px 28px;
            background: #fff;
        }

        h1 {
            padding-top: 20px;
            font-size: 25px;
        }

        label {
            display: block;
            margin: 17px 0 7px;
            font-size: 13px;
        }

        input[type=text],
        input[type=date],
        textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #cfd7e0;
            border-radius: 4px;
            font: inherit;
        }

        textarea {
            min-height: 130px;
            resize: vertical;
        }

        input[type=file] {
            margin-top: 2px;
        }

        .button {
            margin-top: 13px;
            padding: 10px 15px;
            border: 0;
            border-radius: 4px;
            background: #087cf5;
            color: #fff;
            cursor: pointer;
        }

        .cancel {
            margin-left: 10px;
            color: #687386;
            text-decoration: none;
        }

        .error {
            padding: 11px;
            background: #fff0f0;
            color: #b52e2e;
        }

        @media(max-width:600px) {
            main {
                padding: 0 20px 25px;
            }
        }
    </style>
</head>

<body>
    <nav class="nav"><strong>Portal Berita</strong><a href="index.php">Home</a><a class="active" href="tambah.php">Input Berita</a></nav>
    <main>
        <h1>Input Berita</h1>
        <?php if ($error): ?>
            <div class="error">
                <?= e($error) ?>
            </div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <label for="judul">Judul Berita:</label>
            <input id="judul" name="judul" type="text" value="<?= old('judul') ?>" required>

            <label for="gambar">Gambar:</label>
            <input id="gambar" name="gambar" type="file" accept="image/jpeg,image/png,image/gif,image/webp">

            <label for="isi">Isi Berita:</label>
            <textarea id="isi" name="isi" required><?= old('isi') ?></textarea>

            <label for="penulis">Penulis:</label>
            <input id="penulis" name="penulis" type="text" value="<?= old('penulis') ?>" required>

            <label for="tanggal">Tanggal:</label>
            <input id="tanggal" name="tanggal" type="date" value="<?= old('tanggal') ?>" required>

            <button class="button" type="submit">Submit</button>
            <a class="cancel" href="index.php">Batal</a>
        </form>
    </main>
</body>

</html>