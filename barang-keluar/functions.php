<?php
require_once('../koneksi.php');

$nama_table1 = "trs_pemakaian_barang";
$nama_table2 = "barang";
$nama_table3 = "history_stock_barang";

function tambah($data)
{
  global $conn;
  global $nama_table1;
  global $nama_table2;
  global $nama_table3;

  $barang_id = htmlspecialchars($data["barang_id"]);
  $jumlah_barang = htmlspecialchars($data["jumlah_barang"]);

  // TAMBAH DATA trs_pemakaian_barang
  $query = "INSERT INTO $nama_table1 
            (barang_id, jumlah_barang)
              VALUES 
            ('$barang_id','$jumlah_barang')
          ";
  mysqli_query($conn, $query);

  // Mengambil data Barang yang dipilih
  $barang = query("SELECT * FROM $nama_table2 WHERE id = '$barang_id'")[0];

  //Menghitung total barang
  $total_barang = $barang['jumlah_barang'] - $jumlah_barang; //jumlah barang saat ini - jumlah barang yang masuk

  // Ubah data jumlah Barang yang dipilih
  $query = "UPDATE $nama_table2 SET
              jumlah_barang = '$total_barang'
            WHERE id = '$barang_id'
          ";
          
  mysqli_query($conn, $query);

  // Tambah data history stock Barang yang dipilih
  $query = "INSERT INTO $nama_table3
            (barang_id, jumlah_barang)
              VALUES 
            ('$barang_id','$total_barang')
          ";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}
