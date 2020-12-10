<?php
require_once('../koneksi.php');

function getDataBarang()
{
  return query("SELECT id, nama FROM barang");
}

function getStockAwal($year, $month)
{
  $month--; //ambil data stock barang di bulan sebelumnya

  $subquery = "SELECT MAX(id)
  FROM history_stock_barang
  WHERE year(created_at) = '$year'
    AND month(created_at) = '$month'
  GROUP BY barang_id";

  return query("SELECT id, barang_id, jumlah_barang
  FROM history_stock_barang
  WHERE id IN ($subquery)
    AND year(created_at) = '$year'
    AND month(created_at) = '$month';");
}

function getStockAkhir($year, $month)
{

  $subquery = "SELECT MAX(id)
  FROM history_stock_barang
  WHERE year(created_at) = '$year'
    AND month(created_at) = '$month'
  GROUP BY barang_id";

  return query("SELECT id, barang_id, jumlah_barang
  FROM history_stock_barang
  WHERE id IN ($subquery)
    AND year(created_at) = '$year'
    AND month(created_at) = '$month';");
}

function getPemakaianBarang($year, $month)
{
  return query("SELECT id, barang_id, jumlah_barang, date(created_at) as tanggal, day(created_at) as day
  FROM trs_pemakaian_barang
  WHERE id IN (SELECT MAX(id) FROM trs_pemakaian_barang GROUP BY date(created_at), barang_id)
    AND year(created_at) = '$year'
    AND month(created_at) = '$month';");
}

function getPemasukanBarang($year, $month)
{
  $subquery2 = "SELECT id
  FROM trs_barang_masuk
  WHERE year(created_at) = '$year'
    AND month(created_at) = '$month'";

  $subquery1 = "SELECT id, barang_id, jumlah_barang, date(created_at) as tanggal, day(created_at) as day
  FROM trs_barang_masuk
  WHERE id IN ($subquery2)";

  return query("SELECT barang_id, sum(jumlah_barang) as jumlah_barang FROM ($subquery1)  as subquery
  GROUP BY barang_id;");
}

function getData()
{
}
function buat_laporan($data)
{
  global $conn;
  $month_year = htmlspecialchars($data["month_year"]);
  $month_year_sec = mysqli_real_escape_string($conn, $month_year);
  $temp = new DateTime($month_year_sec . '-01');
  $month = $temp->format('m');
  $year = $temp->format('Y');
  $newMonthYear = $temp->format('Y-m');
  $newMonthName = $temp->format('F Y');
  // $newMonthMinusOne = $temp->sub(new DateInterval('P1D'))->format('Y-m');
  // $newMonthMinusOneName = $temp->sub(new DateInterval('P1D'))->format('F Y');

  $barang = getDataBarang();
  $stockAwal = getStockAwal($year, $month);
  $stockAkhir = getStockAkhir($year, $month);
  $pemakaianBarang = getPemakaianBarang($year, $month);
  $pemasukanBarang = getPemasukanBarang($year, $month);

  // baris dibawah hanya untuk debugging
  // echo '<pre>';
  // var_dump($barang);
  // var_dump($stockAwal);
  // var_dump($stockAkhir);
  // var_dump($pemakaianBarang);
  // var_dump($pemasukanBarang);
  // die;
  $defaultPemakaianBarang = [];
  for ($i = 1; $i <= 31; $i++) {
    //hari = jumlah_barang
    $defaultPemakaianBarang[$i] = '';
  }
  $data = [];
  foreach ($barang as $key => $value) {
    $tempValue[] = $value;
    $tempValue[$key]['stock_awal'] = 0;
    foreach ($stockAwal as $valueSA) {
      if ($tempValue[$key]['id'] == $valueSA['barang_id']) {
        $tempValue[$key]['stock_awal'] = $valueSA['jumlah_barang'];
      }
    }
    $tempValue[$key]['stock_akhir'] = 0;
    foreach ($stockAkhir as $valueSA) {
      if ($tempValue[$key]['id'] == $valueSA['barang_id']) {
        $tempValue[$key]['stock_akhir'] = $valueSA['jumlah_barang'];
      }
    }
    $tempValue[$key]['pemasukan_barang'] = 0;
    foreach ($pemasukanBarang as $valuePB) {
      if ($tempValue[$key]['id'] == $valuePB['barang_id']) {
        $tempValue[$key]['pemasukan_barang'] = $valuePB['jumlah_barang'];
      }
    }
    $tempValue[$key]['pemakaian_barang'] = $defaultPemakaianBarang;
    $tempValue[$key]['total_pemakaian_barang'] = 0;
    foreach ($pemakaianBarang as $valuePB) {
      if ($tempValue[$key]['id'] == $valuePB['barang_id']) {
        $tempValue[$key]['total_pemakaian_barang'] += $valuePB['jumlah_barang'];
        $tempValue[$key]['pemakaian_barang'][$valuePB['day']] = $valuePB['jumlah_barang'];
      }
    }
  }
  $data = $tempValue;

  // Load plugin PHPExcel nya
  require_once '../libraries/PHPExcel/PHPExcel.php';
  require_once '../libraries/PHPExcel/PHPExcel/Writer/Excel2007.php';

  // Panggil class PHPExcel nya
  $excel = new PHPExcel();

  // Settingan awal file excel
  $excel->getProperties()->setCreator('Laundry App')
    ->setLastModifiedBy('Laundry App')
    ->setTitle("Laporan Penggunaan Bahan Instalasi Laundry")
    ->setSubject("Linen")
    ->setDescription("Laporan Penggunaan Bahan Instalasi Laundry")
    ->setKeywords("Laporan Penggunaan Bahan Instalasi Laundry");


  // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
  $style_col = array(
    'font' => array('bold' => true), // Set font nya jadi bold
    'alignment' => array(
      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    ),
    'borders' => array(
      'allborders' => array(
        'style' => PHPExcel_Style_Border::BORDER_THIN
      )
    )
  );

  // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
  $style_row = array(
    'alignment' => array(
      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    ),
    'borders' => array(
      'allborders' => array(
        'style' => PHPExcel_Style_Border::BORDER_THIN
      )
    )
  );
  $excel->setActiveSheetIndex(0)->setCellValue('A1', "PEMERINTAH PROVINSI JAWA BARAT"); // Set kolom A1 dengan tulisan "PEMERINTAH PROVINSI JAWA BARAT"
  $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
  $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1

  $excel->setActiveSheetIndex(0)->setCellValue('A2', "RUMAH SAKIT JIWA"); // Set kolom A2 dengan tulisan "RUMAH SAKIT JIWA"
  $excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE); // Set bold kolom A2
  $excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(15); // Set font size 15 untuk kolom A2

  $excel->setActiveSheetIndex(0)->setCellValue('A3', "Jl. Kolonel Masturi, Telp. (022) 2700260 Cisarua-Cimahi"); // Set kolom A3 dengan tulisan "Jl. Kolonel Masturi, Telp. (022) 2700260 Cisarua-Cimahi"
  $excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(TRUE); // Set bold kolom A3
  $excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(15); // Set font size 15 untuk kolom A3

  $excel->setActiveSheetIndex(0)->setCellValue('A5', "DAFTAR PENGGUNAAN BAHAN"); // Set kolom A5 dengan tulisan "DAFTAR PENGGUNAAN BAHAN"
  $excel->getActiveSheet()->mergeCells('A5:AM5'); // Set Merge Cell pada kolom A1 sampai F1
  $excel->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE); // Set bold kolom A5
  $excel->getActiveSheet()->getStyle('A5')->getFont()->setSize(15); // Set font size 15 untuk kolom A5
  $excel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A5

  $excel->setActiveSheetIndex(0)->setCellValue('A6', "INSTALASI LAUNDRY"); // Set kolom A6 dengan tulisan "INSTALASI LAUNDRY"
  $excel->getActiveSheet()->mergeCells('A6:AM6'); // Set Merge Cell pada kolom A1 sampai F1
  $excel->getActiveSheet()->getStyle('A6')->getFont()->setBold(TRUE); // Set bold kolom A6
  $excel->getActiveSheet()->getStyle('A6')->getFont()->setSize(15); // Set font size 15 untuk kolom A6
  $excel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A6

  $excel->setActiveSheetIndex(0)->setCellValue('A7', "BULAN: $newMonthName"); // Set kolom A7 dengan tulisan Contoh "BULAN: AGUSTUS 2020"
  $excel->getActiveSheet()->mergeCells('A7:AM7'); // Set Merge Cell pada kolom A1 sampai F1
  $excel->getActiveSheet()->getStyle('A7')->getFont()->setBold(TRUE); // Set bold kolom A7
  $excel->getActiveSheet()->getStyle('A7')->getFont()->setSize(15); // Set font size 15 untuk kolom A7
  $excel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A7

  // Buat header tabel nya pada baris ke 
  $excel->getActiveSheet()->setCellValue('A9', "NO"); // Set kolom A3 dengan tulisan "NO"
  $excel->getActiveSheet()->mergeCells('A9:A10'); // Set Merge Cell pada kolom A1 sampai F1
  $excel->getActiveSheet()->setCellValue('B9', "NAMA BAHAN"); // Set kolom B3 dengan tulisan "TANGGAL"
  $excel->getActiveSheet()->mergeCells('B9:B10'); // Set Merge Cell pada kolom A1 sampai F1
  $excel->getActiveSheet()->setCellValue('C9', "STOK AWAL"); // Set kolom C3 dengan tulisan "Linen"
  $excel->getActiveSheet()->mergeCells('C9:C10'); // Set Merge Cell pada kolom A1 sampai F1
  $excel->getActiveSheet()->setCellValue('D9', "PEMAKAIAN"); // Set kolom C3 dengan tulisan "Jumlah"
  $excel->getActiveSheet()->mergeCells('D9:AH9'); // Set Merge Cell pada kolom A1 sampai F1
  $excel->getActiveSheet()->setCellValue('AJ9', "MASUK"); // Set kolom C3 dengan tulisan "Jumlah"
  $excel->getActiveSheet()->mergeCells('AJ9:AL9'); // Set Merge Cell pada kolom A1 sampai F1
  $excel->getActiveSheet()->setCellValue('AM9', "STOCK AKHIR"); // Set kolom C3 dengan tulisan "Jumlah"
  $excel->getActiveSheet()->mergeCells('AM9:AM10'); // Set Merge Cell pada kolom A1 sampai F1

  $bgGrey = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'DDDDDD')
    )
  );

  $index = 1;
  $kolom_pemakaian = [];
  foreach (range('D', 'Z') as $columnID) {
    $excel->getActiveSheet()->setCellValue($columnID . '10', $index); // Set kolom D10-Z10
    $kolom_pemakaian[$index] = $columnID;
    $index++;
  }
  foreach (range('A', 'H') as $columnID) {
    $excel->getActiveSheet()->setCellValue('A' . $columnID . '10', $index); // Set kolom AA10 AZ10
    $kolom_pemakaian[$index] = 'A' . $columnID;
    $index++;
  }
  // var_dump($kolom_pemakaian);
  // die;

  $excel->getActiveSheet()->setCellValue('AI10', 'Σ'); // Set kolom AI10
  $excel->getActiveSheet()->setCellValue('AL10', 'Σ'); // Set kolom AI10

  // Apply style
  $excel->getActiveSheet()->getStyle('D10:AL10')->applyFromArray($bgGrey);
  $excel->getActiveSheet()->getStyle('A9:AM10')->applyFromArray($style_col);

  $no = 1; // Untuk penomoran tabel, di awal set dengan 1
  $numrow = 11; // Set baris pertama untuk isi tabel adalah baris ke 4
  foreach ($data as $d) { // Lakukan looping pada variabel data
    $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
    $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $d["nama"]);
    $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $d["stock_awal"]);
    foreach ($d['pemakaian_barang'] as $key => $value) {
      $excel->setActiveSheetIndex(0)->setCellValue($kolom_pemakaian[$key] . $numrow, $value);
    }
    $excel->setActiveSheetIndex(0)->setCellValue('AI' . $numrow, $d["total_pemakaian_barang"]);
    $excel->setActiveSheetIndex(0)->setCellValue('AL' . $numrow, $d["pemasukan_barang"]);
    $excel->setActiveSheetIndex(0)->setCellValue('AM' . $numrow, $d["stock_akhir"]);

    $no++; // Tambah 1 setiap kali looping
    $numrow++; // Tambah 1 setiap kali looping
  }
  // die;


  // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
  $excel->getActiveSheet()->getStyle('A11:AM' . ($numrow - 1))->applyFromArray($style_row);

  // Set width kolom
  $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
  $excel->getActiveSheet()->getColumnDimension('B')->setWidth(20); // Set width kolom B
  $excel->getActiveSheet()->getColumnDimension('C')->setWidth(20); // Set width kolom B
  $excel->getActiveSheet()->getColumnDimension('AM')->setWidth(20); // Set width kolom B
  $excel->getActiveSheet()->getColumnDimension('AJ')->setWidth(5); // Set width kolom B
  $excel->getActiveSheet()->getColumnDimension('AK')->setWidth(5); // Set width kolom B
  $excel->getActiveSheet()->getColumnDimension('AL')->setWidth(5); // Set width kolom B

  $numrow += 2;
  //Penanggung Jawab
  $excel->getActiveSheet()->setCellValue('Q' . $numrow, "Cisarua,"); // Set kolom A3 dengan tulisan "NO"
  $excel->getActiveSheet()->mergeCells('Q' . $numrow . ':AL' . $numrow); // Set Merge Cell pada kolom A1 sampai F1
  $excel->getActiveSheet()->getStyle('Q' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom Q

  //Penanggung Jawab
  $excel->getActiveSheet()->setCellValue('B' . $numrow, "Penanggung Jawab"); // Set kolom A3 dengan tulisan "NO"
  $excel->getActiveSheet()->mergeCells('B' . $numrow . ':C' . $numrow); // Set Merge Cell pada kolom A1 sampai F1
  $excel->getActiveSheet()->getStyle('B' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom B
  $numrow;
  $numrow++; //turun ke baris selanjutnya
  //STOCK BAHAN
  $excel->getActiveSheet()->setCellValue('B' . $numrow, "Stok Bahan"); // Set kolom A3 dengan tulisan "NO"
  $excel->getActiveSheet()->mergeCells('B' . $numrow . ':C' . $numrow); // Set Merge Cell pada kolom A1 sampai F1
  $excel->getActiveSheet()->getStyle('B' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom B

  //KEPALA INSTALASI LAUNDRY
  $excel->getActiveSheet()->setCellValue('Q' . $numrow, "Kepala Instalasi Laundry"); // Set kolom A3 dengan tulisan "NO"
  $excel->getActiveSheet()->mergeCells('Q' . $numrow . ':AL' . $numrow); // Set Merge Cell pada kolom A1 sampai F1
  $excel->getActiveSheet()->getStyle('Q' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom B

  $numrow += 3;
  //STOCK BAHAN
  $excel->getActiveSheet()->setCellValue('B' . $numrow, "..."); // Set kolom A3 dengan tulisan "NO"
  $excel->getActiveSheet()->mergeCells('B' . $numrow . ':C' . $numrow); // Set Merge Cell pada kolom A1 sampai F1
  $excel->getActiveSheet()->getStyle('B' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom B
  //KEPALA INSTALASI LAUNDRY
  $excel->getActiveSheet()->setCellValue('Q' . $numrow, "..."); // Set kolom A3 dengan tulisan "NO"
  $excel->getActiveSheet()->mergeCells('Q' . $numrow . ':AL' . $numrow); // Set Merge Cell pada kolom A1 sampai F1
  $excel->getActiveSheet()->getStyle('Q' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom B

  $numrow++;
  //STOCK BAHAN
  $excel->getActiveSheet()->setCellValue('B' . $numrow, "NIK."); // Set kolom A3 dengan tulisan "NO"
  $excel->getActiveSheet()->mergeCells('B' . $numrow . ':C' . $numrow); // Set Merge Cell pada kolom A1 sampai F1
  $excel->getActiveSheet()->getStyle('B' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom B

  //KEPALA INSTALASI LAUNDRY
  $excel->getActiveSheet()->setCellValue('Q' . $numrow, "NIP."); // Set kolom A3 dengan tulisan "NO"
  $excel->getActiveSheet()->mergeCells('Q' . $numrow . ':AL' . $numrow); // Set Merge Cell pada kolom A1 sampai F1
  $excel->getActiveSheet()->getStyle('Q' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom B

  // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
  $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
  foreach (range('D', 'Z') as $columnID) {
    $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
  }
  foreach (range('A', 'I') as $columnID) {
    $excel->getActiveSheet()->getColumnDimension('A' . $columnID)->setAutoSize(true);
  }
  // Set orientasi kertas jadi LANDSCAPE
  $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
  // Set judul file excel nya
  $excel->getActiveSheet(0)->setTitle("Laporan Data Penggunaan Bahan");
  $excel->setActiveSheetIndex(0);
  // Proses file excel
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment; filename="Data Penggunaan Bahan' . $newMonthName . '.xlsx"'); // Set nama file excel nya
  header('Cache-Control: max-age=0');
  $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
  $write->save('php://output');
}
