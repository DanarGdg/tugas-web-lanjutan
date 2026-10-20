<?php

require_once __DIR__ . '/../../core/Model.php';

// Model untuk tabel account_type
class AccountType extends Model {
    protected $table = 'account_type';

    // Mengambil seluruh data tipe akun yang aktif 
    public function getAccountType($keyword = '') {
        if (!$this->db) return [];

        $sql = "SELECT * FROM account_type WHERE deleted_at IS NULL";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND (name LIKE :kw1 OR description LIKE :kw2)";
            $params['kw1'] = '%' . $keyword . '%';
            $params['kw2'] = '%' . $keyword . '%';
        }

        $sql .= " ORDER BY created_at ASC, name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Mengambil satu data tipe akun berdasarkan ID
    public function getAccountTypeById($id) {
        if (!$this->db) return null;
        $stmt = $this->db->prepare("SELECT * FROM account_type WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Menambahkan data tipe akun baru ke database
    public function createAccountType($data) {
        if (!$this->db) return false;
        $id = self::generateUuid();
        $stmt = $this->db->prepare("INSERT INTO account_type (id, name, description, created_at, updated_at) 
                                    VALUES (:id, :name, :description, NOW(), NOW())");
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null
        ]);
    }

    // Memperbarui data tipe akun berdasarkan ID
    public function updateAccountType($id, $data) {
        if (!$this->db) return false;
        $stmt = $this->db->prepare("UPDATE account_type 
                                    SET name = :name, description = :description, updated_at = NOW() 
                                    WHERE id = :id AND deleted_at IS NULL");
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null
        ]);
    }

    // Menghapus data tipe akun secara soft delete (mengisi kolom deleted_at)
    public function deleteAccountType($id) {
        if (!$this->db) return false;
        $stmt = $this->db->prepare("UPDATE account_type SET deleted_at = NOW() WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Menghitung akun aktif yang masih memakai tipe akun ini
    public function countActiveAccounts($id) {
        if (!$this->db) return 0;
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM accounts WHERE account_type_id = :id AND deleted_at IS NULL");
        $stmt->execute(['id' => $id]);
        return (int) $stmt->fetchColumn();
    }

    // Mengambil ID tipe akun yang tersedia atau membuat default Mahasiswa jika tabel masih kosong
    public function getOrCreateDefaultTypeId() {
        if (!$this->db) return null;
        $types = $this->getAccountType();
        if (!empty($types)) {
            return $types[0]['id'];
        }

        $id = self::generateUuid();
        $stmt = $this->db->prepare("INSERT INTO account_type (id, name, description, created_at, updated_at) 
                                    VALUES (:id, 'Mahasiswa', 'Akun Pengguna Mahasiswa', NOW(), NOW())");
        $stmt->execute(['id' => $id]);
        return $id;
    }
}