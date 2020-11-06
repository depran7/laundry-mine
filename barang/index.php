<?php
$title = 'Laundry App | Barang';

require 'functions.php';
$barang = query("SELECT 
                  b.id, b.nama, b.jumlah_barang, b.harga_barang, 
                  jb.nama as jenis_barang,
                  sb.nama as satuan_barang,
                  kb.nama as kategori_barang
                FROM 
                  barang as b, 
                  jenis_barang as jb,
                  satuan_barang as sb,
                  kategori_barang as kb
                WHERE 
                  b.jenis_barang_id = jb.id
                  AND
                  b.satuan_barang_id = sb.id
                  AND
                  b.kategori_barang_id = kb.id
          ");

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
    <h2>Daftar Barang</h2>
    <a href="tambah.php">Tambah Data</a>
    <br />
    <br />

    <table border="1">
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Jumlah</th>
        <th>Harga</th>
        <th>Satuan</th>
        <th>Jenis</th>
        <th>Kategori</th>
        <th>Aksi</th>
      </tr>
      <?php $i = 1; ?>
      <?php foreach ($barang as $data) : ?>
        <tr>
          <td><?= $i; ?></td>
          <td><?= $data["nama"]; ?></td>
          <td><?= $data["jumlah_barang"]; ?></td>
          <td><?= $data["harga_barang"]; ?></td>
          <td><?= $data["satuan_barang"]; ?></td>
          <td><?= $data["jenis_barang"]; ?></td>
          <td><?= $data["kategori_barang"]; ?></td>
          <td>
            <a href="edit.php?id=<?= $data["id"]; ?>">Edit</a>
            <a href="hapus.php?id=<?= $data["id"]; ?>" onclick="return confirm('yakin?');">hapus</a>
          </td>
        </tr>
        <?php $i++; ?>
      <?php endforeach; ?>
    </table>
  </div>
  <!-- End Content -->

</body>

</html>