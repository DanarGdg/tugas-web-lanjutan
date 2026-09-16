<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Perkenalan Diri</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .container { max-width: 450px; }
        .form-group { margin-bottom: 12px; }
        label { display: block; font-weight: bold; margin-bottom: 4px; }
        input { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 8px 16px; background-color: #007bff; color: white; border: none; cursor: pointer; }
        .result { margin-top: 24px; padding: 16px; border: 1px solid #ddd; background-color: #f9f9f9; }
    </style>
</head>
<body>

<div class="container">
    <h2>Form Perkenalan Diri</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" required>
        </div>
        <div class="form-group">
            <label for="nim">NIM</label>
            <input type="text" id="nim" name="nim" required>
        </div>
        <div class="form-group">
            <label for="semester">Semester</label>
            <input type="number" id="semester" name="semester" required>
        </div>
        <div class="form-group">
            <label for="prodi">Program Studi</label>
            <input type="text" id="prodi" name="prodi" required>
        </div>
        <div class="form-group">
            <label for="umur">Umur</label>
            <input type="number" id="umur" name="umur" required>
        </div>
        <div class="form-group">
            <label for="hobi">Hobi</label>
            <input type="text" id="hobi" name="hobi" required>
        </div>
        <div class="form-group">
            <label for="cita_cita">Cita-cita</label>
            <input type="text" id="cita_cita" name="cita_cita" required>
        </div>
        <button type="submit" name="submit">Kirim Data</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nama      = htmlspecialchars($_POST['nama']);
        $nim       = htmlspecialchars($_POST['nim']);
        $semester  = htmlspecialchars($_POST['semester']);
        $prodi     = htmlspecialchars($_POST['prodi']);
        $umur      = htmlspecialchars($_POST['umur']);
        $hobi      = htmlspecialchars($_POST['hobi']);
        $cita_cita = htmlspecialchars($_POST['cita_cita']);
    ?>
        <div class="result">
            <h3>Data Perkenalan Diri:</h3>
            <p><strong>Nama:</strong> <?= $nama; ?></p>
            <p><strong>NIM:</strong> <?= $nim; ?></p>
            <p><strong>Semester:</strong> <?= $semester; ?></p>
            <p><strong>Program Studi:</strong> <?= $prodi; ?></p>
            <p><strong>Umur:</strong> <?= $umur; ?> tahun</p>
            <p><strong>Hobi:</strong> <?= $hobi; ?></p>
            <p><strong>Cita-cita:</strong> <?= $cita_cita; ?></p>
        </div>
    <?php } ?>
</div>

</body>
</html>