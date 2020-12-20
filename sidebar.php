<div class="sidebar">
  <h1><a href="/">Laundry App</a></h1>

  <ul type="none">
    <li class="menu_utama">Cucian</li>
    <ul>
      <?php if (is_role('admin')) : ?>
        <li><a href="/buat_laporan_cucian/index.php">Buat Laporan</a></li>
      <?php endif ?>
      <li><a href="/serah-terima/index.php">Serah Terima</a></li>
      <?php if (is_role('admin')) : ?>
        <li><a href="/spesifikasi/index.php">Spesifikasi</a></li>
        <li><a href="/jenis-linen/index.php">Jenis Linen</a></li>
        <li><a href="/ruangan/index.php">Ruangan</a></li>
        <li><a href="/pegawai/index.php">Pegawai</a></li>
      <?php endif ?>
    </ul>
    <?php if (is_role('admin')) : ?>
      <li class="menu_utama">Barang</li>
      <ul>
        <li><a href="/buat_laporan_barang/index.php">Buat Laporan</a></li>
        <li><a href="/barang-keluar/index.php">Catat Pemakaian Barang</a></li>
        <li><a href="/barang-masuk/index.php">Catat Barang Masuk</a></li>
        <li><a href="/barang/index.php">Barang</a></li>
        <li><a href="/satuan/index.php">Satuan</a></li>
        <li><a href="/jenis/index.php">Jenis</a></li>
        <li><a href="/kategori/index.php">Kategori</a></li>
      </ul>
    <?php endif ?>
    <br>
    <br>
    <li>
      <form action="/auth/logout.php">
        <button type="submit">Logout</button>
      </form>
    </li>
  </ul>

</div>