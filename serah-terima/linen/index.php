<?php
$title = 'Laundry App | Linen';

require 'functions.php';

//subquery
$trs_serah_terima_id = $_GET['trs_serah_terima_id'];
$linen = query("SELECT *FROM linen WHERE trs_serah_terima_id = '$trs_serah_terima_id'");

?>

<!DOCTYPE html>
<html>

<head>
  <title><?= $title ?></title>
  <link rel="stylesheet" href="../../css/styles.css">
</head>

<body>
  <?php include('../../sidebar.php') ?>

  <!-- Begin Content -->
  <div class="content">
    <h2>Daftar Linen</h2>
    <a href="tambah.php?trs_serah_terima_id=<?= $trs_serah_terima_id; ?>">Tambah Data</a>
    <br />
    <br />

    <table border="1">
      <tr>
        <th>No</th>
        <th>Jumlah Linen</th>
        <th>Keterangan</th>
        <th>Aksi</th>
      </tr>
      <?php $i = 1; ?>
      <?php foreach ($linen as $data) : ?>
        <tr>
          <td><?= $i; ?></td>
          <td><?= $data["jumlah"]; ?></td>
          <td><?= $data["keterangan"]; ?></td>
          <td>
            <a href="edit.php?id=<?= $data["id"]; ?>&trs_serah_terima_id=<?= $trs_serah_terima_id; ?>">Edit</a>
            |
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