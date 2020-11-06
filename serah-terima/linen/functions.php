<?php
require_once('../../koneksi.php');

$nama_table = "linen";

function tambah($data)
{
  global $conn;
  global $nama_table;

  $trs_serah_terima_id = htmlspecialchars($data["trs_serah_terima_id"]);
  $jenis_linen_id = htmlspecialchars($data["jenis_linen_id"]);
  $spesifikasi_id = htmlspecialchars($data["spesifikasi_id"]);
  $hitung_lidi = htmlspecialchars($data["hitung_lidi"]);
  $jumlah = htmlspecialchars($data["jumlah"]);
  $keterangan = htmlspecialchars($data["keterangan"]);

  $query = "INSERT INTO $nama_table 
            (trs_serah_terima_id, jenis_linen_id, spesifikasi_id, hitung_lidi, jumlah, keterangan)
              VALUES 
            ('$trs_serah_terima_id','$jenis_linen_id','$spesifikasi_id','$hitung_lidi','$jumlah','$keterangan')
          ";
  mysqli_query($conn, $query);
  // var_dump($query);
  // die;

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
  $trs_serah_terima_id = htmlspecialchars($data["trs_serah_terima_id"]);
  $jenis_linen_id = htmlspecialchars($data["jenis_linen_id"]);
  $spesifikasi_id = htmlspecialchars($data["spesifikasi_id"]);
  $hitung_lidi = htmlspecialchars($data["hitung_lidi"]);
  $jumlah = htmlspecialchars($data["jumlah"]);
  $keterangan = htmlspecialchars($data["keterangan"]);


  $query = "UPDATE $nama_table SET
              trs_serah_terima_id = $trs_serah_terima_id,
              jenis_linen_id = $jenis_linen_id,
              spesifikasi_id = $spesifikasi_id,
              hitung_lidi = $hitung_lidi,
              jumlah = $jumlah,
              keterangan = $keterangan,
              updated_at = CURRENT_TIMESTAMP
            WHERE id = '$id'
  ";

  mysqli_query($conn, $query);
  
  return mysqli_affected_rows($conn);
}
