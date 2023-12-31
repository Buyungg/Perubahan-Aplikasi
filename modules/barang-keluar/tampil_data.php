<?php
 
 
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
   
  header('location: 404.html');
}
 
else {
   
   
  if (isset($_GET['pesan'])) {
     
    if ($_GET['pesan'] == 1) {
       
      echo '<div class="alert alert-notify alert-success alert-dismissible fade show" role="alert">
              <span data-notify="icon" class="fas fa-check"></span> 
              <span data-notify="title" class="text-success">Sukses!</span> 
              <span data-notify="message">Data barang keluar berhasil disimpan.</span>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>';
    }
     
    elseif ($_GET['pesan'] == 2) {
       
      echo '<div class="alert alert-notify alert-success alert-dismissible fade show" role="alert">
              <span data-notify="icon" class="fas fa-check"></span> 
              <span data-notify="title" class="text-success">Sukses!</span> 
              <span data-notify="message">Data barang keluar berhasil dihapus.</span>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>';
    }
  }
?>
  <div class="panel-header bg-secondary-gradient">
    <div class="page-inner py-45">
      <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
        <div class="page-header text-white">
           
          <h4 class="page-title text-white"><i class="fas fa-sign-out-alt mr-2"></i> Barang Keluar</h4>
           
          <ul class="breadcrumbs">
            <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a href="?module=barang_keluar" class="text-white">Barang Keluar</a></li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a>Data</a></li>
          </ul>
        </div>
        <div class="ml-md-auto py-2 py-md-0">
           
          <a href="?module=form_entri_barang_keluar" class="btn btn-secondary btn-round">
            <span class="btn-label"><i class="fa fa-plus mr-2"></i></span> Entri Data
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="page-inner mt--5">
    <div class="card">
      <div class="card-header">
         
        <div class="card-title">Data Barang Keluar</div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
           
          <table id="basic-datatables" class="display table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Barang</th>
                <th class="text-center">Jenis</th>
                <th class="text-center">Harga</th>
                <th class="text-center">Jumlah Keluar</th>
                <th class="text-center">Satuan</th>
                <th class="text-center">Total</th>
                <th class="text-center">Diserahkan</th>
                <th class="text-center">Sisa</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
               
              $no = 1;
               
              $query = mysqli_query($mysqli, "SELECT a.id_transaksi, a.tanggalk, a.barang, a.hargak, a.jumlahk, a.totalk, a.serah, b.nama_barang, b.stok, c.nama_satuan, d.nama_jenis
                                              FROM tbl_barang_keluar as a INNER JOIN tbl_barang as b INNER JOIN tbl_satuan as c INNER JOIN tbl_jenis as d 
                                              ON a.barang=b.id_barang AND b.satuan=c.id_satuan AND b.jenis=d.id_jenis
                                              ORDER BY a.id_transaksi DESC")
                                              or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
               
              while ($data = mysqli_fetch_assoc($query)) { ?>
                 
                <tr>
                  <td width="30" class="text-center"><?php echo $no++; ?></td>
                  <td width="100" class="text-center"><?php echo date('d-m-Y', strtotime($data['tanggalk'])); ?></td>
                  <td width="140"><?php echo $data['nama_barang']; ?></td>
                  <td width="10" class="text-center"><?php echo $data['nama_jenis']; ?></td>
                  <td width="90" class="text-center">Rp. <?php echo number_format($data['hargak'], 0, '', '.'); ?></td>
                  <td width="90" class="text-right"><?php echo number_format($data['jumlahk'], 0, '', '.'); ?></td>
                  <td width="60" class="text-center"><?php echo $data['nama_satuan']; ?></td>
                  <td width="110" class="text-center">Rp. <?php echo number_format($data['totalk'], 0, '', '.'); ?></td>
                  <td width="100"><?php echo $data['serah']; ?></td>
                  <td width="50" class="text-right"><?php echo $data['stok']; ?></td>
                  <td width="70" class="text-center">
                    <div>
                       
                      <a href="?module=form_ubah_barang_keluar&id=<?php echo $data['id_transaksi']; ?>" class="btn btn-icon btn-round btn-secondary btn-sm mr-md-1" data-toggle="tooltip" data-placement="top" title="Ubah">
                        <i class="fas fa-pencil-alt fa-sm"></i>
                      </a>
                       
                      <a href="modules/barang-keluar/proses_hapus.php?id=<?php echo $data['id_transaksi']; ?>" onclick="return confirm('Anda yakin ingin menghapus data barang keluar <?php echo $data['id_transaksi']; ?>?')" class="btn btn-icon btn-round btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus">
                        <i class="fas fa-trash fa-sm"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php } ?>