<?php

// Kelas dasar controller MVC
class Controller {



    protected function isAdmin() {
        return strtolower(trim($_SESSION['user_account_type'] ?? '')) === 'admin';
    }

    protected function checkAdmin($redirectUrl) {
        if (!$this->isAdmin()) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Anda tidak memiliki izin untuk melakukan tindakan tersebut.'
            ];
            header('Location: ' . BASE_URL . 'index.php?url=' . $redirectUrl);
            exit;
        }
    }

    // Memuat model berdasarkan nama
    public function model($model) {
        require_once __DIR__ . '/../app/Models/' . $model . '.php';
        return new $model();
    }

    // Merender tampilan ke dalam layout utama
    public function view($view, $data = []) {
        extract($data);
        
        $viewFile = __DIR__ . '/../app/Views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            $contentView = $viewFile;
            require_once __DIR__ . '/../app/Views/layouts/main.php';
        } else {
            die("Berkas view tidak ditemukan: " . $viewFile);
        }
    }

    // Merender tampilan mandiri tanpa pembungkus layout utama (misal: halaman login)
    public function viewOnly($view, $data = []) {
        extract($data);
        
        $viewFile = __DIR__ . '/../app/Views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Berkas view tidak ditemukan: " . $viewFile);
        }
    }
}
