<?php
session_start();      // mengaktifkan session

// pengecekan session login user 
// jika user belum login
if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
  // alihkan ke halaman login dan tampilkan pesan peringatan login
  header('location: ../../login.php?pesan=2');
}
// jika user sudah login, maka jalankan perintah untuk update
else {
  // panggil file "database.php" untuk koneksi ke database
  require_once "../../config/database.php";

  // mengecek data hasil submit dari form
  if (isset($_POST['simpan'])) {
    // ambil data hasil submit dari form
    $id_barang          = mysqli_real_escape_string($mysqli, $_POST['id_barang']);
    $nama_barang        = mysqli_real_escape_string($mysqli, trim($_POST['nama_barang']));
    $jenis              = mysqli_real_escape_string($mysqli, $_POST['jenis']);
    $stok_minimum       = mysqli_real_escape_string($mysqli, $_POST['stok_minimum']);
    $satuan             = mysqli_real_escape_string($mysqli, $_POST['satuan']);

    // mengecek data foto dari form ubah data
    // jika data foto tidak ada (foto tidak diubah)
    if (empty($nama_file)) {
      // sql statement untuk update data di tabel "tbl_barang" berdasarkan "id_barang"
      $update = mysqli_query($mysqli, "UPDATE tbl_barang
                                       SET nama_barang='$nama_barang', jenis='$jenis', stok_minimum='$stok_minimum', satuan='$satuan'
                                       WHERE id_barang='$id_barang'")
                                       or die('Ada kesalahan pada query update : ' . mysqli_error($mysqli));
      // cek query
      // jika proses update berhasil
      if ($update) {
        // alihkan ke halaman barang dan tampilkan pesan berhasil ubah data
        header('location: ../../main.php?module=barang&pesan=2');
      }
    }
  }
}
