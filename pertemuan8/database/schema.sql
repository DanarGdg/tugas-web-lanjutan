-- Create Database
CREATE DATABASE IF NOT EXISTS `PBL_TI_2025_C_KELOMPOK5` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `PBL_TI_2025_C_KELOMPOK5`;

-- Table 1: account_type
CREATE TABLE IF NOT EXISTS `account_type` (
    `id` CHAR(36) NOT NULL,
    `name` VARCHAR(128) NOT NULL,
    `description` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table 2: actions
CREATE TABLE IF NOT EXISTS `actions` (
    `id` CHAR(36) NOT NULL,
    `name` VARCHAR(128) NOT NULL,
    `description` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table 3: accounts
CREATE TABLE IF NOT EXISTS `accounts` (
    `id` CHAR(36) NOT NULL,
    `name` VARCHAR(128) NOT NULL,
    `email` VARCHAR(128) NOT NULL,
    `password` TEXT NOT NULL,
    `account_type_id` CHAR(36) NULL,
    `status` VARCHAR(128) NOT NULL,
    `identification_number` VARCHAR(128) NOT NULL,
    `identification_type` ENUM('NIM', 'NIP') NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_accounts_identification_number` (`identification_number`),
    CONSTRAINT `fk_accounts_account_type` 
        FOREIGN KEY (`account_type_id`) 
        REFERENCES `account_type` (`id`) 
        ON DELETE SET NULL 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `account_type` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
('550e8400-e29b-41d4-a716-446655440001', 'Admin', 'Tipe akun dengan hak akses penuh ke seluruh sistem.', NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440002', 'Dosen', 'Tipe akun pengajar/dosen untuk mengakses fitur akademik.', NOW(), NOW()),
('550e8400-e29b-41d4-a716-446655440003', 'Mahasiswa', 'Tipe akun mahasiswa untuk mengakses layanan perkuliahan.', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Seeders untuk tabel actions (Tipe Aksi / Akses)
INSERT INTO `actions` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
('6ba7b810-9dad-11d1-80b4-00c04fd43001', 'Tambah Data', 'Aksi untuk membuat data baru pada sistem.', NOW(), NOW()),
('6ba7b810-9dad-11d1-80b4-00c04fd43002', 'Ubah Data', 'Aksi untuk memperbarui data yang sudah ada.', NOW(), NOW()),
('6ba7b810-9dad-11d1-80b4-00c04fd43003', 'Hapus Data', 'Aksi untuk menghapus/soft delete data.', NOW(), NOW()),
('6ba7b810-9dad-11d1-80b4-00c04fd43004', 'Cari Data', 'Aksi untuk melakukan pencarian atau pemfilteran data.', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Seeders untuk tabel accounts
-- Note: Password default adalah 'password123' (bcrypt hash)
INSERT INTO `accounts` (`id`, `name`, `email`, `password`, `account_type_id`, `status`, `identification_number`, `identification_type`, `created_at`, `updated_at`) VALUES
('7c9e6679-7425-40de-944b-e07fc1f90ae7', 'Administrator', 'admin@pnj.ac.id', 'admin123', '550e8400-e29b-41d4-a716-446655440001', 'Aktif', '5200000000000746', 'NIP', NOW(), NOW()),
('a3c10b8d-2b4f-4d92-81e5-6b586071012a', 'Danar Gading', 'danar@pnj.ac.id', 'user123', '550e8400-e29b-41d4-a716-446655440002', 'Aktif', '199208152020121001', 'NIP', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
