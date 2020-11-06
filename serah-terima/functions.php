<?php
require_once('../koneksi.php');

$nama_table = "trs_serah_terima";

function tambah($data)
{
  global $conn;
  global $nama_table;

  $today = date("Y-m-d");
  $total_trs = query("SELECT COUNT(*) as total_trs FROM $nama_table WHERE DATE(created_at) = '$today'")[0];
  // var_dump($total_trs['total_trs']);
  $total_trs = (int) $total_trs['total_trs'] + 1;
  $no_trs = "ST/" . date('d-m-Y') . "/" . str_pad($total_trs, 4, "0", STR_PAD_LEFT);
  // echo $no_trs;
  // die;
  $ruangan_id = htmlspecialchars($data["ruangan_id"]);

  $query = "INSERT INTO $nama_table (no_trs, ruangan_id)
              VALUES 
            ('$no_trs','$ruangan_id')
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
  $ruangan_id = htmlspecialchars($data["ruangan_id"]);


  $query = "UPDATE $nama_table SET
              ruangan_id = '$ruangan_id',
              updated_at = CURRENT_TIMESTAMP
            WHERE id = '$id'
  ";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function search($id)
{
  global $conn;
  global $nama_table;

  $query = "UPDATE $nama_table SET
              tanggal_pengiriman = CURRENT_TIMESTAMP,
              status = 1,
              updated_at = CURRENT_TIMESTAMP
            WHERE id = '$id'
  ";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}


function status($data)
{
  switch ($data) {
    case 1:
      return 'Telah diserahkan';
    
    case 2:
      return 'Telah dicucikan';
    
    case 3:
      return 'Telah didistribusi';
      
    default:
      return 'Belum';
  }
}