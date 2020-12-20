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
  //mengambil id dari transaksi barang masuk sesuai dengan tahun dan bulan yang diminta
  $subquery2 = "SELECT id
  FROM trs_barang_masuk
  WHERE year(created_at) = '$year'
    AND month(created_at) = '$month'";

  //mengambil id, barang_id, jumlah_barang, tanggal, dan day dari subquery2
  $subquery1 = "SELECT id, barang_id, jumlah_barang, date(created_at) as tanggal, day(created_at) as day
  FROM trs_barang_masuk
  WHERE id IN ($subquery2)";

  //mengambil barang_id dan jumlah_barang dari subquery1 dan di kelompokan berdasarkan barang_id
  return query("SELECT barang_id, sum(jumlah_barang) as jumlah_barang FROM ($subquery1)  as subquery
  GROUP BY barang_id;");
}

function getData($year, $month)
{
  $barang = getDataBarang(); //mengambil data barang
  $stockAwal = getStockAwal($year, $month); //mengambil data stok awal
  $stockAkhir = getStockAkhir($year, $month); //mengambil data stok akhir
  $pemakaianBarang = getPemakaianBarang($year, $month); //mengambil data pemakaian barang
  $pemasukanBarang = getPemasukanBarang($year, $month); //mengambil data pemasukan barang

  $defaultPemakaianBarang = []; //menyiapkan variabel penampung untuk nilai default pemakaian barang
  for ($hari = 1; $hari <= 31; $hari++) {
    // set pemakaian barang dari tanggal 1 - 31 bernilai string kosong (atau bisa juga bernilai 0)
    $defaultPemakaianBarang[$hari] = '';
  }

  /*
  * menggabungkan beberapa data kedalam 1 varibel, adapun rincian data yang digabungkan antara lain:
  * - barang, 
  * - stock awal, 
  * - stock akhir,
  * - pemakaian barang
  * - pemasukan barang 
  */
  $tempValue = [];
  foreach ($barang as $key => $value) {
    $tempValue[] = $value; //menampung data barang kedalam data sementara (agar data yang dimanipluasi adalah data yang sementara)

    //set nilai default stock awal pada brang ke $key menjadi 0;
    $tempValue[$key]['stock_awal'] = 0;

    //mencari data stockawal sesuai dengan barang yang ada di $key
    foreach ($stockAwal as $valueSA) {
      //jika barang ada stock awal
      if ($tempValue[$key]['id'] == $valueSA['barang_id']) {
        //maka set nilai stock awal sesuai dengan jumlah barang
        $tempValue[$key]['stock_awal'] = $valueSA['jumlah_barang'];
        break; //hentikan proses pencarian
      }
    }

    //set nilai default stock akhir pada brang ke $key menjadi 0;
    $tempValue[$key]['stock_akhir'] = 0;
    //mencari data stock akhir sesuai dengan barang yang ada di $key
    foreach ($stockAkhir as $valueSA) {
      //jika barang ada stock akhir
      if ($tempValue[$key]['id'] == $valueSA['barang_id']) {
        //maka set nilai stock akhir sesuai dengan jumlah barang
        $tempValue[$key]['stock_akhir'] = $valueSA['jumlah_barang'];
        break; //hentikan proses pencarian
      }
    }

    //set nilai default pemasukan pada brang ke $key menjadi 0;
    $tempValue[$key]['pemasukan_barang'] = 0;
    //mencari data pemasukan barang sesuai dengan barang yang ada di $key
    foreach ($pemasukanBarang as $valuePB) {
      //jika barang ada pemasukan
      if ($tempValue[$key]['id'] == $valuePB['barang_id']) {
        //maka set nilai pemasukan barang sesuai dengan jumlah barang
        $tempValue[$key]['pemasukan_barang'] = $valuePB['jumlah_barang'];
        break; //hentikan proses pencarian
      }
    }

    //set nilai default pemakaian barang pada brang ke $key menjadi (sesuai dengan isi dari variable default pemakaian barang)
    $tempValue[$key]['pemakaian_barang'] = $defaultPemakaianBarang;
    //set nilai default total pemakian pada brang ke $key menjadi 0;
    $tempValue[$key]['total_pemakaian_barang'] = 0;
    //mencari data pemakaian barang sesuai dengan barang yang ada di $key
    foreach ($pemakaianBarang as $valuePB) {
      //jika barang ada pemakaian
      if ($tempValue[$key]['id'] == $valuePB['barang_id']) {
        //maka hitung total pemakaian barang sesuai dengan total pemakaian sekarang ditambah dengan jumlah barang
        $tempValue[$key]['total_pemakaian_barang'] += $valuePB['jumlah_barang'];
        //maka set nilai pemakaian barang sesuai dengan jumlah barang
        $tempValue[$key]['pemakaian_barang'][$valuePB['day']] = $valuePB['jumlah_barang'];
      }
    }
  }
  //balikan data sementara nya ke pemanggil
  return $tempValue;
}

