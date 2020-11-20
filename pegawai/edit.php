<?php
$title = 'Laundry App | Ubah Pegawai';

require 'functions.php';

// ambil data di URL
$id = $_GET["id"];

// query data mahasiswa berdasarkan id
$item = query("SELECT * FROM pegawai WHERE id = $id")[0];

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
    <h2>Ubah Pegawai</h2>
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
            <label for="nip">nip</label>
          </td>
          <td>
            <input required type="text" placeholder="Masukkan Nip" id="nip" name="nip" value="<?= $item['nip'] ?>">
          </td>
        </tr>
        <tr>
          <td>
            <label for="role">role</label>
          </td>
          <td>
            <select name="role" id="role" required>
              <option value="" disabled selected>Pilih Role</option>
              <?php foreach ($roles as $role) : ?>
                <?php if ($item['role'] == $role['id']) : ?>
                  <option value="<?= $role['id'] ?>" selected><?= $role['name'] ?></option>
                <?php else : ?>
                  <option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>
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