<?php
require_once('../koneksi.php');

$nama_table = "pegawai";

function tambah($data)
{
  global $conn;
  global $nama_table;

  $nama = htmlspecialchars($data["nama"]);
  $nip = htmlspecialchars($data["nip"]);
  $role = htmlspecialchars($data["role"]);
  $password = mysqli_real_escape_string($conn, $nip);
  // enkripsi password
	$password = password_hash($password, PASSWORD_DEFAULT);

  $query = "INSERT INTO $nama_table (nama,nip,password,role)
              VALUES 
            ('$nama','$nip','$password','$role')
          ";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function ubah($data)
{
  global $conn;
  global $nama_table;

  $id = htmlspecialchars($data["id"]);
  $nama = htmlspecialchars($data["nama"]);
  $nip = htmlspecialchars($data["nip"]);
  $role = htmlspecialchars($data["role"]);

  $query = "UPDATE $nama_table SET
              nama = '$nama',
              nip = '$nip',
              role = '$role',
              updated_at = CURRENT_TIMESTAMP
            WHERE id = '$id'
  ";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}


function hapus($id)
{
  global $conn;
  global $nama_table;

  mysqli_query($conn, "DELETE FROM $nama_table WHERE id = $id");
  return mysqli_affected_rows($conn);
}

function reset_password($id)
{
  global $conn;
  global $nama_table;

  $pegawai = query("SELECT nip FROM $nama_table WHERE id = $id")[0];

  $nip = $pegawai['nip'];
  $password = mysqli_real_escape_string($conn, $nip);
  // enkripsi password
	$password = password_hash($password, PASSWORD_DEFAULT);
  $query = "UPDATE $nama_table SET
              password = '$password',
              updated_at = CURRENT_TIMESTAMP
            WHERE id = '$id'
  ";
  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}
