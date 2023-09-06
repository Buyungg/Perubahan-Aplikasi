<?php
session_start();      // mengaktifkan session

// pengecekan session login user 
// jika user belum login
if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
  // alihkan ke halaman login dan tampilkan pesan peringatan login
  header('location: ../../login.php?pesan=2');
}
// jika user sudah login, maka jalankan perintah untuk export
else {
  // panggil file "database.php" untuk koneksi ke database
  require_once "../../config/database.php";
  // panggil file "fungsi_tanggal_indo.php" untuk membuat format tanggal indonesia
  require_once "../../helper/fungsi_tanggal_indo.php";

  // ambil data GET dari tombol export
  $tanggal_awal  = $_GET['tanggal_awal'];
  $tanggal_akhir = $_GET['tanggal_akhir'];
  $stok = $_GET['stok'];
  $jenis_barang = $_GET['jenis_barang'];

  // variabel untuk nomor urut tabel 
  $no = 1;

  // mengecek filter data stok
  // jika filter data stok "Seluruh" dipilih, tampilkan laporan stok seluruh barang
  if ($stok == 'Seluruh') {
    // fungsi header untuk mengirimkan raw data excel
    header("Content-type: application/vnd-ms-excel");
    // mendefinisikan nama file hasil ekspor "Laporan Stok Seluruh Barang.xls"
    header("Content-Disposition: attachment; filename=Laporan Stok Seluruh Barang $jenis_barang.xls");
?>
    <!-- halaman HTML yang akan diexport ke excel -->
    <!-- judul tabel -->
    <center>
      <h4>LAPORAN STOK SELURUH BARANG KELUAR</h4>
    </center>

  <div style="text-align:left">OPD        : DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL</div>
  <div style="text-align:left">KABUPATEN  : NGAWI</div>
  <div style="text-align:left">PROVINSI   : JAWA TIMUR</div>
  <br>
  <br>
  <div style="text-align:left">GUDANG     : DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL</div>
  <div style="text-align:left">TAHUN ANGGARAN    : <?php echo date('Y', strtotime($tanggal_awal)); ?></div>
  <div style="text-align:left">JENIS      : <?php echo $jenis_barang; ?></div>

    <!-- tabel untuk menampilkan data dari database -->
    <table border="1">
      <thead>
        <tr>
        <th height="50" align="center" vertical="center" rowspan="3">No.</th>
          <th height="50" align="center" vertical="center" rowspan="3">Tanggal Masuk</th>
          <th height="50" align="center" vertical="center" rowspan="3">Nama Barang</th>
          <th height="50" align="center" vertical="center" rowspan="3">Merk/Ukuran</th>
          <th height="50" align="center" vertical="center" rowspan="3">Tahun Pembuatan</th>
          <th height="50" align="center" vertical="center" rowspan="3">Dari</th>
          <th width="130" colspan="6"> Penerimaan </th>
          <th height="50" colspan="5">Pengeluaran</th>
          <th height="50" align="center" vertical="center" rowspan="3">Sisa</th>
          <th height="50" align="center" vertical="center" rowspan="3">Total Sisa</th>
        </tr>
      <tr>
    
        <th width="130" colspan="2"> Jumlah Satuan/Barang </th>
        <th width="130" colspan="2"> Tgl/No. Kontrak/SP/SPK </th>
        <th width="130" colspan="2"> Berita Acara Pemeriksaan </th>
        <th height="50" align="center" vertical="center" rowspan="2">Tanggal Dikeluarkan / Penyerahan</th>
        <th height="50" align="center" vertical="center" rowspan="2">Diserahkan Kepada</th>
        <th height="50" align="center" vertical="center" rowspan="2">Jumlah Barang</th>
        <th width="130" colspan="2"> Tgl/No.Surat Penyerahan </th>
      </tr>
        <tr>
          
          
          
          
          
          <th height="50" align="center" vertical="center">Jumlah</th>
          <th height="50" align="center" vertical="center">Satuan</th>
          <th height="50" align="center" vertical="center">Harga Satuan</th>
          <th height="50" align="center" vertical="center">Jumlah Harga</th>
          <th height="50" align="center" vertical="center">Tanggal</th>
          <th height="50" align="center" vertical="center">Nomor</th>
          
          <th height="50" align="center" vertical="center">Harga Satuan</th>
          <th height="50" align="center" vertical="center">Jumlah Harga</th>
          
        </tr>
      </thead>
      <tbody>
        <?php
         $tanggal_awal  = date('Y-m-d', strtotime($tanggal_awal));
         $tanggal_akhir = date('Y-m-d', strtotime($tanggal_akhir));
        // sql statement untuk menampilkan data dari tabel "tbl_barang", tabel "tbl_jenis", dan tabel "tbl_satuan"
        $query = mysqli_query($mysqli, "SELECT a.id_transaksi, a.tanggalk, a.barang, a.jumlahk, a.hargak, a.serah, a.totalk, b.nama_barang, b.stok, b.stok_minimum, c.nama_satuan, d.nama_jenis , e.dari, e.tanggalm, e.jumlahm, e.totalm
                                        FROM tbl_barang_keluar as a INNER JOIN tbl_barang as b INNER JOIN tbl_satuan as c INNER JOIN tbl_jenis as d INNER JOIN tbl_barang_masuk as e
                                        ON a.barang=b.id_barang AND b.satuan=c.id_satuan AND b.jenis=d.id_jenis AND b.id_barang=e.barang
                                        WHERE a.tanggalk BETWEEN '$tanggal_awal' AND '$tanggal_akhir' AND LOWER(d.nama_jenis) LIKE LOWER('%$jenis_barang%') ORDER BY a.id_transaksi ASC")
                                        or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
        // ambil data hasil query
        $total_jumlahm = 0;
        $total_bayarm = 0;
        $total_jumlahk = 0;
        $total_bayark = 0;
        $selisih = 0;
        $jumlah = 0;
        while ($data = mysqli_fetch_assoc($query)) { 
         $total_jumlahm += $data['jumlahm'];
         $total_bayarm += $data['totalm'];
         $total_jumlahk += $data['jumlahk'];
         $total_bayark += $data['totalk'];
         $selisih = $data['totalm'] - $data['totalk'];
         $jumlah += $selisih;
         
          ?>
        
          <!-- tampilkan data -->
          <tr>

                    <td width="50"   align="center"><?php echo $no++; ?></td>
                    <td width="170"  align="center"><?php echo date('d-m-Y', strtotime($data['tanggalm'])); ?></td>
                    <td width="220"><?php echo $data['nama_barang']; ?></td>
                    <td width="130"> </td>
                    <td width="100"  align="center"><?php echo date('Y', strtotime($data['tanggalm'])); ?></td>
                    <td width="200"  align="center"><?php echo $data['dari']; ?></td>
                    <td width="100"  align="center"><?php echo number_format($data['jumlahm'], 0, '', '.'); ?></td>
                    <td width="60"   align="center"><?php echo $data['nama_satuan']; ?></td>
                    <td width="150"  align="center">Rp. <?php echo number_format($data['hargak'], 0, '', '.'); ?></td>
                    <td width="200"  align="center">Rp. <?php echo number_format($data['totalm'], 0, '', '.'); ?></td>
                    <td width="170"  align="center"><?php echo date('d-m-Y', strtotime($data['tanggalm'])); ?></td>
                    <td> </td>
                    <td width="170"  align="center"><?php echo date('d-m-Y', strtotime($data['tanggalk'])); ?></td>
                    <td width="190"  align="center"><?php echo $data['serah']; ?></td>
                    <td width="100"  align="center"><?php echo number_format($data['jumlahk'], 0, '', '.'); ?></td>
                    <td width="130"  align="center">Rp. <?php echo number_format($data['hargak'], 0, '', '.'); ?></td>
                    <td width="200"  align="center">Rp. <?php echo number_format($data['totalk'], 0, '', '.'); ?></td>
            <?php
            // mengecek data "stok"
            // jika data stok minim
            if ($data['stok'] <= $data['stok_minimum']) { ?>
              <!-- tampilkan data dengan warna background -->
              <td style="background-color:#ffad46;color:#fff" width="100" align="center"><?php echo $data['stok']; ?></td>
            <?php }
            // jika data stok tidak minim
            else { ?>
              <!-- tampilkan data tanpa warna background -->
              <td width="100" align="center"><?php echo $data['stok']; ?></td>
              <td>Rp.<?=number_format($selisih, 0,'','.')?></td>
            <?php } ?>
          </tr>
        <?php } ?>

        <tr>
        <th width="130" colspan="6"> JUMLAH </th>
        <th align="center"><?=$total_jumlahm?></th>
        <th></th>
        <th></th>
        <th >Rp.<?=number_format($total_bayarm, 0,'','.')?></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th align="center"><?=$total_jumlahk?></th>
        <th></th>
        <th >Rp.<?=number_format($total_bayark, 0,'','.')?></th>
        <th></th>
        <th >Rp.<?=number_format($jumlah, 0,'','.')?></th>
      </tr>
      </tbody>
    </table>
  <?php
  }
  // jika filter data stok "Minimum" dipilih, tampilkan laporan stok barang yang mencapai batas minimum
  else {
    // fungsi header untuk mengirimkan raw data excel
    header("Content-type: application/vnd-ms-excel");
    // mendefinisikan nama file hasil ekspor "Laporan Stok Barang Minimum.xls"
    header("Content-Disposition: attachment; filename=Laporan Stok Barang $jenis_barang pakai habis.xls");
  ?>
    <!-- halaman HTML yang akan diexport ke excel -->
    <!-- judul tabel -->
    <center>
      <h4>LAPORAN STOK BARANG YANG PAKAI HABIS</h4>
    </center>

    <div style="text-align:left">OPD        : DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL</div>
    <div style="text-align:left">KABUPATEN  : NGAWI</div>
    <div style="text-align:left">PROVINSI   : JAWA TIMUR</div>
    <br>
    <br>
    <div style="text-align:left">GUDANG     : DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL</div>
    <div style="text-align:left">TAHUN ANGGARAN    : <?php echo date('Y', strtotime($tanggal_awal)); ?></div>
    <div style="text-align:left">JENIS      : <?php echo $jenis_barang; ?></div>
    <!-- tabel untuk menampilkan data dari database -->
    <table border="1">
      <thead>
      <tr>
        <th height="50" align="center" vertical="center" rowspan="3">No.</th>
          <th height="50" align="center" vertical="center" rowspan="3">Tanggal Masuk</th>
          <th height="50" align="center" vertical="center" rowspan="3">Nama Barang</th>
          <th height="50" align="center" vertical="center" rowspan="3">Merk/Ukuran</th>
          <th height="50" align="center" vertical="center" rowspan="3">Tahun Pembuatan</th>
          <th height="50" align="center" vertical="center" rowspan="3">Dari</th>
          <th width="130" colspan="6"> Penerimaan </th>
          <th height="50" colspan="5">Pengeluaran</th>
          <th height="50" align="center" vertical="center" rowspan="3">Ket</th>
        </tr>
      <tr>
    
        <th width="130" colspan="2"> Jumlah Satuan/Barang </th>
        <th width="130" colspan="2"> Tgl/No. Kontrak/SP/SPK </th>
        <th width="130" colspan="2"> Berita Acara Pemeriksaan </th>
        <th height="50" align="center" vertical="center" rowspan="2">Tanggal Dikeluarkan / Penyerahan</th>
        <th height="50" align="center" vertical="center" rowspan="2">Diserahkan Kepada</th>
        <th height="50" align="center" vertical="center" rowspan="2">Jumlah Barang</th>
        <th width="130" colspan="2"> Tgl/No.Surat Penyerahan </th>
      </tr>
        <tr>
          
          
          
          
          
          <th height="50" align="center" vertical="center">Jumlah</th>
          <th height="50" align="center" vertical="center">Satuan</th>
          <th height="50" align="center" vertical="center">Harga Satuan</th>
          <th height="50" align="center" vertical="center">Jumlah Harga</th>
          <th height="50" align="center" vertical="center">Tanggal</th>
          <th height="50" align="center" vertical="center">Nomor</th>
          
          <th height="50" align="center" vertical="center">Harga Satuan</th>
          <th height="50" align="center" vertical="center">Jumlah Harga</th>
          
        </tr>
      </thead>
      <tbody>
        <?php
         $tanggal_awal  = date('Y-m-d', strtotime($tanggal_awal));
         $tanggal_akhir = date('Y-m-d', strtotime($tanggal_akhir));
        // sql statement untuk menampilkan data dari tabel "tbl_barang", tabel "tbl_jenis", dan tabel "tbl_satuan" berdasarkan "stok"
        $query = mysqli_query($mysqli, "SELECT a.id_transaksi, a.tanggalk, a.barang, a.jumlahk, a.hargak, a.serah, a.totalk, b.nama_barang, b.stok, b.stok_minimum, c.nama_satuan, d.nama_jenis , e.dari, e.tanggalm, e.jumlahm, e.totalm
                                        FROM tbl_barang_keluar as a INNER JOIN tbl_barang as b INNER JOIN tbl_satuan as c INNER JOIN tbl_jenis as d INNER JOIN tbl_barang_masuk as e
                                        ON a.barang=b.id_barang AND b.satuan=c.id_satuan AND b.jenis=d.id_jenis AND b.id_barang=e.barang
                                        WHERE a.tanggalk BETWEEN '$tanggal_awal' AND '$tanggal_akhir'AND b.stok<=b.stok_minimum AND LOWER(d.nama_jenis) LIKE LOWER('%$jenis_barang%') ORDER BY a.id_transaksi ASC")
                                        or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
        // ambil data hasil query

          $total_jumlahm = 0;
          $total_bayarm = 0;
          $total_jumlahk = 0;
          $total_bayark = 0;
          while ($data = mysqli_fetch_assoc($query)) { 
          $total_jumlahm += $data['jumlahm'];
          $total_bayarm += $data['totalm'];
          $total_jumlahk += $data['jumlahk'];
          $total_bayark += $data['totalk'];
          
         ?>
          <!-- tampilkan data -->
          <tr>
          <td width="50"   align="center"><?php echo $no++; ?></td>
                    <td width="170"  align="center"><?php echo date('d-m-Y', strtotime($data['tanggalm'])); ?></td>
                    <td width="220"><?php echo $data['nama_barang']; ?></td>
                    <td width="130"> </td>
                    <td width="100"  align="center"><?php echo date('Y', strtotime($data['tanggalm'])); ?></td>
                    <td width="200"  align="center"><?php echo $data['dari']; ?></td>
                    <td width="100"  align="center"><?php echo number_format($data['jumlahm'], 0, '', '.'); ?></td>
                    <td width="60"   align="center"><?php echo $data['nama_satuan']; ?></td>
                    <td width="150"  align="center">Rp. <?php echo number_format($data['hargak'], 0, '', '.'); ?></td>
                    <td width="200"  align="center">Rp. <?php echo number_format($data['totalm'], 0, '', '.'); ?></td>
                    <td width="170"  align="center"><?php echo date('d-m-Y', strtotime($data['tanggalm'])); ?></td>
                    <td> </td>
                    <td width="170"  align="center"><?php echo date('d-m-Y', strtotime($data['tanggalk'])); ?></td>
                    <td width="190"  align="center"><?php echo $data['serah']; ?></td>
                    <td width="100"  align="center"><?php echo number_format($data['jumlahk'], 0, '', '.'); ?></td>
                    <td width="130"  align="center">Rp. <?php echo number_format($data['hargak'], 0, '', '.'); ?></td>
                    <td width="200"  align="center">Rp. <?php echo number_format($data['totalk'], 0, '', '.'); ?></td>
                    <td width="200"  align="center"> </td>

          </tr>
        <?php } ?>
        <tr>
        <th width="130" colspan="6"> JUMLAH </th>
        <th align="center"><?=$total_jumlahm?></th>
        <th></th>
        <th></th>
        <th >Rp.<?=number_format($total_bayarm, 0,'','.')?></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th align="center"><?=$total_jumlahk?></th>
        <th></th>
        <th >Rp.<?=number_format($total_bayark, 0,'','.')?></th>
      </tr>
      </tbody>
    </table>
  <?php } ?>
  <br>
  <div style="text-align:right">............, <?php echo tanggal_indo(date('Y-m-d')); ?></div>
<?php } ?>