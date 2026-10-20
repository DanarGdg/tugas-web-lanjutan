<?php

require_once __DIR__ . '/../../core/Controller.php';

// Controller untuk modul manajemen jenis aksi
class ActionController extends Controller {

    // Validasi sesi autentikasi pengguna
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'index.php?url=auth');
            exit;
        }
    }

    // Menampilkan daftar jenis aksi (beserta pencarian)
    public function index() {
        $this->checkAuth();

        $keyword = trim($_GET['q'] ?? '');
        $model = $this->model('Action');

        // Ambil pesan dari proses sebelumnya (jika ada), lalu hapus agar hanya tampil sekali
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        $this->view('action/index', [
            'title' => 'Jenis Aksi',
            'activeMenu' => 'action',
            'actions' => $model->getActions($keyword),
            'keyword' => $keyword,
            'flash' => $flash,
            'isAdmin' => $this->isAdmin()
        ]);
    }

    // Menampilkan form tambah jenis aksi
    public function create() {
        $this->checkAuth();
        $this->checkAdmin('action');

        $this->view('action/form', [
            'title' => 'Tambah Jenis Aksi',
            'activeMenu' => 'action',
            'actionItem' => null
        ]);
    }

    // Menyimpan jenis aksi baru
    public function store() {
        $this->checkAuth();
        $this->checkAdmin('action');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if ($name === '') {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Nama aksi wajib diisi.'];
            } else {
                $model = $this->model('Action');
                $model->createAction(['name' => $name, 'description' => $description]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Jenis aksi berhasil ditambahkan.'];
            }
        }

        header('Location: ' . BASE_URL . 'index.php?url=action');
        exit;
    }

    // Menampilkan form ubah jenis aksi
    public function edit($id = null) {
        $this->checkAuth();
        $this->checkAdmin('action');

        $model = $this->model('Action');
        $actionItem = $id ? $model->getActionById($id) : null;

        // Jika data tidak ditemukan, kembali ke daftar
        if (!$actionItem) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Data jenis aksi tidak ditemukan.'];
            header('Location: ' . BASE_URL . 'index.php?url=action');
            exit;
        }

        $this->view('action/form', [
            'title' => 'Ubah Jenis Aksi',
            'activeMenu' => 'action',
            'actionItem' => $actionItem
        ]);
    }

    // Menyimpan perubahan jenis aksi
    public function update($id = null) {
        $this->checkAuth();
        $this->checkAdmin('action');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if ($name === '') {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Nama aksi wajib diisi.'];
            } else {
                $model = $this->model('Action');
                $model->updateAction($id, ['name' => $name, 'description' => $description]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Jenis aksi berhasil diperbarui.'];
            }
        }

        header('Location: ' . BASE_URL . 'index.php?url=action');
        exit;
    }

    // Menghapus jenis aksi (soft delete)
    public function delete($id = null) {
        $this->checkAuth();
        $this->checkAdmin('action');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $model = $this->model('Action');
            $model->deleteAction($id);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Jenis aksi berhasil dihapus.'];
        }

        header('Location: ' . BASE_URL . 'index.php?url=action');
        exit;
    }
}