<?php
$title = 'Laundry App | Ubah Jenis';

require 'functions.php';

// ambil data di URL
$id = $_GET["id"];

// query data mahasiswa berdasarkan id
$item = query("SELECT * FROM jenis_barang WHERE id = $id")[0];

// cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {

  // cek apakah data berhasil di ubahkan atau tidak
  if (ubah($_POST) > 0) {
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
    <h2>Ubah Jenis</h2>
    <form action="" method="post">
      <input required type="hidden" name="id" value="<?= $item["id"]; ?>">
      <table>
        <tr>
          <td>
            <label for="nama">nama</label>
          </td>
          <td>
            <input required type="text" placeholder="Masukkan Nama" id="nama" name="nama" value="<?= $item['nama'] ?>">
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