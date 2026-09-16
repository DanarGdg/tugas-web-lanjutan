<?php

// Konfigurasi Database
$host     = "localhost";
$user     = "root";
$password = "";
$database = "latihan";

// 1. Koneksi Database
$conn = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
} else {
    echo "Koneksi berhasil!";
}

$table_name = 'mahasiswa';

$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
    `nama` varchar(20) NOT NULL,
    `nim` int(5) NOT NULL,
    `tugas` int(5) NOT NULL,
    `uts` int(5) NOT NULL,
    `uas` int(5) NOT NULL,
    PRIMARY KEY (`nim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";


$query = mysqli_query($conn, $sql);

if (!$query) {
    die('ERROR: Tabel ' . $table_name . ' gagal dibuat: ' . mysqli_error($conn));
}

echo 'Tabel ' . $table_name . ' berhasil dibuat <br/>';

$sql = "INSERT INTO `$table_name` (`nama`, `nim`, `tugas`, `uts`, `uas`)
        VALUES ('Budi Santoso', 10001, 85, 80, 90),
               ('Siti Aminah', 10002, 90, 85, 88),
               ('Andi Wijaya', 10003, 75, 70, 80),
               ('Dewi Lestari', 10004, 88, 92, 95),
               ('Eko Prasetyo', 10005, 80, 78, 85)
        ON DUPLICATE KEY UPDATE 
            `nama` = VALUES(`nama`),
            `tugas` = VALUES(`tugas`),
            `uts` = VALUES(`uts`),
            `uas` = VALUES(`uas`);";

$query = mysqli_query($conn, $sql);

if (!$query) {
    die('ERROR: Data gagal dimasukkan pada tabel ' . $table_name . ' : ' . mysqli_error($conn));
}
echo 'Data berhasil dimasukkan pada tabel ' . $table_name . ' ';

$sql = "SELECT nim, nama, tugas, uts, uas, (tugas + uts + uas)/3 AS nilai_akhir FROM `$table_name`";
$query = mysqli_query($conn, $sql);

if (!$query) {
    die('SQL Error: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Menampilkan Data Mahasiswa</title>
    <style>
        body { font-family: tahoma, arial; }
        table { border-collapse: collapse; width: 100%; }
        th, td { font-size: 13px; border: 1px solid #DEDEDE; padding: 6px 10px; color: #303030; }
        th { background: #CCCCCC; font-size: 12px; border-color: #B0B0B0; }
        .center { text-align: center; }
    </style>
</head>
<body>

<table>
    <thead>
        <tr>
            <th>NIM</th>
            <th>NAMA</th>
            <th>TUGAS</th>
            <th>UTS</th>
            <th>UAS</th>
            <th>NILAI AKHIR</th>
        </tr>
    </thead>
    <tbody>
    <?php
    while ($row = mysqli_fetch_array($query)) {
        echo '<tr>
                <td class="center">'.$row['nim'].'</td>
                <td>'.$row['nama'].'</td>
                <td class="center">'.$row['tugas'].'</td>
                <td class="center">'.$row['uts'].'</td>
                <td class="center">'.$row['uas'].'</td>
                <td class="center">'.$row['nilai_akhir'].'</td>
            </tr>';
    }
    ?>
    </tbody>
</table>

</body>
</html>

<?php
// Bebaskan hasil memori query
mysqli_free_result($query);

// Tutup koneksi database
mysqli_close($conn);
?>