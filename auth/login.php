<?php
session_start();
//cek apakah sudah login atau belum
if(isset($_SESSION['loggedin'])){
  header("Location: ../index.php");
}

$title = 'Laundry App | Login';

require 'functions.php';

// cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {

  // cek apakah user berhasil login atau tidak
  if (login($_POST) > 0) {
    echo "
			<script>
				alert('Berhasil Login');
				document.location.href = '../index.php';
			</script>
		";
  } else {
    echo "
			<script>
				alert('Seperti nya username atau password salah');
			</script>
		";
  }
}

?>

<!DOCTYPE html>
<html>

<head>
  <title><?= $title ?></title>
</head>

<body>
  <div class="content">
    <h2>Login</h2>
    <form action="" method="POST">
      <table>
        <tr>
          <td><label for="nip">NIP</label></td>
          <td><input required type="text" placeholder="Masukkan nip" id="nip" name="nip"></td>
        </tr>
        <tr>
          <td><label for="password">Password</label></td>
          <td><input required type="password" placeholder="Masukkan password" id="password" name="password"></td>
        </tr>
      </table>
      <br>
      <button type="submit" name="submit">Login</button>
    </form>
  </div>

</body>

</html>