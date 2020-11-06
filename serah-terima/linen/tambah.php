<?php
$title = 'Laundry App | Tambah Serah terima';

require 'functions.php';

$trs_serah_terima_id = $_GET['trs_serah_terima_id'];

// cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {
  // cek apakah data berhasil di tambahkan atau tidak
  if (tambah($_POST) > 0) {
    echo "
			<script>
				alert('data berhasil ditambahkan!');
				document.location.href = 'index.php?trs_serah_terima_id=$trs_serah_terima_id';
			</script>
		";
  } else {
    echo "
			<script>
				alert('data gagal ditambahkan!');
				document.location.href = 'index.php?trs_serah_terima_id=$trs_serah_terima_id';
			</script>
		";
  }
}
$jenis_linen = query("SELECT * FROM jenis_linen");
$spesifikasi = query("SELECT * FROM spesifikasi");
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
    <h2>Tambah Serah terima</h2>
    <form action="" method="post">
      <input type="hidden" value="<?= $trs_serah_terima_id ?>" name="trs_serah_terima_id">
      <table>
        <tr>
          <td>
            <label for="jenis_linen">Jenis Linen</label>
          </td>
          <td>
            <select required name="jenis_linen_id" id="jenis_linen">
              <option value="" disabled selected>Pilih Jenis Linen</option>
              <?php foreach ($jenis_linen as $jl) : ?>
                <option value="<?= $jl['id'] ?>"><?= $jl['nama'] ?></option>
              <?php endforeach ?>
            </select>
          </td>
        </tr>

        <tr>
          <td>
            <label for="spesifikasi_linen">Spesifikasi Linen</label>
          </td>
          <td>
            <select required name="spesifikasi_id" id="spesifikasi_linen">
              <option value="" disabled selected>Pilih Spesifikasi Linen</option>
              <?php foreach ($spesifikasi as $s) : ?>
                <option value="<?= $s['id'] ?>"><?= $s['nama'] ?></option>
              <?php endforeach ?>
            </select>
          </td>
        </tr>

        <tr>
          <td>
            <label for="hitung_lidi">Hitung Lidi</label>
          </td>
          <td>
            <input required type="number" placeholder="Masukkan hitung lidi" id="hitung_lidi" name="hitung_lidi">
          </td>
        </tr>

        <tr>
          <td>
            <label for="jumlah">Jumlah</label>
          </td>
          <td>
            <input required type="number" placeholder="Masukkan jumlah" id="jumlah" name="jumlah">
          </td>
        </tr>

        <tr>
          <td>
            <label for="keterangan">Keterangan</label>
          </td>
          <td>
            <input required type="text" placeholder="Masukkan keterangan" id="keterangan" name="keterangan">
          </td>
        </tr>

      </table>
      <br>
      <button type="submit" name="submit">Simpan Data</button>
    </form>
  </div>
  <!-- End Content -->

</body>

</html>