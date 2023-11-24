<?php
 
 
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
   
  header('location: 404.html');
}
 
else { ?>
  <div class="panel-header bg-secondary-gradient">
    <div class="page-inner py-4">
      <div class="page-header text-white">
         
        <h4 class="page-title text-white"><i class="fas fa-file-import mr-2"></i> Laporan Barang Masuk</h4>
         
        <ul class="breadcrumbs">
          <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a>Laporan</a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a>Barang Masuk</a></li>
        </ul>
      </div>
    </div>
  </div>

  <?php
   
   
  if (!isset($_POST['tampil'])) { ?>
    <div class="page-inner mt--5">
      <div class="card">
        <div class="card-header">
           
          <div class="card-title">Filter Data Barang Masuk</div>
        </div>
         
        <div class="card-body">
          <form action="?module=laporan_barang_masuk" method="post" class="needs-validation" novalidate>
            <div class="row">
              <div class="col-lg-3">
                <div class="form-group">
                  <label>Tanggal Awal <span class="text-danger">*</span></label>
                  <input type="text" name="tanggal_awal" class="form-control date-picker" autocomplete="off" required>
                  <div class="invalid-feedback">Tanggal awal tidak boleh kosong.</div>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="form-group">
                  <label>Tanggal Akhir <span class="text-danger">*</span></label>
                  <input type="text" name="tanggal_akhir" class="form-control date-picker" autocomplete="off" required>
                  <div class="invalid-feedback">Tanggal akhir tidak boleh kosong.</div>
                </div>
              </div>

              <div class="col-lg-2 pr-0">
                <div class="form-group pt-3">
                   
                  <input type="submit" name="tampil" value="Tampilkan" class="btn btn-secondary btn-round btn-block mt-4">
                </div>
              </div>
            </div>

             
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Jenis Barang</label>
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
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php
  }
   
  else {
     
    $tanggal_awal  = $_POST['tanggal_awal'];
    $tanggal_akhir = $_POST['tanggal_akhir'];
    $jenis_barang = $_POST['jenis_barang'];
  ?>
    <div class="page-inner mt--5">
      <div class="card">
        <div class="card-header">
           
          <div class="card-title">Filter Data Barang Masuk</div>
        </div>
         
        <div class="card-body">
          <form action="?module=laporan_barang_masuk" method="post" class="needs-validation" novalidate>
            <div class="row">
              <div class="col-lg-3">
                <div class="form-group">
                  <label>Tanggal Awal <span class="text-danger">*</span></label>
                  <input type="text" name="tanggal_awal" class="form-control date-picker" autocomplete="off" value="<?php echo $tanggal_awal; ?>" required>
                  <div class="invalid-feedback">Tanggal awal tidak boleh kosong.</div>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="form-group">
                  <label>Tanggal Akhir <span class="text-danger">*</span></label>
                  <input type="text" name="tanggal_akhir" class="form-control date-picker" autocomplete="off" value="<?php echo $tanggal_akhir; ?>" required>
                  <div class="invalid-feedback">Tanggal akhir tidak boleh kosong.</div>
                </div>
              </div>

              <div class="col-lg-2 pr-0">
                <div class="form-group pt-3">
                   
                  <input type="submit" name="tampil" value="Tampilkan" class="btn btn-secondary btn-round btn-block mt-4">
                </div>
              </div>

              <div class="col-lg-2 pl-0">
                <div class="form-group pt-3">
                   
                  <a href="modules/laporan-barang-masuk/export.php?tanggal_awal=<?php echo $tanggal_awal; ?>&tanggal_akhir=<?php echo $tanggal_akhir; ?>&jenis_barang=<?php echo $jenis_barang; ?> " target="_blank" class="btn btn-success btn-round btn-block mt-4">
                    <span class="btn-label"><i class="fa fa-file-excel mr-2"></i></span> Export
                  </a>
                </div>
              </div>
            </div>


             
            <div class="row">
              <div class="col-lg-6">
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
            </div>
          </form>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
           
          <div class="card-title">
            <i class="fas fa-file-alt mr-2"></i> Laporan Data Barang Masuk Tanggal <strong><?php echo $tanggal_awal; ?></strong> s.d. <strong><?php echo $tanggal_akhir; ?></strong>
          </div>
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
                  <th class="text-center">Dari</th>
                  <th class="text-center">Harga</th>
                  <th class="text-center">Jumlah Masuk</th>
                  <th class="text-center">Satuan</th>
                  <th class="text-center">Total</th>
                </tr>
              </thead>
              <tbody>
                <?php
                 
                $tanggal_awal  = date('Y-m-d', strtotime($tanggal_awal));
                $tanggal_akhir = date('Y-m-d', strtotime($tanggal_akhir));

                 
                $no = 1;

                 
                $query = mysqli_query($mysqli, "SELECT a.id_transaksi, a.tanggalm, a.barang, a.jumlahm, a.hargam, a.dari, a.totalm, b.nama_barang, c.nama_satuan, d.nama_jenis
                                                FROM tbl_barang_masuk as a INNER JOIN tbl_barang as b INNER JOIN tbl_satuan as c INNER JOIN tbl_jenis as d
                                                ON a.barang=b.id_barang AND b.satuan=c.id_satuan AND b.jenis=d.id_jenis
                                                WHERE a.tanggalm BETWEEN '$tanggal_awal' AND '$tanggal_akhir' AND LOWER(d.nama_jenis) LIKE LOWER('%$jenis_barang%') ORDER BY a.id_transaksi ASC")
                                                or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                 
                while ($data = mysqli_fetch_assoc($query)) { ?>
                   
                  <tr>
                    <td width="50" class="text-center"><?php echo $no++; ?></td>
                    <td width="90" class="text-center"><?php echo date('d-m-Y', strtotime($data['tanggalm'])); ?></td>
                    <td width="150"><?php echo $data['nama_barang']; ?></td>
                    <td width="110" class="text-left"><?php echo $data['nama_jenis']; ?></td>
                    <td width="90" class="text-center"><?php echo $data['dari']; ?></td>
                    <td width="60" class="text-center">Rp. <?php echo number_format($data['hargam'], 0, '', '.'); ?></td>
                    <td width="100" class="text-right"><?php echo number_format($data['jumlahm'], 0, '', '.'); ?></td>
                    <td width="60"><?php echo $data['nama_satuan']; ?></td>
                  <td width="80" class="text-center">Rp. <?php echo number_format($data['totalm'], 0, '', '.'); ?></td>
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