<?php
require_once('../koneksi.php');

$nama_table = "spesifikasi";

function tambah($data)
{
  global $conn;
  global $nama_table;

  $nama = htmlspecialchars($data["nama"]);

  $query = "INSERT INTO $nama_table (nama)
              VALUES 
            ('$nama')
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

function ubah($data)
{
  global $conn;
  global $nama_table;

  $id = htmlspecialchars($data["id"]);
  $nama = htmlspecialchars($data["nama"]);

  $query = "UPDATE $nama_table SET
              nama = '$nama',
              updated_at = CURRENT_TIMESTAMP
            WHERE id = '$id'
  ";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}