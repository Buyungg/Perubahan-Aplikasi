<?php
// mencegah direct access file PHP agar file PHP tidak bisa diakses secara langsung dari browser dan hanya dapat dijalankan ketika di include oleh file lain
// jika file diakses secara langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
  // alihkan ke halaman error 404
  header('location: 404.html');
}
// jika file di include oleh file lain, tampilkan isi file
else { ?>
  <div class="panel-header bg-secondary-gradient">
    <div class="page-inner py-4">
      <div class="page-header text-white">
        <!-- judul halaman -->
        <h4 class="page-title text-white"><i class="fas fa-file-export mr-2"></i> Laporan Barang Keluar</h4>
        <!-- breadcrumbs -->
        <ul class="breadcrumbs">
          <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a>Laporan</a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a>Barang Keluar</a></li>
        </ul>
      </div>
    </div>
  </div>

  <?php
  // mengecek data hasil submit dari form filter
  // jika tidak ada data yang dikirim (tombol tampilkan belum diklik) 
  if (!isset($_POST['tampil'])) { ?>
    <div class="page-inner mt--5">
      <div class="card">
        <div class="card-header">
          <!-- judul form -->
          <div class="card-title">Filter Data Barang Keluar</div>
        </div>
        <!-- form filter data -->
        <div class="card-body">
          <form action="?module=laporan_barang_keluar" method="post" class="needs-validation" novalidate>
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
                  <!-- tombol tampil data -->
                  <input type="submit" name="tampil" value="Tampilkan" class="btn btn-secondary btn-round btn-block mt-4">
                </div>
              </div>

              <!-- Pilihan stok dan jenis barang -->
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Stok<span class="text-danger">*</span></label>
                  <select name="stok" class="form-control chosen-select" autocomplete="off" required>
                    <option selected disabled value="">-- Pilih --</option>
                    <option value="Seluruh">Seluruh</option>
                    <option value="Pakai Habis">Pakai Habis</option>
                  </select>
                  <div class="invalid-feedback">Stok tidak boleh kosong.</div>
                </div>
              </div>

                <div class="col-lg-6">
                <div class="form-group">
                  <label>Jenis Barang<span class="text-danger">*</span></label>
                  <select name="jenis_barang" class="form-control chosen-select" autocomplete="off" required>
                  <option selected disabled value="">--Pilih--</option>
                  <?php
                  // sql statement untuk menampilkan data dari tabel "tbl_jenis"
                  $query_jenis = mysqli_query($mysqli, "SELECT id_jenis, nama_jenis FROM tbl_jenis ORDER BY id_jenis ASC")
                                                         or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                  // ambil data hasil query
                  while ($data_jenis = mysqli_fetch_assoc($query_jenis)) {
                    // tampilkan data
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
    </div>
  <?php
  }
  // jika ada data yang dikirim (tombol tampilkan diklik)
  else {
    // ambil data hasil submit dari form filter
    $tanggal_awal  = $_POST['tanggal_awal'];
    $tanggal_akhir = $_POST['tanggal_akhir'];
    $stok = $_POST['stok'];
    $jenis_barang = $_POST['jenis_barang'];
  ?>
    <div class="page-inner mt--5">
      <div class="card">
        <div class="card-header">
          <!-- judul form -->
          <div class="card-title">Filter Data Barang Keluar</div>
        </div>
        <!-- form filter data -->
        <div class="card-body">
          <form action="?module=laporan_barang_keluar" method="post" class="needs-validation" novalidate>
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
                  <!-- tombol tampil data -->
                  <input type="submit" name="tampil" value="Tampilkan" class="btn btn-secondary btn-round btn-block mt-4">
                </div>
              </div>

              <div class="col-lg-2 pl-0">
                <div class="form-group pt-3">
                  <!-- tombol export laporan -->
                  <a href="modules/laporan-barang-keluar/export.php?tanggal_awal=<?php echo $tanggal_awal; ?>&tanggal_akhir=<?php echo $tanggal_akhir; ?>&stok=<?php echo $stok; ?>&jenis_barang=<?php echo $jenis_barang; ?>" target="_blank" class="btn btn-success btn-round btn-block mt-4">
                    <span class="btn-label"><i class="fa fa-file-excel mr-2"></i></span> Export
                  </a>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>Stok <span class="text-danger">*</span></label>
                  <select name="stok" class="form-control chosen-select" autocomplete="off" required>
                    <option value="<?php echo $stok; ?>"><?php echo $stok; ?></option>
                    <option disabled value="">-- Pilih --</option>
                    <option value="Seluruh">Seluruh</option>
                    <option value="Pakai Habis">Pakai Habis</option>
                  </select>
                  <div class="invalid-feedback">Stok tidak boleh kosong.</div>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>Jenis Barang</label>
                  <select name="jenis_barang" class="form-control chosen-select" autocomplete="off" required>
                  <option selected disabled value="<?php echo $jenis_barang; ?>"><?php echo $jenis_barang; ?></option>
                  <?php
                  // sql statement untuk menampilkan data dari tabel "tbl_jenis"
                  $query_jenis = mysqli_query($mysqli, "SELECT id_jenis, nama_jenis FROM tbl_jenis ORDER BY id_jenis ASC")
                                                         or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                  // ambil data hasil query
                  while ($data_jenis = mysqli_fetch_assoc($query_jenis)) {
                    // tampilkan data
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
        <?php
        // mengecek filter data stok
        // jika filter data stok "Seluruh" dipilih
        if ($stok == 'Seluruh') { ?>
          <!-- tampilkan laporan stok seluruh barang -->
          <div class="card-header">
            <!-- judul tabel -->
            <div class="card-title"><i class="fas fa-file-alt mr-2"></i> Laporan Stok Seluruh Barang</div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <!-- tabel untuk menampilkan data dari database -->
              <table id="basic-datatables" class="display table table-bordered table-striped table-hover">
                <thead>
                  <tr>
                  <th class="text-center">No.</th>
                  <th class="text-center">Tanggal Masuk</th>
                  <th class="text-center">Barang</th>
                  <th class="text-center">Jenis</th>
                  <th class="text-center">Jumlah Masuk</th>
                  <th class="text-center">Keluar</th>
                  <th class="text-center">Jumlah Keluar</th>
                  <th class="text-center">Satuan</th>
                  <th class="text-center">Harga</th>
                  <th class="text-center">Total</th>
                  <th class="text-center">Sisa</th>
                  <th class="text-center">Diserahkan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                   // ubah format tanggal menjadi Tahun-Bulan-Hari (Y-m-d)
                  $tanggal_awal  = date('Y-m-d', strtotime($tanggal_awal));
                  $tanggal_akhir = date('Y-m-d', strtotime($tanggal_akhir));

                  // variabel untuk nomor urut tabel
                  $no = 1;
                  // sql statement untuk menampilkan data dari tabel "tbl_barang", tabel "tbl_jenis", dan tabel "tbl_satuan"
                  $query = mysqli_query($mysqli, "SELECT a.id_transaksi, a.tanggalk, a.barang, a.hargak, a.jumlahk, a.totalk, a.serah, b.nama_barang, b.stok, b.stok_minimum, c.nama_satuan, d.tanggalm, d.jumlahm, e.nama_jenis
                                        FROM tbl_barang_keluar as a INNER JOIN tbl_barang as b INNER JOIN tbl_satuan as c INNER JOIN tbl_barang_masuk as d INNER JOIN tbl_jenis as e
                                        ON a.barang=b.id_barang AND b.satuan=c.id_satuan AND b.id_barang=d.barang AND b.jenis=e.id_jenis
                                        WHERE a.tanggalk BETWEEN '$tanggal_awal' AND '$tanggal_akhir' AND LOWER(e.nama_jenis) LIKE LOWER('%$jenis_barang%') ORDER BY a.id_transaksi ASC")
                                        or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                  // ambil data hasil query
                  while ($data = mysqli_fetch_assoc($query)) { ?>
                    <!-- tampilkan data -->
                    <tr>
                    <td width="50" class="text-center"><?php echo $no++; ?></td>
                    <td width="70" class="text-center"><?php echo date('d-m-Y', strtotime($data['tanggalm'])); ?></td>
                    <td width="220"><?php echo $data['nama_barang']; ?></td>
                    <td width="60" class="text-center"><?php echo $data['nama_jenis']; ?></td>
                    <td width="100" class="text-right"><?php echo number_format($data['jumlahm'], 0, '', '.'); ?></td>
                    <td width="70" class="text-center"><?php echo date('d-m-Y', strtotime($data['tanggalk'])); ?></td>
                    <td width="100" class="text-right"><?php echo number_format($data['jumlahk'], 0, '', '.'); ?></td>
                    <td width="60"><?php echo $data['nama_satuan']; ?></td>
                    <td width="60" class="text-center">Rp. <?php echo number_format($data['hargak'], 0, '', '.'); ?></td>
                    <td width="60" class="text-center">Rp. <?php echo number_format($data['totalk'], 0, '', '.'); ?></td>
                      <?php
                      // mengecek data "stok"
                      // jika data stok minim
                      if ($data['stok'] <= $data['stok_minimum']) { ?>
                        <!-- tampilkan data dengan warna background -->
                        <td width="70" class="text-right"><span class="badge badge-warning"><?php echo $data['stok']; ?></span></td>
                      <?php }
                      // jika data stok tidak minim
                      else { ?>
                        <!-- tampilkan data tanpa warna background -->
                        <td width="70" class="text-right"><?php echo $data['stok']; ?></td>
                      <?php } ?>
                      <td width="60"><?php echo $data['serah']; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        <?php
        }
        // jika filter data stok "Minimum" dipilih
        else { ?>
          <!-- tampilkan laporan stok barang yang mencapai batas minimum -->
          <div class="card-header">
            <!-- judul tabel -->
            <div class="card-title"><i class="fas fa-file-alt mr-2"></i> Laporan Stok Barang yang Mencapai Batas Minimum</div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <!-- tabel untuk menampilkan data dari database -->
              <table id="basic-datatables" class="display table table-bordered table-striped table-hover">
                <thead>
                  <tr>
                 <th class="text-center">No.</th>
                  <th class="text-center">ID Transaksi</th>
                  <th class="text-center">Tanggal Masuk</th>
                  <th class="text-center">Barang</th>
                  <th class="text-center">Jenis</th>
                  <th class="text-center">Jumlah Masuk</th>
                  <th class="text-center">Keluar</th>
                  <th class="text-center">Jumlah Keluar</th>
                  <th class="text-center">Satuan</th>
                  <th class="text-center">Harga</th>
                  <th class="text-center">Total</th>
                  <th class="text-center">Sisa</th>
                  <th class="text-center">Diserahkan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $tanggal_awal  = date('Y-m-d', strtotime($tanggal_awal));
                  $tanggal_akhir = date('Y-m-d', strtotime($tanggal_akhir));

                  // variabel untuk nomor urut tabel
                  $no = 1;
                  // sql statement untuk menampilkan data dari tabel "tbl_barang", tabel "tbl_jenis", dan tabel "tbl_satuan" berdasarkan "stok"
                  $query = mysqli_query($mysqli, "SELECT a.id_transaksi, a.tanggalk, a.barang, a.hargak, a.jumlahk, a.totalk, a.serah, b.nama_barang, b.stok, b.stok_minimum, c.nama_satuan, d.tanggalm, d.jumlahm, e.nama_jenis
                                                            FROM tbl_barang_keluar as a INNER JOIN tbl_barang as b INNER JOIN tbl_satuan as c INNER JOIN tbl_barang_masuk as d INNER JOIN tbl_jenis as e
                                                            ON a.barang=b.id_barang AND b.satuan=c.id_satuan AND b.id_barang=d.barang AND b.jenis=e.id_jenis
                                                            WHERE a.tanggalk BETWEEN '$tanggal_awal' AND '$tanggal_akhir'AND b.stok<=b.stok_minimum AND LOWER(e.nama_jenis) LIKE LOWER('%$jenis_barang%') ORDER BY a.id_transaksi ASC")
                                                            or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                  // ambil data hasil query
                  while ($data = mysqli_fetch_assoc($query)) { ?>
                    <!-- tampilkan data -->
                    <tr>
                    <td width="50" class="text-center"><?php echo $no++; ?></td>
                    <td width="90" class="text-center"><?php echo $data['id_transaksi']; ?></td>
                    <td width="70" class="text-center"><?php echo date('d-m-Y', strtotime($data['tanggalm'])); ?></td>
                    <td width="220"><?php echo $data['barang']; ?> - <?php echo $data['nama_barang']; ?></td>
                    <td width="60"><?php echo $data['nama_jenis']; ?></td>
                    <td width="100" class="text-right"><?php echo number_format($data['jumlahm'], 0, '', '.'); ?></td>
                    <td width="70" class="text-center"><?php echo date('d-m-Y', strtotime($data['tanggalk'])); ?></td>
                    <td width="100" class="text-right"><?php echo number_format($data['jumlahk'], 0, '', '.'); ?></td>
                    <td width="60"><?php echo $data['nama_satuan']; ?></td>
                    <td width="60" class="text-center">Rp. <?php echo number_format($data['hargak'], 0, '', '.'); ?></td>
                    <td width="60" class="text-center">Rp. <?php echo number_format($data['totalk'], 0, '', '.'); ?></td>
                    <td width="70" class="text-right"><?php echo $data['stok']; ?></td>
                    <td width="60"><?php echo $data['serah']; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
<?php
  }
}
?>