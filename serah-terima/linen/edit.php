<?php
$title = 'Laundry App | Ubah Linen';

require 'functions.php';

$trs_serah_terima_id = $_GET['trs_serah_terima_id'];
$id = $_GET['id'];

// cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {
  // cek apakah data berhasil di ubahkan atau tidak
  if (ubah($_POST) > 0) {
    echo "
			<script>
				alert('data berhasil diubahkan!');
				document.location.href = 'index.php?trs_serah_terima_id=$trs_serah_terima_id';
			</script>
		";
  } else {
    echo "
			<script>
				alert('data gagal diubahkan!');
				document.location.href = 'index.php?trs_serah_terima_id=$trs_serah_terima_id';
			</script>
		";
  }
}
$jenis_linen = query("SELECT * FROM jenis_linen");
$spesifikasi = query("SELECT * FROM spesifikasi");
$item = query("SELECT * FROM linen WHERE id = $id")[0];

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
    <h2>Ubah Linen</h2>
    <form action="" method="post">
      <input type="hidden" value="<?= $trs_serah_terima_id ?>" name="trs_serah_terima_id">
      <input type="hidden" value="<?= $id ?>" name="id">
      <table>
        <tr>
          <td>
            <label for="jenis_linen">Jenis Linen</label>
          </td>
          <td>
            <select required name="jenis_linen_id" id="jenis_linen">
              <option value="" disabled selected>Pilih Jenis Linen</option>
              <?php foreach ($jenis_linen as $jl) : ?>
                <?php if ($jl['id'] == $item['jenis_linen_id']) : ?>
                  <option value="<?= $jl['id'] ?>" selected><?= $jl['nama'] ?></option>
                <?php else : ?>
                  <option value="<?= $jl['id'] ?>"><?= $jl['nama'] ?></option>
                <?php endif ?>
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
                <?php if ($s['id'] == $item['spesifikasi_id']) : ?>
                  <option value="<?= $s['id'] ?>" selected><?= $s['nama'] ?></option>
                <?php else : ?>
                  <option value="<?= $s['id'] ?>"><?= $s['nama'] ?></option>
                <?php endif ?>
              <?php endforeach ?>
            </select>
          </td>
        </tr>

        <tr>
          <td>
            <label for="hitung_lidi">Hitung Lidi</label>
          </td>
          <td>
            <input required type="number" placeholder="Masukkan hitung lidi" id="hitung_lidi" name="hitung_lidi" value="<?= $item['hitung_lidi'] ?>">
          </td>
        </tr>

        <tr>
          <td>
            <label for="jumlah">Jumlah</label>
          </td>
          <td>
            <input required type="number" placeholder="Masukkan jumlah" id="jumlah" name="jumlah" value="<?= $item['jumlah'] ?>">
          </td>
        </tr>

        <tr>
          <td style="vertical-align: top;">
            <label for="keterangan">Keterangan</label>
          </td>
          <td>
            <textarea name="keterangan" id="keterangan" cols="30" rows="4" placeholder="Masukkan keterangan"><?= $item['keterangan'] ?></textarea>
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