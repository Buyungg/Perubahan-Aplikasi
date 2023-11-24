<?php
 
 
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
   
  require_once "../../config/database.php";

   
  if (isset($_GET['id_barang'])) {
     
    $id_barang = $_GET['id_barang'];

     
    $query = mysqli_query($mysqli, "SELECT a.stok, b.nama_satuan, c.nama_jenis FROM tbl_barang as a INNER JOIN tbl_satuan as b ON a.satuan=b.id_satuan 
                                    INNER JOIN tbl_jenis as c ON a.jenis=c.id_jenis 
                                    WHERE id_barang='$id_barang'")
                                    or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
     
    $data  = mysqli_fetch_assoc($query);

     
    echo json_encode($data);
  }
}
 
else {
   
  header('location: ../../404.html');
}
