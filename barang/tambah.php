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

$jenis_barang = query("SELECT * FROM jenis_barang");
$satuan_barang = query("SELECT * FROM satuan_barang");
$kategori_barang = query("SELECT * FROM kategori_barang");

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
            <label for="nama">nama</label>
          </td>
          <td>
            <input required type="text" placeholder="Masukkan Nama" id="nama" name="nama">
          </td>
        </tr>
        <tr>
          <td>
            <label for="jenis_barang">Jenis</label>
          </td>
          <td>
            <select required name="jenis_barang_id" id="jenis_barang" required>
              <option value="" disabled selected>Pilih Jenis Barang</option>
              <?php foreach ($jenis_barang as $jb) : ?>
                <option value="<?= $jb['id'] ?>"><?= $jb['nama'] ?></option>
              <?php endforeach ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <label for="satuan_barang">Satuan</label>
          </td>
          <td>
            <select required name="satuan_barang_id" id="satuan_barang">
              <option value="" disabled selected>Pilih Satuan Barang</option>
              <?php foreach ($satuan_barang as $sb) : ?>
                <option value="<?= $sb['id'] ?>"><?= $sb['nama'] ?></option>
              <?php endforeach ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <label for="kategori_barang">Kategori</label>
          </td>
          <td>
            <select required name="kategori_barang_id" id="kategori_barang">
              <option value="" disabled selected>Pilih Kategori Barang</option>
              <?php foreach ($kategori_barang as $kb) : ?>
                <option value="<?= $kb['id'] ?>"><?= $kb['nama'] ?></option>
              <?php endforeach ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <label for="harga">harga</label>
          </td>
          <td>
            <input required type="text" placeholder="Masukkan harga" id="harga" name="harga_barang">
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