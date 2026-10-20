<?php

require_once __DIR__ . '/../../core/Controller.php';

// Controller untuk modul manajemen tipe akun
class AccountTypeController extends Controller {

    // Validasi sesi autentikasi pengguna
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'index.php?url=auth');
            exit;
        }
    }

    // Menampilkan daftar tipe akun (beserta pencarian)
    public function index() {
        $this->checkAuth();

        $keyword = trim($_GET['q'] ?? '');
        $model = $this->model('AccountType');

        // Ambil pesan dari proses sebelumnya (jika ada), lalu hapus agar hanya tampil sekali
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        $this->view('account_type/index', [
            'title' => 'Tipe Akun',
            'activeMenu' => 'account_type',
            'accountTypes' => $model->getAccountType($keyword),
            'keyword' => $keyword,
            'flash' => $flash,
            'isAdmin' => $this->isAdmin()
        ]);
    }

    // Menampilkan form tambah tipe akun
    public function create() {
        $this->checkAuth();
        $this->checkAdmin('accountType');

        $this->view('account_type/form', [
            'title' => 'Tambah Tipe Akun',
            'activeMenu' => 'account_type',
            'accountType' => null
        ]);
    }

    // Menyimpan tipe akun baru
    public function store() {
        $this->checkAuth();
        $this->checkAdmin('accountType');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if ($name === '') {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Nama tipe akun wajib diisi.'];
            } else {
                $model = $this->model('AccountType');
                $model->createAccountType(['name' => $name, 'description' => $description]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Tipe akun berhasil ditambahkan.'];
            }
        }

        header('Location: ' . BASE_URL . 'index.php?url=accountType');
        exit;
    }

    // Menampilkan form ubah tipe akun
    public function edit($id = null) {
        $this->checkAuth();
        $this->checkAdmin('accountType');

        $model = $this->model('AccountType');
        $accountType = $id ? $model->getAccountTypeById($id) : null;

        // Jika data tidak ditemukan, kembali ke daftar
        if (!$accountType) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Data tipe akun tidak ditemukan.'];
            header('Location: ' . BASE_URL . 'index.php?url=accountType');
            exit;
        }

        $this->view('account_type/form', [
            'title' => 'Ubah Tipe Akun',
            'activeMenu' => 'account_type',
            'accountType' => $accountType
        ]);
    }

    // Menyimpan perubahan tipe akun
    public function update($id = null) {
        $this->checkAuth();
        $this->checkAdmin('accountType');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if ($name === '') {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Nama tipe akun wajib diisi.'];
            } else {
                $model = $this->model('AccountType');
                $model->updateAccountType($id, ['name' => $name, 'description' => $description]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Tipe akun berhasil diperbarui.'];
            }
        }

        header('Location: ' . BASE_URL . 'index.php?url=accountType');
        exit;
    }

    // Menghapus tipe akun (soft delete), kecuali masih dipakai akun aktif
    public function delete($id = null) {
        $this->checkAuth();
        $this->checkAdmin('accountType');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $model = $this->model('AccountType');
            $used = $model->countActiveAccounts($id);

            if ($used > 0) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Tipe akun tidak dapat dihapus karena masih digunakan oleh ' . $used . ' akun aktif.'];
            } else {
                $model->deleteAccountType($id);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Tipe akun berhasil dihapus.'];
            }
        }

        header('Location: ' . BASE_URL . 'index.php?url=accountType');
        exit;
    }
}