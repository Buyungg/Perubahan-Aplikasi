<?php
session_start();      

if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
  
  header('location: ../../login.php?pesan=2');
}

else {
  
  require_once "../../config/database.php";

  
  if (isset($_POST['simpan'])) {
    
    $id_barang          = mysqli_real_escape_string($mysqli, $_POST['id_barang']);
    $nama_barang        = mysqli_real_escape_string($mysqli, trim($_POST['nama_barang']));
    $jenis              = mysqli_real_escape_string($mysqli, $_POST['jenis']);
    $stok_minimum       = mysqli_real_escape_string($mysqli, $_POST['stok_minimum']);
    $satuan             = mysqli_real_escape_string($mysqli, $_POST['satuan']);
    
      
      $update = mysqli_query($mysqli, "UPDATE tbl_barang
                                       SET nama_barang='$nama_barang', jenis='$jenis', stok_minimum='$stok_minimum', satuan='$satuan'
                                       WHERE id_barang='$id_barang'")
                                       or die('Ada kesalahan pada query update : ' . mysqli_error($mysqli));
      
      if ($update) {
        
        header('location: ../../main.php?module=barang&pesan=2');
      }
  }
}
