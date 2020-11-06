<?php 
$title = 'Laundry App | Tambah Serah terima';

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
    <h2>Tambah Serah terima</h2>
    <form action="" method="post">
      <table>
        <tr>
          <td>
            <label for="Ruangan">Ruangan</label>
          </td>
          <td>
            <select required name="ruangan_id" id="ruangan">
              <option value="" disabled selected>Pilih Ruangan</option>
              <?php foreach ($ruangan as $r) : ?>
                <option value="<?= $r['id'] ?>"><?= $r['nama'] ?></option>
              <?php endforeach ?>
            </select>
          </td>
        </tr>
        <!-- <tr>
          <td>
            <label for="tanggal_pengiriman">Tanggal Pengiriman</label>
          </td>
          <td>
            <input required type="date" id="tanggal_pengiriman" name="tanggal_pengiriman">
          </td>
        </tr> -->
      </table>
      <br>
      <button type="submit" name="submit">Simpan Data</button>
    </form>
  </div>
  <!-- End Content -->

</body>

</html>