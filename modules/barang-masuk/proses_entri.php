<?php
session_start();      // mengaktifkan session

// pengecekan session login user 
// jika user belum login
if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
  // alihkan ke halaman login dan tampilkan pesan peringatan login
  header('location: ../../login.php?pesan=2');
}
// jika user sudah login, maka jalankan perintah untuk insert
else {
  // panggil file "database.php" untuk koneksi ke database
  require_once "../../config/database.php";

  // mengecek data hasil submit dari form
  if (isset($_POST['simpan'])) {
    // ambil data hasil submit dari form
    $id_transaksi  = mysqli_real_escape_string($mysqli, $_POST['id_transaksi']);
    $tanggalm       = mysqli_real_escape_string($mysqli, trim($_POST['tanggalm']));
    $barang        = mysqli_real_escape_string($mysqli, $_POST['barang']);
    $nomor       = mysqli_real_escape_string($mysqli, $_POST['nomor']);
    $hargam        = mysqli_real_escape_string($mysqli, $_POST['hargam']);
    $jumlahm        = mysqli_real_escape_string($mysqli, $_POST['jumlahm']);
    $dari        = mysqli_real_escape_string($mysqli, $_POST['dari']);
    $totalm        = $jumlahm*$hargam;

    // ubah format tanggal menjadi Tahun-Bulan-Hari (Y-m-d) sebelum disimpan ke database
    $tanggal_masuk = date('Y-m-d', strtotime($tanggalm));

    // sql statement untuk insert data ke tabel "tbl_barang_masuk"
    $insert = mysqli_query($mysqli, "INSERT INTO tbl_barang_masuk(id_transaksi, tanggalm, barang, nomor, hargam, jumlahm, dari, totalm) 
                                     VALUES('$id_transaksi', '$tanggal_masuk', '$barang', '$nomor', '$hargam', '$jumlahm', '$dari', '$totalm')")
                                     or die('Ada kesalahan pada query insert : ' . mysqli_error($mysqli));
    // cek query
    // jika proses insert berhasil
    if ($insert) {
      // alihkan ke halaman barang masuk dan tampilkan pesan berhasil simpan data
      header('location: ../../main.php?module=barang_masuk&pesan=1');
    }
  }
}
