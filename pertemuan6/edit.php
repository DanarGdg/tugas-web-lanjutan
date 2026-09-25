<?php
require __DIR__ . '/koneksi.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    redirect('index.php?pesan=Data berita tidak ditemukan.');
}

$stmt = mysqli_prepare($conn, 'SELECT * FROM berita WHERE id = ?');

mysqli_stmt_bind_param($stmt, 'i', $id);

mysqli_stmt_execute($stmt);

$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$data) {
    redirect('index.php?pesan=Data berita tidak ditemukan.');
}

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
            $gambar = uploadGambar('gambar', $data['gambar']);
            
            $stmt = mysqli_prepare(
                $conn, 
                'UPDATE berita SET judul=?, gambar=?, isi=?, penulis=?, tanggal=? WHERE id=?'
            );
            
            mysqli_stmt_bind_param($stmt, 'sssssi', $judul, $gambar, $isi, $penulis, $tanggal, $id);
            
            mysqli_stmt_execute($stmt);
            
            redirect('index.php?pesan=Berita berhasil diperbarui.');
        } catch (Throwable $exception) {
            $error = $exception->getMessage();
        }
    }
}
function value(string $key, array $data): string
{
    return e($_POST[$key] ?? $data[$key] ?? '');
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Berita</title>
    <style>
        body {
            margin: 0;
            color: #172033;
            font: 15px Arial, sans-serif;
            background: linear-gradient(135deg, #f4fbff, #eef8ef);
        }

        .nav {
            max-width: 1100px;
            margin: auto;
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

        .preview {
            display: block;
            max-width: 180px;
            max-height: 100px;
            margin-top: 8px;
            object-fit: cover;
        }

        @media(max-width:600px) {
            main {
                padding: 0 20px 25px;
            }
        }
    </style>
</head>

<body>
    <nav class="nav">
        <strong>Portal Berita</strong>
        <a href="index.php">Home</a>
        <a class="active" href="tambah.php">Input Berita</a>
    </nav>
    <main>
        <h1>Edit Berita</h1>
        <?php if ($error): ?>
            <div class="error"><?= e($error) ?></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <label for="judul">Judul Berita:</label>
            <input id="judul" name="judul" type="text" value="<?= value('judul', $data) ?>" required>

            <label for="gambar">Gambar:</label>
            <?php if ($data['gambar']): ?>
                <img class="preview" src="gambar/<?= e($data['gambar']) ?>" alt="Gambar berita">
                <br>
            <?php endif; ?>
            <input id="gambar" name="gambar" type="file" accept="image/jpeg,image/png,image/gif,image/webp">

            <label for="isi">Isi Berita:</label>
            <textarea id="isi" name="isi" required><?= value('isi', $data) ?></textarea>

            <label for="penulis">Penulis:</label>
            <input id="penulis" name="penulis" type="text" value="<?= value('penulis', $data) ?>" required>

            <label for="tanggal">Tanggal:</label>
            <input id="tanggal" name="tanggal" type="date" value="<?= value('tanggal', $data) ?>" required>

            <button class="button" type="submit">Simpan Perubahan</button>
            <a class="cancel" href="index.php">Batal</a>
        </form>
    </main>
</body>

</html>