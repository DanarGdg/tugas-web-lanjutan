<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = mysqli_connect('localhost', 'root', '');

mysqli_set_charset($conn, 'utf8mb4');

mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS latihan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

mysqli_select_db($conn, 'latihan');

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS berita (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	judul VARCHAR(200) NOT NULL,
	gambar VARCHAR(255) DEFAULT NULL,
	isi TEXT NOT NULL,
	penulis VARCHAR(100) NOT NULL,
	tanggal DATE NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'gambar';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function uploadGambar(string $field, ?string $gambarLama = null): ?string
{
    global $uploadDir;

    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return $gambarLama;
    }
    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Gambar gagal diunggah.');
    }
    if ($_FILES[$field]['size'] > 2 * 1024 * 1024) {
        throw new RuntimeException('Ukuran gambar maksimal 2 MB.');
    }

    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES[$field]['tmp_name']);
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Format gambar harus JPG, PNG, GIF, atau WEBP.');
    }

    $nama = bin2hex(random_bytes(12)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $uploadDir . DIRECTORY_SEPARATOR . $nama)) {
        throw new RuntimeException('Gambar tidak dapat disimpan.');
    }
    if ($gambarLama && is_file($uploadDir . DIRECTORY_SEPARATOR . $gambarLama)) {
        unlink($uploadDir . DIRECTORY_SEPARATOR . $gambarLama);
    }
    return $nama;
}

function hapusGambar(?string $nama): void
{
    global $uploadDir;
    if ($nama && is_file($uploadDir . DIRECTORY_SEPARATOR . $nama)) {
        unlink($uploadDir . DIRECTORY_SEPARATOR . $nama);
    }
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}
