<?php
session_start();       

 
 
if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
   
  header('location: ../../login.php?pesan=2');
}
 
else {
   
  require_once "../../config/database.php";

   
  if (isset($_POST['simpan'])) {
     
    $id_barang     = mysqli_real_escape_string($mysqli, $_POST['id_barang']);
    $id_transaksi  = mysqli_real_escape_string($mysqli, $_POST['id_transaksi']);
    $jumlahk       = mysqli_real_escape_string($mysqli, trim($_POST['jumlahk']));
    $hargak        = mysqli_real_escape_string($mysqli, $_POST['hargak']);
    $serah         = mysqli_real_escape_string($mysqli, $_POST['serah']);
    $totalk        = $jumlahk*$hargak;


    $lihatstock = mysqli_query($mysqli, "select * from tbl_barang where id_barang='$id_barang'");
    $stoknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stoknya['stok'];

    $qtyskrg = mysqli_query($mysqli, "select * from tbl_barang_keluar where id_transaksi='$id_transaksi'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['jumlahk'];

    if($jumlahk>$qtyskrg){
      $selisih = $jumlahk-$qtyskrg;
      $kurangin = $stockskrg - $selisih;
      $kurangistocknya = mysqli_query($mysqli, "update tbl_barang set stok='$kurangin' where id_barang='$id_barang'");
      $updatenya = mysqli_query($mysqli, "update tbl_barang_keluar  SET jumlahk='$jumlahk', hargak='$hargak', totalk='$totalk', serah='$serah'  WHERE id_transaksi='$id_transaksi'");
      if($kurangistocknya&&$updatenya) {
        header('location:../../main.php?module=barang_keluar&pesan=1');
      } else {
                echo "
                <script>
                alert('Data Gagal Ditambahkan');
                document.location.href = '../../main.php?module=barang_keluar';
                </script>
                ";
              }
            } else {
              $selisih = $qtyskrg-$jumlahk;
              $kurangin = $stockskrg + $selisih;
              $kurangistocknya = mysqli_query($mysqli, "update tbl_barang set stok='$kurangin' where id_barang='$id_barang'");
              $updatenya = mysqli_query($mysqli, "update tbl_barang_keluar  SET jumlahk='$jumlahk', hargak='$hargak', totalk='$totalk', serah='$serah'  WHERE id_transaksi='$id_transaksi'");
              if($kurangistocknya&&$updatenya){
                  header('location:../../main.php?module=barang_keluar&pesan=1');
          } else {
              echo "
                      <script>
                      alert('Data Gagal Ditambahkan');
                      document.location.href = '../../main.php?module=barang_keluar';
                      </script>
                      ";
            } 
          }
      }
  }
