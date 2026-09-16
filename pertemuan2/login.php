<?php
$username_benar = 'danar';
$password_benar = '2507411056';
$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = $_POST['username'] ?? '';
	$password = $_POST['password'] ?? '';

	if ($username === $username_benar && $password === $password_benar) {
		$pesan = 'Selamat datang Admin';
	} else {
		$pesan = 'Login gagal';
	}
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<title>Form Login</title>
</head>
<body>
	<h2>Form Login</h2>

	<form method="post" action="">
		<label for="username">Username:</label>
		<input type="text" id="username" name="username" required>
		<br><br>

		<label for="password">Password:</label>
		<input type="password" id="password" name="password" required>
		<br><br>

		<button type="submit">Login</button>
	</form>

	<?php if ($pesan !== ''): ?>
		<p><?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8') ?></p>
	<?php endif; ?>
</body>
</html>
