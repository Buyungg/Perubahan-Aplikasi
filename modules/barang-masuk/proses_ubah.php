<?php
session_start();       

 
 
if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
   
  header('location: ../../login.php?pesan=2');
}
 
else {
   
  require_once "../../config/database.php";

   
  if (isset($_POST['simpan'])) {
     
    $id_barang = mysqli_real_escape_string($mysqli, $_POST['id_barang']);
    $id_transaksi  = mysqli_real_escape_string($mysqli, $_POST['id_transaksi']);
    $nomor       = mysqli_real_escape_string($mysqli, trim($_POST['nomor']));
    $dari        = mysqli_real_escape_string($mysqli, $_POST['dari']);
    $jumlahm        = mysqli_real_escape_string($mysqli, $_POST['jumlahm']);
    $hargam        = mysqli_real_escape_string($mysqli, $_POST['hargam']);
    $totalm        = $jumlahm*$hargam;
    $guna       = mysqli_real_escape_string($mysqli, $_POST['guna']);

    $lihatstock = mysqli_query($mysqli, "select * from tbl_barang where id_barang='$id_barang'");
    $stoknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stoknya['stok'];

    $qtyskrg = mysqli_query($mysqli, "select * from tbl_barang_masuk where id_transaksi='$id_transaksi'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['jumlahm'];

    if($jumlahm>$qtyskrg){
      $selisih = $jumlahm-$qtyskrg;
      $kurangin = $stockskrg + $selisih;
      $kurangistocknya = mysqli_query($mysqli, "update tbl_barang set stok='$kurangin' where id_barang='$id_barang'");
      $updatenya = mysqli_query($mysqli, "update tbl_barang_masuk  SET nomor='$nomor', dari='$dari', jumlahm='$jumlahm', hargam='$hargam', totalm='$totalm', guna='$guna'  WHERE id_transaksi='$id_transaksi'");
      if($kurangistocknya&&$updatenya) {
        header('location:../../main.php?module=barang_masuk&pesan=1');
      } else {
                echo "
                <script>
                alert('Data Gagal Ditambahkan');
                document.location.href = '../../main.php?module=barang_masuk';
                </script>
                ";
              }
            } else {
              $selisih = $qtyskrg-$jumlahm;
              $kurangin = $stockskrg - $selisih;
              $kurangistocknya = mysqli_query($mysqli, "update tbl_barang set stok='$kurangin' where id_barang='$id_barang'");
              $updatenya = mysqli_query($mysqli, "update tbl_barang_masuk SET nomor='$nomor', dari='$dari', jumlahm='$jumlahm', hargam='$hargam', totalm='$totalm', guna='$guna'  WHERE id_transaksi='$id_transaksi'");
              if($kurangistocknya&&$updatenya){
                  header('location:../../main.php?module=barang_masuk&pesan=1');
          } else {
              echo "
                      <script>
                      alert('Data Gagal Ditambahkan');
                      document.location.href = '../../main.php?module=barang_masuk';
                      </script>
                      ";
            } 
          }
      }
  }
