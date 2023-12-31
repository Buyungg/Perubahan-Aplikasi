<?php
 
 
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
   
  header('location: 404.html');
}
 
else {
   
  if (isset($_GET['id'])) {
     
    $id_transaksi = $_GET['id'];

     
    $query = mysqli_query($mysqli, "SELECT  a.id_transaksi, a.barang, a.nomor, a.dari, a.jumlahm, a.hargam, a.guna, b.id_barang, b.nama_barang
                          FROM tbl_barang_masuk as a INNER JOIN tbl_barang as b
                          ON a.barang=b.id_barang 
                          WHERE id_transaksi='$id_transaksi'")
                          or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
     
    $data = mysqli_fetch_assoc($query);
  }
?>
  <div class="panel-header bg-secondary-gradient">
    <div class="page-inner py-4">
      <div class="page-header text-white">
          
         <h4 class="page-title text-white"><i class="fas fa-sign-in-alt mr-2"></i>Ubah Data Barang Masuk</h4>
         
        <ul class="breadcrumbs">
          <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a href="?module=barang_masuk" class="text-white">Barang Masuk</a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a>Ubah</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="page-inner mt--5">
    <div class="card">
      <div class="card-header">
         
        <div class="card-title">Ubah Data Barang Masuk</div>
      </div>
       
      <form action="modules/barang-masuk/proses_ubah.php" method="post" class="needs-validation" novalidate>
        <div class="card-body">
          <input type="hidden" name="id_transaksi" value="<?php echo $data['id_transaksi']; ?>">
          <input type="hidden" name="id_barang" value="<?php echo $data['id_barang']; ?>">

          <div class="form-group">
            <label>Nama Barang <span class="text-danger">*</span></label>
            <input type="text" name="nama_barang" class="form-control col-lg-5" autocomplete="off" value="<?php echo $data['nama_barang']; ?>" readonly>
            <div class="invalid-feedback">Nomor barang tidak boleh kosong.</div>
          </div>

          <div class="form-group">
            <label>Nomor <span class="text-danger">*</span></label>
            <input type="text" name="nomor" class="form-control col-lg-5" autocomplete="off" value="<?php echo $data['nomor']; ?>" required>
            <div class="invalid-feedback">Nomor barang tidak boleh kosong.</div>
          </div>

          <div class="form-group">
            <label>Barang Dari <span class="text-danger">*</span></label>
            <input type="text" name="dari" class="form-control col-lg-5" autocomplete="off" value="<?php echo $data['dari']; ?>" required>
            <div class="invalid-feedback">Barang dari tidak boleh kosong.</div>
          </div>

          <div class="form-group">
            <label>Jumlah Masuk <span class="text-danger">*</span></label>
            <input type="text" name="jumlahm" class="form-control col-lg-5" autocomplete="off" value="<?php echo $data['jumlahm']; ?>" required>
            <div class="invalid-feedback">Jumlah barang tidak boleh kosong.</div>
          </div>

          <div class="form-group">
            <label>Harga Satuan <span class="text-danger">*</span></label>
            <input type="text" name="hargam" class="form-control col-lg-5" autocomplete="off" value="<?php echo $data['hargam']; ?>" required>
            <div class="invalid-feedback">Harga barang tidak boleh kosong.</div>
          </div>

          <div class="form-group">
            <label> Dipergunakan oleh Unit <span class="text-danger">*</span></label>
            <input type="text" name="guna" class="form-control col-lg-5" autocomplete="off" value="<?php echo $data['guna']; ?>">
            <div class="invalid-feedback">dipergunakan tidak boleh kosong.</div>
          </div>

        </div>
        <div class="card-action">
           
          <input type="submit" name="simpan" value="Simpan" class="btn btn-secondary btn-round pl-4 pr-4 mr-2">
           
          <a href="?module=barang_masuk" class="btn btn-default btn-round pl-4 pr-4">Batal</a>
        </div>
      </form>
    </div>
  </div>
<?php } ?>