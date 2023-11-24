<?php
session_start();       

 
 
if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
   
  header('location: ../../login.php?pesan=2');
}
 
else {
   
  require_once "../../config/database.php";

   
  if (isset($_POST['simpan'])) {
     
    $id_transaksi = mysqli_real_escape_string($mysqli, $_POST['id_transaksi']);
    $tanggalm     = mysqli_real_escape_string($mysqli, trim($_POST['tanggalm']));
    $barang       = mysqli_real_escape_string($mysqli, $_POST['barang']);
    $nomor        = mysqli_real_escape_string($mysqli, $_POST['nomor']);
    $hargam       = mysqli_real_escape_string($mysqli, $_POST['hargam']);
    $jumlahm      = mysqli_real_escape_string($mysqli, $_POST['jumlahm']);
    $dari         = mysqli_real_escape_string($mysqli, $_POST['dari']);
    $totalm       = $jumlahm*$hargam;
    $guna         = mysqli_real_escape_string($mysqli, $_POST['guna']);

     
    $tanggal_masuk = date('Y-m-d', strtotime($tanggalm));

     
    $insert = mysqli_query($mysqli, "INSERT INTO tbl_barang_masuk(id_transaksi, tanggalm, barang, nomor, hargam, jumlahm, dari, totalm, guna) 
                                     VALUES('$id_transaksi', '$tanggal_masuk', '$barang', '$nomor', '$hargam', '$jumlahm', '$dari', '$totalm', '$guna')")
                                     or die('Ada kesalahan pada query insert : ' . mysqli_error($mysqli));
     
     
    if ($insert) {
       
      header('location: ../../main.php?module=barang_masuk&pesan=1');
    }
  }
}
