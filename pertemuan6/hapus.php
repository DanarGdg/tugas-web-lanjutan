<?php
require __DIR__ . '/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $stmt = mysqli_prepare($conn, 'SELECT gambar FROM berita WHERE id = ?');

    mysqli_stmt_bind_param($stmt, 'i', $id);

    mysqli_stmt_execute($stmt);

    $data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    $stmt = mysqli_prepare($conn, 'DELETE FROM berita WHERE id = ?');

    mysqli_stmt_bind_param($stmt, 'i', $id);

    mysqli_stmt_execute($stmt);

    if ($data) {
        hapusGambar($data['gambar']);
    }
}

redirect('index.php?pesan=Berita berhasil dihapus.');
