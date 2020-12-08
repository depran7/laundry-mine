<?php
$title = 'Laundry App | Linen';

require 'functions.php';

//subquery
$trs_serah_terima_id = $_GET['trs_serah_terima_id'];
$linen = query("SELECT 
    linen.*,
    spesifikasi.nama as spesifikasi,
    jenis_linen.nama as jenis_linen,
    trs_serah_terima.status
  FROM 
    linen, spesifikasi, jenis_linen,trs_serah_terima
  WHERE 
    linen.spesifikasi_id = spesifikasi.id
    AND
    linen.jenis_linen_id = jenis_linen.id
    AND
    linen.trs_serah_terima_id = trs_serah_terima.id
    AND
    trs_serah_terima_id = '$trs_serah_terima_id'
");
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

    <?php if (!is_role('admin')) : ?>
      <a href="tambah.php?trs_serah_terima_id=<?= $trs_serah_terima_id; ?>">Tambah Data</a>
    <?php endif ?>
    <br />
    <br />

    <table border="1">
      <tr>
        <th rowspan="2">No</th>
        <th rowspan="2">Jenis Linen</th>
        <th rowspan="2">Spesifikasi Linen</th>
        <th colspan="2">Hitung Lidi</th>
        <th colspan="2">Jumlah Linen</th>
        <th colspan="2">Keterangan</th>
        <th rowspan="2">Aksi</th>
      </tr>
      <tr>
        <th>Dari Ruangan</th>
        <th>Dari Laundry</th>
        <th>Dari Ruangan</th>
        <th>Dari Laundry</th>
        <th>Dari Ruangan</th>
        <th>Dari Laundry</th>
      </tr>
      <?php $i = 1; ?>
      <?php foreach ($linen as $data) : ?>
        <tr>
          <td><?= $i; ?></td>
          <td><?= $data["spesifikasi"]; ?></td>
          <td><?= $data["jenis_linen"]; ?></td>
          <td><?= $data["hitung_lidi"]; ?></td>
          <td><?= $data["hitung_lidi_laundry"]; ?></td>
          <td><?= $data["jumlah"]; ?></td>
          <td><?= $data["jumlah_laundry"]; ?></td>
          <td><?= $data["keterangan"]; ?></td>
          <td><?= $data["keterangan_laundry"]; ?></td>
          <td>
            <?php if (is_role('admin')) : ?>
              <?php if ($data["status"] != 4) : ?>
                <a href="edit_jumlah.php?id=<?= $data["id"]; ?>&trs_serah_terima_id=<?= $trs_serah_terima_id; ?>">Edit</a>
              <?php endif ?>
            <?php else : ?>
              <?php if (empty($data["status"])) : ?>
                <a href="edit.php?id=<?= $data["id"]; ?>&trs_serah_terima_id=<?= $trs_serah_terima_id; ?>">Edit</a>
                |
                <a href="hapus.php?id=<?= $data["id"]; ?>" onclick="return confirm('yakin?');">hapus</a>
              <?php endif ?>
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