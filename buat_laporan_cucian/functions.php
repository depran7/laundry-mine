<?php
require_once('../koneksi.php');

function buat_laporan($data)
{
  $ruangan_id = htmlspecialchars($data["ruangan_id"]);
  $start_date = htmlspecialchars($data["start_date"]);
  $from_date = htmlspecialchars($data["from_date"]);

  //menyiapkan query 
  $query = "SELECT 
              date(linen.updated_at) as tanggal, 
              spesifikasi.nama as spesifikasi, 
              SUM(linen.jumlah_laundry) as jumlah
            FROM 
              linen INNER JOIN spesifikasi 
              ON linen.spesifikasi_id = spesifikasi.id 
              INNER JOIN trs_serah_terima
              ON linen.trs_serah_terima_id = trs_serah_terima.id 
            WHERE
              trs_serah_terima.ruangan_id = '$ruangan_id'
              AND
              trs_serah_terima.updated_at BETWEEN '$start_date 00:00:01' AND '$from_date 23:59:59'
            GROUP BY spesifikasi, tanggal
            ORDER BY tanggal
            ";

  //jalankan query (ambil data)
  $data = query($query);

  // baris dibawah hanya untuk debugging
  // echo '<pre>';
  // var_dump($query);
  // var_dump($data);
  // die;

  // Load plugin PHPExcel nya
  require_once '../libraries/PHPExcel/PHPExcel.php';
  require_once '../libraries/PHPExcel/PHPExcel/Writer/Excel2007.php';

  // Panggil class PHPExcel nya
  $excel = new PHPExcel();

  // Settingan awal file excel
  $excel->getProperties()->setCreator('Laundry App')
    ->setLastModifiedBy('Laundry App')
    ->setTitle("LAPORAN PENGELOLAAN LINEN INSTALASI LAUNDRY")
    ->setSubject("Linen")
    ->setDescription("LAPORAN PENGELOLAAN LINEN INSTALASI LAUNDRY")
    ->setKeywords("LAPORAN PENGELOLAAN LINEN INSTALASI LAUNDRY");


  // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
  $style_col = array(
    'font' => array('bold' => true), // Set font nya jadi bold
    'alignment' => array(
      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    ),
    'borders' => array(
      'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
      'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
      'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
      'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
    )
  );

  // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
  $style_row = array(
    'alignment' => array(
      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    ),
    'borders' => array(
      'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
      'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
      'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
      'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
    )
  );
  $excel->setActiveSheetIndex(0)->setCellValue('A1', "LAPORAN PENGELOLAAN LINEN INSTALASI LAUNDRY"); // Set kolom A1 dengan tulisan "DATA LINEN"
  $excel->getActiveSheet()->mergeCells('A1:D1'); // Set Merge Cell pada kolom A1 sampai F1
  $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
  $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
  $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

  // Buat header tabel nya pada baris ke 3
  $excel->getActiveSheet()->setCellValue('A4', "NO"); // Set kolom A3 dengan tulisan "NO"
  $excel->getActiveSheet()->setCellValue('B4', "TANGGAL"); // Set kolom B3 dengan tulisan "TANGGAL"
  $excel->getActiveSheet()->setCellValue('C4', "Linen"); // Set kolom C3 dengan tulisan "Linen"
  $excel->getActiveSheet()->setCellValue('D4', "Jumlah"); // Set kolom C3 dengan tulisan "Jumlah"

  // Apply style header yang telah kita buat tadi ke masing-masing kolom header
  $excel->getActiveSheet()->getStyle('A4')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('B4')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('C4')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('D4')->applyFromArray($style_col);

  $no = 1; // Untuk penomoran tabel, di awal set dengan 1
  $numrow = 5; // Set baris pertama untuk isi tabel adalah baris ke 4
  foreach ($data as $d) { // Lakukan looping pada variabel data
    $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
    $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $d["tanggal"]);
    $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $d["spesifikasi"]);
    $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $d["jumlah"]);

    // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
    $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
    $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
    $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
    $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);

    $no++; // Tambah 1 setiap kali looping
    $numrow++; // Tambah 1 setiap kali looping
  }
  // Set width kolom
  $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
  $excel->getActiveSheet()->getColumnDimension('B')->setWidth(15); // Set width kolom B
  $excel->getActiveSheet()->getColumnDimension('C')->setWidth(25); // Set width kolom C
  $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20); // Set width kolom D

  // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
  $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
  // Set orientasi kertas jadi LANDSCAPE
  $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
  // Set judul file excel nya
  $excel->getActiveSheet(0)->setTitle("Laporan Data Linen");
  $excel->setActiveSheetIndex(0);
  // Proses file excel
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment; filename="Data Linen'.$start_date.' sd '.$start_date.'.xlsx"'); // Set nama file excel nya
  header('Cache-Control: max-age=0');
  $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
  $write->save('php://output');
}