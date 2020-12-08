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

function tetapkan_pegawai($data)
{
  global $conn;
  global $nama_table;

  $id = htmlspecialchars($data["id"]);
  $petugas_pengambil = htmlspecialchars($data["petugas_pengambil"]);
  $petugas_pencuci = htmlspecialchars($data["petugas_pencuci"]);
  $petugas_penyetrika = htmlspecialchars($data["petugas_penyetrika"]);
  $petugas_pendistribusi = htmlspecialchars($data["petugas_pendistribusi"]);

  $query = "UPDATE $nama_table SET
              petugas_pengambil = '$petugas_pengambil',
              petugas_pencuci = '$petugas_pencuci',
              petugas_penyetrika = '$petugas_penyetrika',
              petugas_pendistribusi = '$petugas_pendistribusi',
              updated_at = CURRENT_TIMESTAMP
            WHERE id = '$id'
  ";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function serah_ke_laundry($id)
{
  global $conn;
  global $nama_table;

  $query = "UPDATE $nama_table SET
              tanggal_pengiriman_ruangan = CURRENT_TIMESTAMP,
              status = 1,
              updated_at = CURRENT_TIMESTAMP
            WHERE id = '$id'
  ";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function serah_ke_ruangan($id)
{
  global $conn;
  global $nama_table;

  $query = "UPDATE $nama_table SET
              status = 4,
              tanggal_pengiriman_laundry = CURRENT_TIMESTAMP,
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
      return 'Telah diserahkan ke laundry';

    case 2:
      return 'Telah dicucikan';

    case 3:
      return 'Telah didistribusi';

    case 4:
      return 'Telah diserahkan ke ruangan';

    default:
      return 'Belum';
  }
}
