<?php

// Deteksi BASE_URL secara dinamis untuk subfolder Apache/XAMPP maupun PHP built-in server
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
$dir = dirname($scriptName);
$baseUrl = ($dir === '/' || $dir === '\\') ? '/' : rtrim($dir, '/') . '/';

define('BASE_URL', $baseUrl);
define('APP_NAME', 'Manajemen Akun');
define('APP_VERSION', '1.0.0');
