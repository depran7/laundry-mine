<?php
$title = 'Laundry App | Serah Terima';

require 'functions.php';

//subquery
$jumlah_linen = "SELECT SUM(l.jumlah)
  FROM 
    linen as l
  WHERE
    tst.id = l.trs_serah_terima_id";

$serah_terima = query("SELECT 
              tst.*,
              r.nama as nama_ruangan,
              ($jumlah_linen) as jumlah_linen
            FROM 
              trs_serah_terima as tst
            INNER JOIN ruangan as r ON tst.ruangan_id = r.id
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
    <h2>Serah Terima</h2>
    <?php if (is_role('penyerahan')) : ?>
      <a href="tambah.php">Tambah Data</a>
    <?php endif ?>
    <br />
    <br />

    <table border="1">
      <tr>
        <th>No</th>
        <th>No Transaksi</th>
        <th>Ruangan</th>
        <th>Status</th>
        <th>Tanggal Pengiriman</th>
        <th>Jumlah Linen</th>
        <th>Aksi</th>
      </tr>
      <?php $i = 1; ?>
      <?php foreach ($serah_terima as $data) : ?>
        <tr>
          <td><?= $i; ?></td>
          <td><?= $data["no_trs"]; ?></td>
          <td><?= $data["nama_ruangan"]; ?></td>
          <td><?= $data["status"] ? status($data["status"]) : 'Belum Dikirim' ?></td>
          <td><?= $data["tanggal_pengiriman"] ? $data["tanggal_pengiriman"] : '-'; ?></td>
          <td><?= $data["jumlah_linen"]; ?></td>
          <td>
            <a href="linen/index.php?trs_serah_terima_id=<?= $data["id"]; ?>">Lihat Linen</a>
            <?php if ($data["status"] == NULL || $data["status"] == 0) : ?>
              |
              <?php if (is_role('admin')) : ?>
                <a href="serah.php?id=<?= $data["id"]; ?>" onclick="return confirm('yakin?');">Serahkan</a>
                |
              <?php endif ?>
              <a href="edit.php?id=<?= $data["id"]; ?>">Edit</a>
              |
              <a href="hapus.php?id=<?= $data["id"]; ?>" onclick="return confirm('yakin?');">hapus</a>
            <?php endif ?>
          </td>
        </tr>
        <?php $i++; ?>
      <?php endforeach; ?>
    </table>
  </div>
  <!-- End Content -->

</body>

</html>