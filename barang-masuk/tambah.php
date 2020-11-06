<?php
$title = 'Laundry App | Tambah Barang';

require 'functions.php';

// cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {

  // cek apakah data berhasil di tambahkan atau tidak
  if (tambah($_POST) > 0) {
    echo "
			<script>
				alert('data berhasil ditambahkan!');
				document.location.href = 'index.php';
			</script>
		";
  } else {
    echo "
			<script>
				alert('data gagal ditambahkan!');
				document.location.href = 'index.php';
			</script>
		";
  }
}

$barang = query("SELECT * FROM barang");

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
    <h2>Tambah Barang</h2>
    <form action="" method="post">
      <table>
        <tr>
          <td>
            <label for="barang">Barang</label>
          </td>
          <td>
            <select required name="barang_id" id="barang">
              <option value="" disabled selected>Pilih Barang</option>
              <?php foreach ($barang as $b) : ?>
                <option value="<?= $b['id'] ?>"><?= $b['nama'] ?></option>
              <?php endforeach ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <label for="jumlah">Jumlah</label>
          </td>
          <td>
            <input required type="text" placeholder="Masukkan jumlah" id="jumlah" name="jumlah_barang">
          </td>
        </tr>
      </table>
      <p class="text-danger">*note: Data yang ditambahkan tidak bisa diedit atau dihapus</p>
      <button type="submit" name="submit"  onclick="return confirm('yakin?');">Simpan Data</button>
    </form>
  </div>
  <!-- End Content -->

</body>

</html>