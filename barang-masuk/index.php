<?php
$title = 'Catat Barang Masuk';

require 'functions.php';
$barang = query("SELECT 
                  tbm.id, tbm.jumlah_barang, tbm.created_at,
                  b.nama
                FROM 
                  trs_barang_masuk as tbm,
                  barang as b
                WHERE
                  tbm.barang_id = b.id
          ");

?>

<!DOCTYPE html>
<html>

<head>
  <title>Laundry App | <?= $title ?></title>
  <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
  <?php include('../sidebar.php') ?>

  <!-- Begin Content -->
  <div class="content">
    <h2><?= $title ?></h2>
    <a href="tambah.php">Tambah Data</a>
    <br />
    <br />

    <table border="1">
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Jumlah</th>
        <th>Tanggal</th>
        <!-- <th>Aksi</th> -->
      </tr>
      <?php $i = 1; ?>
      <?php foreach ($barang as $data) : ?>
        <tr>
          <td><?= $i; ?></td>
          <td><?= $data["nama"]; ?></td>
          <td><?= $data["jumlah_barang"]; ?></td>
          <td><?= $data["created_at"]; ?></td>
        </tr>
        <?php $i++; ?>
      <?php endforeach; ?>
    </table>
  </div>
  <!-- End Content -->

</body>

</html>