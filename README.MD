# Laundry of Mine
Projek ini digunakan untuk pembelajaran PHP dasar dengan studi kasus laundry di RSJ

## Environment
- Apache Web Server
- PHP
- MySQL

## Instalasi Environment
Agar tidak install environment satu per satu disarankan menggunakan Laragon atau XAMPP, kebetulan yang saya gunakan pada saat membuat project ini adalah Laragon

## Fitur fitur yang ada pada project ini
1. Kelola data Barang
   1. CRUD kategori
   2. CRUD jenis
   3. CRUD barang
   4. Catat Barang Masuk
   5. Catat Barang Keluar
   6. Buat Laporan ke Excel
2. Kelola Cucian
   1. CRUD Pegawai
   2. CRUD Ruangan
   3. CRUD Jenis Linen
   4. CRUD Spesifikasi Linen
   5. Catat Serah Terima Cucian & CRUD Detailnya
   6. Buat Laporan ke Excel
3. Login
4. Logout
5. Check Roles

## Library yang digunakan
- [PHP Excel](https://github.com/PHPOffice/PHPExcel)

## Langkah-langkah Instalasi Project
1. download project
2. taruh di folder htdocs(jika menggunakan XAMPP) atau sesuaikan saja dengan folder web server anda
3. buat database **'laundry-mine'** pada MySQL anda
4. Import **laundry-mine.sql** yang ada pada **folder sql**
5. buka **koneksi.php** dan sesuaikan username, password dan database anda (jika diperlukan) 
6. Lalu silahkan buka url project nya 
   - http://localhost/laundry-mine (jika menggunakan XAMPP)
   - http://laundry-mine.test (jika menggunakan Laragon) 
   - atau sesuaikan dengan environment anda masing masing

#### Login default
   1. nip : 123456
   2. password : 123456

## Catatan
1. jika pada saat buat laporan terjadi error mungkin itu dikarenakan [PHP Excel](https://github.com/PHPOffice/PHPExcel) yang tidak support dengan versi PHP diatas 7, maka dari itu disarankan menggunakan **Versi PHP dibawah 7**
2. jika link ada yang tidak sesuai silahkan cek dimasing masing href pada tag a

Semoga Bermanfaat