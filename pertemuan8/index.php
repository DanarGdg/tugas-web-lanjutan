<?php

// Inisialisasi sesi aplikasi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memuat berkas konfigurasi
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';

// Memuat berkas inti (core MVC)
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';

// Inisialisasi router aplikasi
$app = new Router();
