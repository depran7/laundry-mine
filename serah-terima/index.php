<?php
$title = 'Laundry App | Serah Terima';

require 'functions.php';

//subquery
$jumlah_linen = "SELECT SUM(l.jumlah)
  FROM 
    linen as l
  WHERE
    tst.id = l.trs_serah_terima_id";

$jumlah_linen_laundry = "SELECT SUM(l.jumlah_laundry)
  FROM 
    linen as l
  WHERE
    tst.id = l.trs_serah_terima_id";

//query utama
$query_utama = "SELECT 
    tst.*,
    r.nama as nama_ruangan,
    ($jumlah_linen) as jumlah_linen,
    ($jumlah_linen_laundry) as jumlah_linen_laundry
  FROM 
    trs_serah_terima as tst
  INNER JOIN ruangan as r ON tst.ruangan_id = r.id
";

//jika yang login adalah admin maka ambil transaksi yang sudah diserahkan oleh ruangan ke laundry
if (is_role('admin')) {
  $query_utama .= " WHERE status > 0";
}

//jalankan query
$serah_terima = query($query_utama);

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
        <th rowspan="2">No</th>
        <th rowspan="2">No Transaksi</th>
        <th rowspan="2">Ruangan</th>
        <th rowspan="2">Status</th>
        <th colspan="2">Tanggal Pengiriman</th>
        <th colspan="2">Jumlah Linen</th>
        <th rowspan="2">Aksi</th>
      </tr>
      <tr>
        <th>Dari Ruangan</th>
        <th>Dari Laundry</th>
        <th>Dari Ruangan</th>
        <th>Dari Laundry</th>
      </tr>
      <?php $i = 1; ?>
      <?php foreach ($serah_terima as $data) : ?>
        <tr>
          <td><?= $i; ?></td>
          <td><?= $data["no_trs"]; ?></td>
          <td><?= $data["nama_ruangan"]; ?></td>
          <td><?= $data["status"] ? status($data["status"]) : 'Belum Dikirim' ?></td>
          <td><?= $data["tanggal_pengiriman_ruangan"] ? $data["tanggal_pengiriman_ruangan"] : '-'; ?></td>
          <td><?= $data["tanggal_pengiriman_laundry"] ? $data["tanggal_pengiriman_laundry"] : '-'; ?></td>
          <td><?= $data["jumlah_linen"]; ?></td>
          <td><?= $data["jumlah_linen_laundry"]; ?></td>
          <td>
            <a href="linen/index.php?trs_serah_terima_id=<?= $data["id"]; ?>">Lihat Linen</a>
            <!-- APAKAH YANG LOGIN ADALAH ADMIN? -->
            <?php if (is_role('admin')) : ?>
              <!-- JIKA IYA MAKA CEK APAKAH SUDAH DISERAHKAN KE RUANGAN -->
              <?php if ($data["status"] != 4) : ?>
                <!-- JIKA BELUM DISERAHKAN TAMPILKAN AKSI (serahkan, tetapkan pegawai) -->
                |
                <a href="serah_ke_ruangan.php?id=<?= $data["id"]; ?>" onclick="return confirm('Apa anda yakin untuk menyerahkan kembali ke ruangan? pastikan anda telah pegawai yang ditetapkan, hitung lidi dan jumlah linen nya sesuai');">Serahkan</a>
                |
                <a href="tetapkan_pegawai.php?id=<?= $data["id"]; ?>">Tetapkan Pegawai</a>
              <?php endif ?>
            <?php else : ?>
              <!-- JIKA BUKAN ADMIN (RUANGAN) MAKA CEK APAKAH SUDAH DISERAHKAN KE LAUNDRY? -->
              <?php if (empty($data["status"])) : ?>
                <!-- JIKA BELUM DISERAHKAN TAMPILKAN AKSI (serahkan, Edit, Hapus) -->
                |
                <a href="serah_ke_laundry.php?id=<?= $data["id"]; ?>" onclick="return confirm('Apa anda yakin untuk menyerahkan ke laundry? pastikan linen nya juga sesuai');">Serahkan</a>
                |
                <a href="edit.php?id=<?= $data["id"]; ?>">Edit</a>
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