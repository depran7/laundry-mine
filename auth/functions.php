<?php
require_once('../koneksi.php');

$nama_table = "pegawai";

function login($data)
{
  // global $conn;
  global $nama_table;

  $nip = htmlspecialchars($data["nip"]);
  $password = htmlspecialchars($data["password"]);

  $pegawai = query("SELECT * FROM $nama_table 
            WHERE nip = $nip
          ");
  //cek nip
  if (count($pegawai) > 0) {
    $user = $pegawai[0];
    //cel password
    if (password_verify($password, $user["password"])) {
      //set session
      $_SESSION['loggedin'] = true;
      //meremove password dari array agar tidak tersimpan di session
      unset($user["password"]);
      $_SESSION['user'] = $user;
    } else {
      return 0;
    }
  }
  return count($pegawai);
}

function logout()
{
  $_SESSION = [];
  session_unset();
  session_destroy();
}
