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
    <h2>Buat Laporan Penggunaan Bahan Instalasi Laundry</h2>
    <form action="" method="POST">
      <table>
        <tr>
          <th align="start">
            <label for="month_year">Bulan</label>
          </th>
          <th></th>
        </tr>
        <tr>
          <td>
            <input type="month" id="month_year" name="month_year" required>
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