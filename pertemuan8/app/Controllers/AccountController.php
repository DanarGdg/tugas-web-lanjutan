<?php

require_once __DIR__ . '/../../core/Controller.php';

// Controller untuk modul manajemen akun pengguna
class AccountController extends Controller {

    // Validasi sesi autentikasi pengguna
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'index.php?url=auth');
            exit;
        }
    }

    // Menampilkan daftar seluruh akun pengguna (dengan fitur pencarian server-side)
    public function index() {
        $this->checkAuth();
        
        $keyword = trim($_GET['q'] ?? '');
        $accountModel = $this->model('Account');

        $accounts = $accountModel->getAccounts($keyword);

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        $data = [
            'title' => 'Manajemen Akun',
            'activeMenu' => 'account',
            'accounts' => $accounts,
            'keyword' => $keyword,
            'flash' => $flash,
            'isAdmin' => $this->isAdmin()
        ];

        $this->view('account/index', $data);
    }

    // Menampilkan halaman form untuk menambah akun baru
    public function create() {
        $this->checkAuth();
        $this->checkAdmin('account');

        $accountTypeModel = $this->model('AccountType');
        
        $accountTypes = $accountTypeModel->getAccountType();

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        $data = [
            'title' => 'Tambah Akun Baru',
            'activeMenu' => 'account',
            'account' => null,
            'accountTypes' => $accountTypes,
            'flash' => $flash
        ];

        $this->view('account/form', $data);
    }

    // Menyimpan data akun baru
    public function store() {
        $this->checkAuth();
        $this->checkAdmin('account');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?url=account');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $accountTypeId = $_POST['account_type_id'] ?? '';
        $status = $_POST['status'] ?? 'Aktif';
        $identificationNumber = trim($_POST['identification_number'] ?? '');
        $identificationType = $_POST['identification_type'] ?? 'NIP';

        if (empty($name) || empty($email) || empty($password) || empty($identificationNumber) || empty($accountTypeId)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Semua field wajib diisi.'];
            header('Location: ' . BASE_URL . 'index.php?url=account/create');
            exit;
        }

        $accountModel = $this->model('Account');

        // Cek duplikasi email pengguna
        if ($accountModel->findByEmail($email)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Email sudah digunakan oleh akun lain.'];
            header('Location: ' . BASE_URL . 'index.php?url=account/create');
            exit;
        }

        // cek duplikasi nomor identifikasi pengguna
        if ($accountModel->findByIdentificationNumber($identificationNumber)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Nomor identifikasi sudah digunakan oleh akun lain.'];
            header('Location: ' . BASE_URL . 'index.php?url=account/create');
            exit;
        }

        $created = $accountModel->createAccount([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'account_type_id' => $accountTypeId,
            'status' => $status,
            'identification_number' => $identificationNumber,
            'identification_type' => $identificationType
        ]);

        if ($created) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Akun baru berhasil ditambahkan!'];
            header('Location: ' . BASE_URL . 'index.php?url=account');
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal menambahkan akun baru karena kendala basis data.'];
            header('Location: ' . BASE_URL . 'index.php?url=account/create');
        }
        exit;
    }

    // Menampilkan halaman form untuk mengubah data akun
    public function edit($id = null) {
        $this->checkAuth();
        $this->checkAdmin('account');

        if (empty($id)) {
            header('Location: ' . BASE_URL . 'index.php?url=account');
            exit;
        }

        $accountModel = $this->model('Account');
        $account = $accountModel->getAccountById($id);

        if (!$account) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Data akun tidak ditemukan.'];
            header('Location: ' . BASE_URL . 'index.php?url=account');
            exit;
        }

        $accountTypeModel = $this->model('AccountType');
        $accountTypes = $accountTypeModel->getAccountType();

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        $data = [
            'title' => 'Ubah Data Akun',
            'activeMenu' => 'account',
            'account' => $account,
            'accountTypes' => $accountTypes,
            'flash' => $flash
        ];

        $this->view('account/form', $data);
    }

    // Memperbarui data akun pengguna
    public function update($id = null) {
        $this->checkAuth();
        $this->checkAdmin('account');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($id)) {
            header('Location: ' . BASE_URL . 'index.php?url=account');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $accountTypeId = $_POST['account_type_id'] ?? '';
        $status = $_POST['status'] ?? 'Aktif';
        $identificationNumber = trim($_POST['identification_number'] ?? '');
        $identificationType = $_POST['identification_type'] ?? 'NIP';

        if (empty($name) || empty($email) || empty($identificationNumber) || empty($accountTypeId)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Semua field wajib diisi.'];
            header('Location: ' . BASE_URL . 'index.php?url=account/edit/' . $id);
            exit;
        }

        $accountModel = $this->model('Account');

        // Cek jika email diganti dan ternyata duplikat
        $existing = $accountModel->findByEmail($email);
        if ($existing && $existing['id'] !== $id) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Email sudah digunakan oleh akun lain.'];
            header('Location: ' . BASE_URL . 'index.php?url=account/edit/' . $id);
            exit;
        }

        // Cek jika nomor identifikasi diganti dan ternyata duplikat
        if ($accountModel->findByIdentificationNumber($identificationNumber, $id)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Nomor identifikasi sudah digunakan oleh akun lain.'];
            header('Location: ' . BASE_URL . 'index.php?url=account/edit/' . $id);
            exit;
        }

        $updated = $accountModel->updateAccount($id, [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'account_type_id' => $accountTypeId,
            'status' => $status,
            'identification_number' => $identificationNumber,
            'identification_type' => $identificationType
        ]);

        if ($updated) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Data akun berhasil diperbarui!'];
            header('Location: ' . BASE_URL . 'index.php?url=account');
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal memperbarui data akun karena kendala basis data.'];
            header('Location: ' . BASE_URL . 'index.php?url=account/edit/' . $id);
        }
        exit;
    }

    // Menghapus data akun (soft delete)
    public function delete($id = null) {
        $this->checkAuth();
        $this->checkAdmin('account');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($id)) {
            header('Location: ' . BASE_URL . 'index.php?url=account');
            exit;
        }

        $accountModel = $this->model('Account');
        $deleted = $accountModel->deleteAccount($id);

        if ($deleted) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Akun berhasil dihapus!'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal menghapus akun.'];
        }

        header('Location: ' . BASE_URL . 'index.php?url=account');
        exit;
    }
}
