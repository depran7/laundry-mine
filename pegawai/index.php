<?php
$title = 'Laundry App | Pegawai';

require 'functions.php';
$pegawai = query("SELECT * FROM pegawai");

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
    <h2>Daftar Pegawai</h2>
    <p class="text-danger">Perhatian: untuk password default itu sama dengan nip, beritahu pegawai agar segera mengganti password nya</p>
    <a href="tambah.php">Tambah Data</a>
    <br />
    <br />
    <table border="1">
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Nip</th>
        <th>Aksi</th>
      </tr>
      <?php $i = 1; ?>
      <?php foreach ($pegawai as $data) : ?>
        <tr>
          <td><?= $i; ?></td>
          <td><?= $data["nama"]; ?></td>
          <td><?= $data["nip"]; ?></td>
          <td>
            <a href="reset_password.php?id=<?= $data["id"]; ?>"  onclick="return confirm('apakah anda yakin untuk mereset password <?= $data["nama"]; ?>?');">Reset password</a>
            |
            <a href="edit.php?id=<?= $data["id"]; ?>">Edit</a>
            |
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