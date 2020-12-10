<?php
$title = 'Laundry App | Ubah Pegawai';

require 'functions.php';

// ambil data di URL
$id = $_GET["id"];

// query data berdasarkan id
$item = query("SELECT * FROM trs_serah_terima WHERE id = $id")[0];

$petugas_pengambil = query("SELECT 
              pegawai.nama as nama
            FROM trs_serah_terima, pegawai 
            WHERE 
              trs_serah_terima.petugas_pengambil = pegawai.id
            AND
            trs_serah_terima.id = $id
");
$petugas_pencuci = query("SELECT 
              pegawai.nama as nama
            FROM trs_serah_terima, pegawai 
            WHERE 
              trs_serah_terima.petugas_pencuci = pegawai.id
            AND
            trs_serah_terima.id = $id
");
$petugas_penyetrika = query("SELECT 
              pegawai.nama as nama
            FROM trs_serah_terima, pegawai 
            WHERE 
              trs_serah_terima.petugas_penyetrika = pegawai.id
            AND
            trs_serah_terima.id = $id
");
$petugas_pendistribusi = query("SELECT 
              pegawai.nama as nama
            FROM trs_serah_terima, pegawai 
            WHERE 
              trs_serah_terima.petugas_penyetrika = pegawai.id
            AND
            trs_serah_terima.id = $id
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
    <h2>Petugas pada transaksi <?= $item["no_trs"]; ?></h2>
    <form action="" method="post">
      <input required type="hidden" name="id" value="<?= $item["id"]; ?>">
      <table border="1">
        <tr>
          <td>
            <label for="petugas_pengambil">Petugas Pengambil</label>
          </td>
          <td>
            <?php if (count($petugas_pengambil) > 0) {
              echo $petugas_pengambil[0]["nama"];
            } else {
              echo '-';
            }
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <label for="petugas_pencuci">Petugas Pencuci</label>
          </td>
          <td>
            <?php if (count($petugas_pencuci) > 0) {
              echo $petugas_pencuci[0]["nama"];
            } else {
              echo '-';
            }
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <label for="petugas_penyetrika">Petugas Penyetrika</label>
          </td>
          <td>
            <?php if (count($petugas_penyetrika) > 0) {
              echo $petugas_penyetrika[0]["nama"];
            } else {
              echo '-';
            }
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <label for="petugas_pendistribusi">Petugas Pendistribusi</label>
          </td>
          <td>
            <?php if (count($petugas_pendistribusi) > 0) {
              echo $petugas_pendistribusi[0]["nama"];
            } else {
              echo '-';
            }
            ?>
          </td>
        </tr>
      </table>
    </form>
  </div>
  <!-- End Content -->

</body>

</html>