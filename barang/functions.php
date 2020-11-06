<?php
require_once('../koneksi.php');

$nama_table = "barang";

function tambah($data)
{
  global $conn;
  global $nama_table;

  $nama = htmlspecialchars($data["nama"]);
  $jenis_barang_id = htmlspecialchars($data["jenis_barang_id"]);
  $satuan_barang_id = htmlspecialchars($data["satuan_barang_id"]);
  $kategori_barang_id = htmlspecialchars($data["kategori_barang_id"]);
  $harga_barang = htmlspecialchars($data["harga_barang"]);

  $query = "INSERT INTO $nama_table 
            (nama, jenis_barang_id, satuan_barang_id, kategori_barang_id, harga_barang)
              VALUES 
            ('$nama','$jenis_barang_id','$satuan_barang_id','$kategori_barang_id', '$harga_barang')
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
  $jenis_barang_id = htmlspecialchars($data["jenis_barang_id"]);
  $satuan_barang_id = htmlspecialchars($data["satuan_barang_id"]);
  $kategori_barang_id = htmlspecialchars($data["kategori_barang_id"]);
  $harga_barang = htmlspecialchars($data["harga_barang"]);

  $query = "UPDATE $nama_table SET
              nama = '$nama',
              jenis_barang_id = '$jenis_barang_id',
              satuan_barang_id = '$satuan_barang_id',
              kategori_barang_id = '$kategori_barang_id',
              harga_barang = '$harga_barang',
              updated_at = CURRENT_TIMESTAMP
            WHERE id = '$id'
  ";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}