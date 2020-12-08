<?php
$title = 'Laundry App | Ubah Pegawai';

require 'functions.php';

// ambil data di URL
$id = $_GET["id"];

// query data mahasiswa berdasarkan id
$item = query("SELECT * FROM trs_serah_terima WHERE id = $id")[0];

// cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {

  // cek apakah data berhasil di ubahkan atau tidak
  if (tetapkan_pegawai($_POST) > 0) {
    echo "
			<script>
				alert('data berhasil diubahkan!');
				document.location.href = 'index.php';
			</script>
		";
  } else {
    echo "
			<script>
				alert('data gagal diubahkan!');
				document.location.href = 'index.php';
			</script>
		";
  }
}
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
    <h2>Ubah Pegawai</h2>
    <form action="" method="post">
      <input required type="hidden" name="id" value="<?= $item["id"]; ?>">
      <table>
        <tr>
          <td>
            <label for="petugas_pengambil">Petugas Pengambil</label>
          </td>
          <td>
            <select required name="petugas_pengambil" id="petugas_pengambil">
              <option value="" disabled selected>Pilih Pegawai</option>
              <?php foreach ($pegawai as $r) : ?>
                <?php if ($r['id'] == $item['petugas_pengambil']) : ?>
                  <option value="<?= $r['id'] ?>" selected><?= $r['nama'] ?></option>
                <?php else : ?>
                  <option value="<?= $r['id'] ?>"><?= $r['nama'] ?></option>
                <?php endif ?>
              <?php endforeach ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <label for="petugas_pencuci">Petugas Pencuci</label>
          </td>
          <td>
            <select required name="petugas_pencuci" id="petugas_pencuci">
              <option value="" disabled selected>Pilih Pegawai</option>
              <?php foreach ($pegawai as $r) : ?>
                <?php if ($r['id'] == $item['petugas_pencuci']) : ?>
                  <option value="<?= $r['id'] ?>" selected><?= $r['nama'] ?></option>
                <?php else : ?>
                  <option value="<?= $r['id'] ?>"><?= $r['nama'] ?></option>
                <?php endif ?>
              <?php endforeach ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <label for="petugas_penyetrika">Petugas Penyetrika</label>
          </td>
          <td>
            <select required name="petugas_penyetrika" id="petugas_penyetrika">
              <option value="" disabled selected>Pilih Pegawai</option>
              <?php foreach ($pegawai as $r) : ?>
                <?php if ($r['id'] == $item['petugas_penyetrika']) : ?>
                  <option value="<?= $r['id'] ?>" selected><?= $r['nama'] ?></option>
                <?php else : ?>
                  <option value="<?= $r['id'] ?>"><?= $r['nama'] ?></option>
                <?php endif ?>
              <?php endforeach ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <label for="petugas_pendistribusi">Petugas Pendistribusi</label>
          </td>
          <td>
            <select required name="petugas_pendistribusi" id="petugas_pendistribusi">
              <option value="" disabled selected>Pilih Pegawai</option>
              <?php foreach ($pegawai as $r) : ?>
                <?php if ($r['id'] == $item['petugas_pendistribusi']) : ?>
                  <option value="<?= $r['id'] ?>" selected><?= $r['nama'] ?></option>
                <?php else : ?>
                  <option value="<?= $r['id'] ?>"><?= $r['nama'] ?></option>
                <?php endif ?>
              <?php endforeach ?>
            </select>
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