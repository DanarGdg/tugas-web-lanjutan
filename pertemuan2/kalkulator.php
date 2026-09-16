<?php
$angka1 = $_POST['angka1'] ?? '';
$angka2 = $_POST['angka2'] ?? '';
$operasi = $_POST['operasi'] ?? '+';
$hasil = null;
$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ($angka1 === '' || $angka2 === '' || !is_numeric($angka1) || !is_numeric($angka2)) {
		$pesan = 'Masukkan dua angka yang valid.';
	} elseif (!in_array($operasi, ['+', '-', '*', '/'], true)) {
		$pesan = 'Operasi tidak valid.';
	} elseif ($operasi === '/' && (float) $angka2 === 0.0) {
		$pesan = 'Angka kedua tidak boleh nol untuk pembagian.';
	} else {
		switch ($operasi) {
			case '+':
				$hasil = $angka1 + $angka2;
				break;
			case '-':
				$hasil = $angka1 - $angka2;
				break;
			case '*':
				$hasil = $angka1 * $angka2;
				break;
			case '/':
				$hasil = $angka1 / $angka2;
				break;
		}
	}
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<title>Kalkulator Sederhana</title>
</head>
<body>
	<h1>Kalkulator Sederhana</h1>

	<form method="post">
		<label for="angka1">Angka pertama:</label>
		<input type="number" id="angka1" name="angka1" step="any" value="<?= htmlspecialchars((string) $angka1) ?>" required>

		<br><br>

		<label for="operasi">Operasi:</label>
		<select id="operasi" name="operasi">
			<option value="+" <?= $operasi === '+' ? 'selected' : '' ?>>+</option>
			<option value="-" <?= $operasi === '-' ? 'selected' : '' ?>>-</option>
			<option value="*" <?= $operasi === '*' ? 'selected' : '' ?>>*</option>
			<option value="/" <?= $operasi === '/' ? 'selected' : '' ?>>/</option>
		</select>

		<br><br>

		<label for="angka2">Angka kedua:</label>
		<input type="number" id="angka2" name="angka2" step="any" value="<?= htmlspecialchars((string) $angka2) ?>" required>

		<br><br>

		<button type="submit">Hitung</button>
	</form>

	<?php if ($pesan !== ''): ?>
		<p><?= htmlspecialchars($pesan) ?></p>
	<?php elseif ($hasil !== null): ?>
		<h2>Hasil: <?= htmlspecialchars((string) $hasil) ?></h2>
	<?php endif; ?>
</body>
</html>