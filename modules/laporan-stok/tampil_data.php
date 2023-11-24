<?php
 
 
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
   
  header('location: 404.html');
}
 
else { ?>
  <div class="panel-header bg-secondary-gradient">
    <div class="page-inner py-4">
      <div class="page-header text-white">
         
        <h4 class="page-title text-white"><i class="fas fa-file-signature mr-2"></i> Laporan Stok</h4>
         
        <ul class="breadcrumbs">
          <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a>Laporan</a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a>Stok</a></li>
        </ul>
      </div>
    </div>
  </div>

  <?php
   
   
  if (!isset($_POST['tampil'])) { ?>
    <div class="page-inner mt--5">
      <div class="card">
        <div class="card-header">
           
          <div class="card-title">Filter Data Stok</div>
        </div>
         
        <div class="card-body">
          <form action="?module=laporan_stok" method="post" class="needs-validation" novalidate>
            <div class="row">
              <div class="col-lg-5">
              <div class="form-group">
                  <label>Jenis Barang<span class="text-danger">*</span></label>
                  <select name="jenis_barang" class="form-control chosen-select" autocomplete="off" required>
                  <option selected disabled value="">-- Pilih --</option>
                  <?php
                   
                  $query_jenis = mysqli_query($mysqli, "SELECT id_jenis, nama_jenis FROM tbl_jenis ORDER BY id_jenis ASC")
                                                         or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                   
                  while ($data_jenis = mysqli_fetch_assoc($query_jenis)) {
                     
                    echo "<option>$data_jenis[nama_jenis]</option>";
                  }
                  ?>
                </select>
                  <div class="invalid-feedback">Pilih Jenis Barang</div>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="form-group pt-3">
                   
                  <input type="submit" name="tampil" value="Tampilkan" class="btn btn-secondary btn-round btn-block mt-4">
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php
  }
   
  else {
     
    $jenis_barang = $_POST['jenis_barang'];
  ?>
    <div class="page-inner mt--5">
      <div class="card">
        <div class="card-header">
           
          <div class="card-title">Filter Data Stok</div>
        </div>
         
        <div class="card-body">
          <form action="?module=laporan_stok" method="post" class="needs-validation" novalidate>
            <div class="row">
              <div class="col-lg-5">
              <div class="form-group">
                  <label>Jenis Barang</label>
                  <select name="jenis_barang" class="form-control chosen-select" autocomplete="off" required>
                  <option selected disabled value=""><?php echo $jenis_barang; ?></option>
                  <?php
                   
                  $query_jenis = mysqli_query($mysqli, "SELECT id_jenis, nama_jenis FROM tbl_jenis ORDER BY id_jenis ASC")
                                                         or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                   
                  while ($data_jenis = mysqli_fetch_assoc($query_jenis)) {
                     
                    echo "<option >$data_jenis[nama_jenis]</option>";
                  }
                  ?>
                </select>
                  <div class="invalid-feedback"></div>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="form-group pt-3">
                   
                  <input type="submit" name="tampil" value="Tampilkan" class="btn btn-secondary btn-round btn-block mt-4">
                </div>
              </div>

              <div class="col-lg-2 pl-0">
                <div class="form-group pt-3">
                   
                  <a href="modules/laporan-stok/export.php?jenis_barang=<?php echo $jenis_barang; ?>" target="_blank" class="btn btn-success btn-round btn-block mt-4">
                    <span class="btn-label"><i class="fa fa-file-excel mr-2"></i></span> Export
                  </a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
           
          <div class="card-title">
            <i class="fas fa-file-alt mr-2"></i> Laporan Data Persediaan Barang <strong><?php echo $jenis_barang; ?></strong>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
             
            <table id="basic-datatables" class="display table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th class="text-center">Tanggal Masuk</th>
                  <th class="text-center">Tanggal Keluar</th>
                  <th class="text-center">Nama Barang</th>
                  <th class="text-center">Jenis Barang</th>
                  <th class="text-center">Satuan</th>
                  <th class="text-center">Masuk</th>
                  <th class="text-center">Keluar</th>
                  <th class="text-center">Sisa</th>
                </tr>
              </thead>
              <tbody>
                <?php
                 
                $no = 1;

                 
                $query = mysqli_query($mysqli, "SELECT a.id_barang, a.nama_barang, a.jenis, a.stok_minimum, a.stok, a.satuan, b.nama_jenis, c.nama_satuan, d.tanggalm, d.jumlahm, e.tanggalk, e.jumlahk
                                      FROM tbl_barang as a INNER JOIN tbl_jenis as b INNER JOIN tbl_satuan as c INNER JOIN tbl_barang_masuk as d INNER JOIN tbl_barang_keluar as e
                                      ON a.jenis=b.id_jenis AND a.satuan=c.id_satuan AND d.barang=a.id_barang AND e.barang=a.id_barang
                                      WHERE LOWER(b.nama_jenis) LIKE LOWER('%$jenis_barang%') ORDER BY a.jenis ASC")
                                      or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                 
                
                $stok = 0;
                $jumlahmm=0;
                $jumlahkk=0;
                while ($data = mysqli_fetch_assoc($query)) { 
                  $stok = $data['jumlahm'] - $data['jumlahk'];
                  $jumlahmm =$data['jumlahm'];
                  $jumlahkk =$data['jumlahk'];
                  
                  ?>
                   
                  <tr>
                    <td width="50" class="text-center"><?php echo $no++; ?></td>
                    <td width="90" class="text-center"><?php echo date('d-m-Y', strtotime($data['tanggalm'])); ?></td>
                    <td width="90" class="text-center"><?php echo date('d-m-Y', strtotime($data['tanggalk'])); ?></td>
                    <td width="150"><?php echo $data['nama_barang']; ?></td>
                    <td width="110" class="text-left"><?php echo $data['nama_jenis']; ?></td>
                    <td width="90" class="text-center"><?php echo $data['nama_satuan']; ?></td>
                    <td width="100" class="text-right"><?=number_format($jumlahmm, 0,'','.')?></td>
                    <td width="80" class="text-center"><?=number_format($jumlahkk, 0,'','.')?></td>
                    <td width="70" class="text-center"><?=$stok?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  <?php
  }
}
?>