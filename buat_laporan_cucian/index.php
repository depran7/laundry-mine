<?php
$title = 'Laundry App | Jenis Linen';

require 'functions.php';

// cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {

  // cek apakah data berhasil di tambahkan atau tidak
  if (buat_laporan($_POST) > 0) {
    echo "
			<script>
				alert('data berhasil diexport ke excel!');
				document.location.href = 'index.php';
			</script>
		";
  } else {
    echo "
			<script>
				alert('data gagal diexport ke excel!');
				document.location.href = 'index.php';
			</script>
		";
  }
}
$jenis = query("SELECT * FROM jenis_linen");
$ruangan = query("SELECT * FROM ruangan");

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
    <h2>Buat Laporan Pengelolaan Linen Instalasi Laundry</h2>
    <form action="" method="POST">
      <table>
        <tr>
          <th align="start">
            <label for="ruangan">Ruangan</label>
          </th>
          <th align="start">
            <label for="start_date">Dari Tanggal</label>
          </th>
          <th align="start">
            <label for="from_date">Sampai Tanggal</label>
          </th>
          <th></th>
        </tr>
        <tr>
          <td>
            <select required name="ruangan_id" id="ruangan">
              <option value="" disabled selected>Pilih Ruangan</option>
              <?php foreach ($ruangan as $r) : ?>
                <option value="<?= $r['id'] ?>"><?= $r['nama'] ?></option>
              <?php endforeach ?>
            </select>
          </td>
          <td>
            <input type="date" id="start_date" name="start_date" required>
          </td>
          <td>
            <input type="date" id="from_date" name="from_date" required>
          </td>
          <td>
            <button type="submit" name="submit">Export Data</button>
          </td>
        </tr>
      </table>
    </form>
  </div>
  <!-- End Content -->
</body>

</html>