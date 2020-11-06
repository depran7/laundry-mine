<?php
$title = 'Laundry App | Ubah Barang';

require 'functions.php';

// ambil data di URL
$id = $_GET["id"];

// query data mahasiswa berdasarkan id
$item = query("SELECT * FROM barang WHERE id = $id")[0];

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
    <h2>Ubah Barang</h2>
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
        <tr>
          <td>
            <label for="jenis_barang">Jenis</label>
          </td>
          <td>
            <select required name="jenis_barang_id" id="jenis_barang" required>
              <option value="" disabled selected>Pilih Jenis Barang</option>
              <?php foreach ($jenis_barang as $jb) : ?>
                <?php if ($jb['id'] == $item['jenis_barang_id']) : ?>
                  <option value="<?= $jb['id'] ?>" selected><?= $jb['nama'] ?></option>
                <?php else : ?>
                  <option value="<?= $jb['id'] ?>"><?= $jb['nama'] ?></option>
                <?php endif ?>
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
                <?php if ($sb['id'] == $item['satuan_barang_id']) : ?>
                  <option value="<?= $sb['id'] ?>" selected><?= $sb['nama'] ?></option>
                <?php else : ?>
                  <option value="<?= $sb['id'] ?>"><?= $sb['nama'] ?></option>
                <?php endif ?>
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
                <?php if ($kb['id'] == $item['kategori_barang_id']) : ?>
                  <option value="<?= $kb['id'] ?>" selected><?= $kb['nama'] ?></option>
                <?php else : ?>
                  <option value="<?= $kb['id'] ?>"><?= $kb['nama'] ?></option>
                <?php endif ?>
              <?php endforeach ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <label for="harga">harga</label>
          </td>
          <td>
            <input required type="text" placeholder="Masukkan harga" id="harga" name="harga_barang" value="<?= $item['harga_barang'] ?>">
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