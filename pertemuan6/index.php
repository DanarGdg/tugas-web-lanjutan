<?php
require __DIR__ . '/koneksi.php';

$berita = mysqli_query($conn, 'SELECT * FROM berita ORDER BY tanggal DESC, id DESC');

$pesan = $_GET['pesan'] ?? '';

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Berita</title>
    <style>
        :root {
            --blue: #087cf5;
            --ink: #172033;
            --muted: #687386;
            --line: #e5eaf0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: var(--ink);
            font: 15px Arial, sans-serif;
            background: linear-gradient(135deg, #f4fbff, #eef8ef);
        }

        .nav {
            max-width: 1100px;
            margin: 0 auto;
            padding: 14px 22px;
            background: #fff;
            display: flex;
            gap: 24px;
            align-items: center;
            box-shadow: 0 1px 10px #16324d12;
        }

        .brand {
            font-size: 18px;
        }

        .nav a {
            color: #778294;
            text-decoration: none;
        }

        .nav a:hover,
        .nav a.active {
            color: var(--blue);
        }

        main {
            max-width: 1100px;
            margin: 28px auto;
            padding: 0 22px 40px;
        }

        .heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
        }

        h1 {
            margin: 0 0 22px;
            font-size: 28px;
        }

        .button {
            display: inline-block;
            padding: 11px 17px;
            border: 0;
            border-radius: 4px;
            background: var(--blue);
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .notice {
            margin-bottom: 18px;
            padding: 12px 15px;
            border-left: 4px solid #16a56a;
            background: #e9fff4;
            color: #176a4b;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        article {
            overflow: hidden;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 6px;
            box-shadow: 0 8px 25px #16324d0b;
        }

        article img,
        .no-image {
            width: 100%;
            height: 175px;
            object-fit: cover;
            background: #edf2f5;
        }

        .no-image {
            display: grid;
            place-items: center;
            color: #99a5b1;
        }

        .content {
            padding: 18px;
        }

        h2 {
            margin: 0 0 9px;
            font-size: 20px;
        }

        .meta {
            margin: 0 0 13px;
            color: var(--muted);
            font-size: 13px;
        }

        .excerpt {
            color: #4e5b6c;
            line-height: 1.55;
            white-space: pre-line;
        }

        .actions {
            display: flex;
            gap: 8px;
            margin-top: 16px;
        }

        .actions a,
        .actions button {
            padding: 8px 11px;
            border-radius: 3px;
            border: 0;
            text-decoration: none;
            cursor: pointer;
            font-size: 13px;
        }

        .edit {
            background: #e7f2ff;
            color: #0669d3;
        }

        .delete {
            background: #fff0f0;
            color: #d63b3b;
        }

        .empty {
            padding: 45px;
            background: #fff;
            text-align: center;
            color: var(--muted);
        }

        @media (max-width: 600px) {
            .heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .nav {
                gap: 15px;
            }
        }
    </style>
</head>

<body>
    <nav class="nav">
        <strong class="brand">Portal Berita</strong>
        <a class="active" href="index.php">Home</a>
        <a href="tambah.php">Input Berita</a>
    </nav>
    <main>
        <div class="heading">
            <h1>Berita Terbaru</h1><a class="button" href="tambah.php">+ Tambah Berita</a>
        </div>

        <?php
        if ($pesan):
        ?>
            <div class="notice">
                <?= e($pesan) ?>
            </div>
        <?php endif; ?>

        <section class="grid">
            <?php
            if (mysqli_num_rows($berita) === 0):
            ?>
                <div class="empty">Belum ada berita. Silakan tambahkan berita pertama.</div>
            <?php
            endif;
            ?>

            <?php while ($row = mysqli_fetch_assoc($berita)): ?>
                <article>
                    <?php if ($row['gambar']): ?>

                        <img src="gambar/<?= e($row['gambar']) ?>" alt="<?= e($row['judul']) ?>">

                    <?php else: ?>
                        <div class="no-image">Tidak ada gambar</div>

                    <?php endif; ?>

                    <div class="content">
                        <h2><?= e($row['judul']) ?></h2>

                        <p class="meta">Oleh <?= e($row['penulis']) ?> · <?= date('d/m/Y', strtotime($row['tanggal'])) ?></p>

                        <div class="excerpt"><?= e(strlen($row['isi']) > 180 ? substr($row['isi'], 0, 180) . '...' : $row['isi']) ?></div>

                        <div class="actions"><a class="edit" href="edit.php?id=<?= (int) $row['id'] ?>">Edit</a>
                            <form method="post" action="hapus.php" onsubmit="return confirm('Hapus berita ini?');">
                                <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                                
                                <button class="delete" type="submit">Hapus</button>
                            </form>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </section>
    </main>
</body>

</html>