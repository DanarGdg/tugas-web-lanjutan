<?php

require_once __DIR__ . '/../../core/Model.php';

// Model untuk tabel accounts
class Account extends Model {
    protected $table = 'accounts';

    // Mengambil seluruh data akun aktif beserta relasi tipe akun (dukungan pencarian server-side)
    public function getAccounts($keyword = '') {
        if (!$this->db) return [];

        try {
            $sql = "SELECT a.*, t.name as account_type_name 
                    FROM accounts a 
                    LEFT JOIN account_type t ON a.account_type_id = t.id 
                    WHERE a.deleted_at IS NULL";
            
            $params = [];

            if (!empty($keyword)) {
                $sql .= " AND (a.name LIKE :kw1 OR a.email LIKE :kw2 OR a.identification_number LIKE :kw3)";
                $term = '%' . $keyword . '%';
                $params['kw1'] = $term;
                $params['kw2'] = $term;
                $params['kw3'] = $term;
            }

            $sql .= " ORDER BY a.created_at DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    // Mengambil satu data akun berdasarkan ID
    public function getAccountById($id) {
        if (!$this->db) return null;
        try {
            $stmt = $this->db->prepare("SELECT a.*, t.name as account_type_name 
                                        FROM accounts a 
                                        LEFT JOIN account_type t ON a.account_type_id = t.id 
                                        WHERE a.id = :id AND a.deleted_at IS NULL");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return null;
        }
    }

    // Mencari akun aktif berdasarkan nomor identifikasi untuk validasi keunikan
    public function findByIdentificationNumber($identificationNumber, $excludeAccountId = null) {
        if (!$this->db || empty($identificationNumber)) return null;

        try {
            $sql = "SELECT id FROM accounts
                    WHERE identification_number = :identification_number
                    AND deleted_at IS NULL";
            $params = ['identification_number' => $identificationNumber];

            if (!empty($excludeAccountId)) {
                $sql .= " AND id != :exclude_id";
                $params['exclude_id'] = $excludeAccountId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return null;
        }
    }

    // Menambahkan data akun baru ke database
    public function createAccount($data) {
        if (!$this->db) return false;
        
        try {
            $id = self::generateUuid();
            $stmt = $this->db->prepare("INSERT INTO accounts (id, name, email, password, account_type_id, status, identification_number, identification_type, created_at, updated_at) 
                                        VALUES (:id, :name, :email, :password, :account_type_id, :status, :identification_number, :identification_type, NOW(), NOW())");
            return $stmt->execute([
                'id' => $id,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_BCRYPT),
                'account_type_id' => $data['account_type_id'],
                'status' => $data['status'],
                'identification_number' => $data['identification_number'],
                'identification_type' => $data['identification_type']
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    // Memperbarui data akun berdasarkan ID
    public function updateAccount($id, $data) {
        if (!$this->db) return false;

        try {
            $sql = "UPDATE accounts 
                    SET name = :name, email = :email, account_type_id = :account_type_id, status = :status, identification_number = :identification_number, identification_type = :identification_type, updated_at = NOW()";
            
            $params = [
                'id' => $id,
                'name' => $data['name'],
                'email' => $data['email'],
                'account_type_id' => $data['account_type_id'],
                'status' => $data['status'],
                'identification_number' => $data['identification_number'],
                'identification_type' => $data['identification_type']
            ];

            // Jika password diisi, update password
            if (!empty($data['password'])) {
                $sql .= ", password = :password";
                $params['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            }

            $sql .= " WHERE id = :id AND deleted_at IS NULL";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (\PDOException $e) {
            return false;
        }
    }

    // Menghapus data akun secara soft delete (mengisi kolom deleted_at)
    public function deleteAccount($id) {
        if (!$this->db) return false;
        try {
            $stmt = $this->db->prepare("UPDATE accounts SET deleted_at = NOW(), account_type_id = NULL WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    // Mencari data akun berdasarkan email untuk proses autentikasi
    public function findByEmail($email) {
        if (!$this->db) return null;
        try {
            $stmt = $this->db->prepare("SELECT a.*, t.name as account_type_name 
                                        FROM accounts a 
                                        LEFT JOIN account_type t ON a.account_type_id = t.id 
                                        WHERE a.email = :email AND a.deleted_at IS NULL");
            $stmt->execute(['email' => $email]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return null;
        }
    }


}
