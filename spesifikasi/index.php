<?php
$title = 'Laundry App | Spesifikasi';

require 'functions.php';
$spesifikasi = query("SELECT * FROM spesifikasi");

?>

<!DOCTYPE html>
<html>

<head>
  <title><?= $title ?></title>
  <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
  <?php include('../sidebar.php') ?>

  <!-- Begin Content -->
  <div class="content">
    <h2>Daftar Spesifikasi</h2>
    <a href="tambah.php">Tambah Data</a>
    <br />
    <br />

    <table border="1">
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Aksi</th>
      </tr>
      <?php $i = 1; ?>
      <?php foreach ($spesifikasi as $data) : ?>
        <tr>
          <td><?= $i; ?></td>
          <td><?= $data["nama"]; ?></td>
          <td>
            <a href="edit.php?id=<?= $data["id"]; ?>">Edit</a>
            <a href="hapus.php?id=<?= $data["id"]; ?>"  onclick="return confirm('yakin?');">hapus</a>
          </td>
        </tr>
        <?php $i++; ?>
      <?php endforeach; ?>
    </table>
  </div>
  <!-- End Content -->

</body>

</html>