function buat_laporan($data)
{
  global $conn;
  $month_year = htmlspecialchars($data["month_year"]); //mengambil bulan dan tahun dari pengguna
  $month_year_sec = mysqli_real_escape_string($conn, $month_year); //cek string yang sekiranya bisa mengakibatkan sql injection (demi keamanan)
  $temp = new DateTime($month_year_sec . '-01'); //konversi string bulan dan tahun ke format DateTime pada PHP (sehingga format nya bisa diubah ubah)
  $month = $temp->format('m'); //ambil bulan nya aja
  $year = $temp->format('Y'); //ambil tahun nya aja
  $newMonthName = $temp->format('F Y'); //atur format nya menjadi (bulan YYYY) contoh: (July 2020)

  $data = getData($year, $month); //ambil data yang dibutuhkan

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
  // Buat sebuah variobel untuk menampung pengaturan style dari kolom tanggal pemakaian
  $style_bg_grey = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'DDDDDD')
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
  $excel->getActiveSheet()->mergeCells('A5:AM5'); // Set Merge Cell pada kolom A5 sampai AM5
  $excel->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE); // Set bold kolom A5
  $excel->getActiveSheet()->getStyle('A5')->getFont()->setSize(15); // Set font size 15 untuk kolom A5
  $excel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A5

  $excel->setActiveSheetIndex(0)->setCellValue('A6', "INSTALASI LAUNDRY"); // Set kolom A6 dengan tulisan "INSTALASI LAUNDRY"
  $excel->getActiveSheet()->mergeCells('A6:AM6'); // Set Merge Cell pada kolom A6 sampai M6
  $excel->getActiveSheet()->getStyle('A6')->getFont()->setBold(TRUE); // Set bold kolom A6
  $excel->getActiveSheet()->getStyle('A6')->getFont()->setSize(15); // Set font size 15 untuk kolom A6
  $excel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A6

  $excel->setActiveSheetIndex(0)->setCellValue('A7', "BULAN: $newMonthName"); // Set kolom A7 dengan tulisan Contoh "BULAN: AGUSTUS 2020"
  $excel->getActiveSheet()->mergeCells('A7:AM7'); // Set Merge Cell pada kolom A7 sampai AM7
  $excel->getActiveSheet()->getStyle('A7')->getFont()->setBold(TRUE); // Set bold kolom A7
  $excel->getActiveSheet()->getStyle('A7')->getFont()->setSize(15); // Set font size 15 untuk kolom A7
  $excel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A7

  // Buat header tabel nya pada baris ke 
  $excel->getActiveSheet()->setCellValue('A9', "NO"); // Set kolom A3 dengan tulisan "NO"
  $excel->getActiveSheet()->mergeCells('A9:A10'); // Set Merge Cell pada kolom A9 sampai A10
  $excel->getActiveSheet()->setCellValue('B9', "NAMA BAHAN"); // Set kolom B3 dengan tulisan "NAMA BAHAN"
  $excel->getActiveSheet()->mergeCells('B9:B10'); // Set Merge Cell pada kolom B9 sampai B10
  $excel->getActiveSheet()->setCellValue('C9', "STOK AWAL"); // Set kolom C3 dengan tulisan "STOCK AWAL"
  $excel->getActiveSheet()->mergeCells('C9:C10'); // Set Merge Cell pada kolom C9 sampai C10
  $excel->getActiveSheet()->setCellValue('D9', "PEMAKAIAN"); // Set kolom C3 dengan tulisan "PEMAKAIAN"
  $excel->getActiveSheet()->mergeCells('D9:AH9'); // Set Merge Cell pada kolom D9 sampai AH9
  $excel->getActiveSheet()->setCellValue('AJ9', "MASUK"); // Set kolom C3 dengan tulisan "MASUK"
  $excel->getActiveSheet()->mergeCells('AJ9:AL9'); // Set Merge Cell pada kolom AJ9 sampai AL9
  $excel->getActiveSheet()->setCellValue('AM9', "STOCK AKHIR"); // Set kolom C3 dengan tulisan "STOCK AKHIR"
  $excel->getActiveSheet()->mergeCells('AM9:AM10'); // Set Merge Cell pada kolom AM9 sampai AM10

  // mendaftarkan tanggal 1-31 pada kolom D sampai AH
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

  $excel->getActiveSheet()->setCellValue('AI10', 'Σ'); // Set kolom AI10 dengan tulisan 'Σ'
  $excel->getActiveSheet()->setCellValue('AL10', 'Σ'); // Set kolom AL10 dengan tulisan 'Σ'

  // Apply style tanggal pemakaian
  $excel->getActiveSheet()->getStyle('D10:AL10')->applyFromArray($style_bg_grey);
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

  // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
  $excel->getActiveSheet()->getStyle('A11:AM' . ($numrow - 1))->applyFromArray($style_row);

  // Set width kolom
  $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
  $excel->getActiveSheet()->getColumnDimension('B')->setWidth(20); // Set width kolom B
  $excel->getActiveSheet()->getColumnDimension('C')->setWidth(20); // Set width kolom C
  $excel->getActiveSheet()->getColumnDimension('AM')->setWidth(20); // Set width kolom AM
  $excel->getActiveSheet()->getColumnDimension('AJ')->setWidth(5); // Set width kolom AJ
  $excel->getActiveSheet()->getColumnDimension('AK')->setWidth(5); // Set width kolom AK
  $excel->getActiveSheet()->getColumnDimension('AL')->setWidth(5); // Set width kolom AL

  $numrow += 2; //turun ke 2 baris selanjutnya
  //Tempat, Tanggal
  $excel->getActiveSheet()->setCellValue('Q' . $numrow, "Cisarua,"); // Set kolom Q dengan tulisan "Cisarua,"
  $excel->getActiveSheet()->mergeCells('Q' . $numrow . ':AL' . $numrow); // Set Merge Cell pada kolom Q sampai AL
  $excel->getActiveSheet()->getStyle('Q' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom Q

  //Penanggung Jawab
  $excel->getActiveSheet()->setCellValue('B' . $numrow, "Penanggung Jawab"); // Set kolom B dengan tulisan "Penanggung Jawab"
  $excel->getActiveSheet()->mergeCells('B' . $numrow . ':C' . $numrow); // Set Merge Cell pada kolom B sampai C $numrow
  $excel->getActiveSheet()->getStyle('B' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom B

  $numrow++; //turun ke baris selanjutnya
  //STOCK BAHAN
  $excel->getActiveSheet()->setCellValue('B' . $numrow, "Stok Bahan"); // Set kolom B dengan tulisan "Stok Bahan"
  $excel->getActiveSheet()->mergeCells('B' . $numrow . ':C' . $numrow); // Set Merge Cell pada kolom B sampai C $numrow
  $excel->getActiveSheet()->getStyle('B' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom B

  //KEPALA INSTALASI LAUNDRY
  $excel->getActiveSheet()->setCellValue('Q' . $numrow, "Kepala Instalasi Laundry"); // Set kolom Q dengan tulisan "Kepala Instalasi Laundry"
  $excel->getActiveSheet()->mergeCells('Q' . $numrow . ':AL' . $numrow); // Set Merge Cell pada kolom Q sampai AL $numrow
  $excel->getActiveSheet()->getStyle('Q' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom Q

  $numrow += 3; //turun ke 3 baris selanjutnya

  //STOCK BAHAN
  $excel->getActiveSheet()->setCellValue('B' . $numrow, "..."); // Set kolom B dengan tulisan "..." (bisa juga diganti dengan nama penanggung jawabnya jika ada datanya
  $excel->getActiveSheet()->mergeCells('B' . $numrow . ':C' . $numrow); // Set Merge Cell pada kolom B sampai C $numrow
  $excel->getActiveSheet()->getStyle('B' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom B
  //KEPALA INSTALASI LAUNDRY
  $excel->getActiveSheet()->setCellValue('Q' . $numrow, "..."); // Set kolom Q dengan tulisan "..." bisa juga di ganti dengan nama KEPALA INSTALASI LAUNDRY jika sudah ada datanya
  $excel->getActiveSheet()->mergeCells('Q' . $numrow . ':AL' . $numrow); // Set Merge Cell pada kolom Q sampai AL
  $excel->getActiveSheet()->getStyle('Q' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom Q

  $numrow++;
  //STOCK BAHAN
  $excel->getActiveSheet()->setCellValue('B' . $numrow, "NIK."); // Set kolom B dengan tulisan "NIK."
  $excel->getActiveSheet()->mergeCells('B' . $numrow . ':C' . $numrow); // Set Merge Cell pada kolom B sampai C
  $excel->getActiveSheet()->getStyle('B' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom B

  //KEPALA INSTALASI LAUNDRY
  $excel->getActiveSheet()->setCellValue('Q' . $numrow, "NIP."); // Set kolom Q dengan tulisan "NIP."
  $excel->getActiveSheet()->mergeCells('Q' . $numrow . ':AL' . $numrow); // Set Merge Cell pada kolom Q sampai AL
  $excel->getActiveSheet()->getStyle('Q' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom Q

  // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
  $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
  // Set Width kolom pemakaian menjadi auto (mengikuti width isi dari kolommnya, jadi otomatis)
  foreach ($kolom_pemakaian as $columnID) {
    $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
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
