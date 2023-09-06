<?php
// mencegah direct access file PHP agar file PHP tidak bisa diakses secara langsung dari browser dan hanya dapat dijalankan ketika di include oleh file lain
// jika file diakses secara langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
  // alihkan ke halaman error 404
  header('location: 404.html');
}
// jika file di include oleh file lain, tampilkan isi file
else {
  // mengecek data GET "id_transaksi"
  if (isset($_GET['id'])) {
    // ambil data GET dari tombol ubah
    $id_transaksi = $_GET['id'];

    // sql statement untuk menampilkan data dari tabel "tbl_masuk" berdasarkan "id_transaksi"
    $query = mysqli_query($mysqli, "SELECT  a.id_transaksi, a.barang, a.jumlahk, a.hargak, a.serah, b.id_barang, b.nama_barang
                          FROM tbl_barang_keluar as a INNER JOIN tbl_barang as b
                          ON a.barang=b.id_barang 
                          WHERE id_transaksi='$id_transaksi'")
                          or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
    // ambil data hasil query
    $data = mysqli_fetch_assoc($query);
  }
?>
  <div class="panel-header bg-secondary-gradient">
    <div class="page-inner py-4">
      <div class="page-header text-white">
         <!-- judul halaman -->
         <h4 class="page-title text-white"><i class="fas fa-sign-in-alt mr-2"></i>Ubah Data Barang Keluar</h4>
        <!-- breadcrumbs -->
        <ul class="breadcrumbs">
          <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a href="?module=barang_Keluar" class="text-white">Barang Keluar</a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a>Ubah</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="page-inner mt--5">
    <div class="card">
      <div class="card-header">
        <!-- judul form -->
        <div class="card-title">Ubah Data Barang Keluar</div>
      </div>
      <!-- form ubah data -->
      <form action="modules/barang-keluar/proses_ubah.php" method="post" class="needs-validation" novalidate>
        <div class="card-body">
          <input type="hidden" name="id_transaksi" value="<?php echo $data['id_transaksi']; ?>">
          <input type="hidden" name="id_barang" value="<?php echo $data['id_barang']; ?>">

          <div class="form-group">
            <label>Nama Barang <span class="text-danger">*</span></label>
            <input type="text" name="nama_barang" class="form-control col-lg-5" autocomplete="off" value="<?php echo $data['nama_barang']; ?>" readonly>
            <div class="invalid-feedback">Nomor barang tidak boleh kosong.</div>
          </div>

          <div class="form-group">
            <label>Jumlah Keluar <span class="text-danger">*</span></label>
            <input type="text" name="jumlahk" class="form-control col-lg-5" autocomplete="off" value="<?php echo $data['jumlahk']; ?>" required>
            <div class="invalid-feedback">Jumlah barang tidak boleh kosong.</div>
          </div>

          <div class="form-group">
            <label>Harga Satuan <span class="text-danger">*</span></label>
            <input type="text" name="hargak" class="form-control col-lg-5" autocomplete="off" value="<?php echo $data['hargak']; ?>" readonly>
            <div class="invalid-feedback">Jumlah barang tidak boleh kosong.</div>
          </div>

          <div class="form-group">
            <label>Diserahkan <span class="text-danger">*</span></label>
            <input type="text" name="serah" class="form-control col-lg-5" autocomplete="off" value="<?php echo $data['serah']; ?>" required>
            <div class="invalid-feedback">Jumlah barang tidak boleh kosong.</div>
          </div>

        </div>
        <div class="card-action">
          <!-- tombol simpan data -->
          <input type="submit" name="simpan" value="Simpan" class="btn btn-secondary btn-round pl-4 pr-4 mr-2">
          <!-- tombol kembali ke halaman data jenis barang -->
          <a href="?module=barang_keluar" class="btn btn-default btn-round pl-4 pr-4">Batal</a>
        </div>
      </form>
    </div>
  </div>
<?php } ?>