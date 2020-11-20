<?php 
$title = 'Laundry App | Tambah Pegawai';

require 'functions.php';

// cek apakah tombol submit sudah ditekan atau belum
if( isset($_POST["submit"]) ) {
	
	// cek apakah data berhasil di tambahkan atau tidak
	if( tambah($_POST) > 0 ) {
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
    <h2>Tambah Pegawai</h2>
    <form action="" method="post">
      <p class="text-danger">Perhatian: untuk password default itu sama dengan nip, beritahu pegawai agar segera mengganti password nya</p>
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
            <label for="nip">nip</label>
          </td>
          <td>
            <input required type="text" placeholder="Masukkan Nip" id="nip" name="nip">
          </td>
        </tr>
        <tr>
          <td>
            <label for="role">role</label>
          </td>
          <td>
            <select name="role" id="role" required>
              <option value="" disabled selected>Pilih Role</option>
              <option value="1">Pengambil</option>
              <option value="2">Pencuci</option>
              <option value="3">Penyetrika</option>
              <option value="4">Pendistribusi</option>
              <option value="5">Admin</option>
              <option value="6">Penyerahan</option>
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