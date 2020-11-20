<?php 
session_start();

require_once('koneksi.php');

$title = 'Laundry App';

//cek apakah sudah login atau belum
if(!isset($_SESSION['loggedin'])){
  header("Location: auth/login.php");
}

?>

<!DOCTYPE html>
<html>

<head>
  <title><?= $title ?></title>
  <link rel="stylesheet" href="css/styles.css">
</head>

<body>
  <?php include('sidebar.php') ?>

  <div class="content">
    <h2>Selamat Datang <?= $_SESSION['user']['nama'] ?></h2>
  </div>

</body>

</html>