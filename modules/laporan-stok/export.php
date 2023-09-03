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
  $jenis_barang = $_GET['jenis_barang'];

  // fungsi header untuk mengirimkan raw data excel
  header("Content-type: application/vnd-ms-excel");
  // mendefinisikan nama file hasil ekspor "Laporan Data Barang Masuk.xls"
  header("Content-Disposition: attachment; filename=Laporan Data Persediaan Barang $jenis_barang .xls");
?>
  <!-- halaman HTML yang akan diexport ke excel -->
  <!-- judul tabel -->
  <center>
    <h4>
      LAPORAN DATA BARANG MASUK<br>
    </h4>
  </center>

  <div style="text-align:left">OPD        : DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL</div>
  <div style="text-align:left">KABUPATEN  : NGAWI</div>
  <div style="text-align:left">PROVINSI   : JAWA TIMUR</div>
  <br>
  <br>
  <div style="text-align:left">GUDANG     : DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL</div>
  <div style="text-align:left">JENIS      : <?php echo $jenis_barang; ?></div>

  <!-- tabel untuk menampilkan data dari database -->
  <table border="1">
    <thead>
    <tr>
        <th colspan="3"> No/Tanggal Surat Dasar </th>
        <th> </th>
        <th colspan="4"> Barang-Barang </th>
        <th colspan="4"> Jumlah Harga Barang yang Diterima/Dikeluarkan/Sisa </th>
      </tr>
      <tr style="background-color:#6861ce;color:#fff">
          <th height="30" align="center" vertical="center">No.</th>
          <th height="30" align="center" vertical="center">Tanggal Masuk</th>
          <th height="30" align="center" vertical="center">Tanggal Keluar</th>
          <th height="30" align="center" vertical="center">Nama Barang</th>
          <th height="30" align="center" vertical="center">Satuan</th>
          <th height="30" align="center" vertical="center">Jumlah Masuk</th>
          <th height="30" align="center" vertical="center">Jumlah Keluar</th>
          <th height="30" align="center" vertical="center">Sisa</th>
          <th height="30" align="center" vertical="center">Harga Satuan</th>
          <th height="30" align="center" vertical="center">Berjumlah</th>
          <th height="30" align="center" vertical="center">Berkurang</th>
          <th height="30" align="center" vertical="center">Sisa</th>
          <th height="30" align="center" vertical="center">Keterangan</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // variabel untuk nomor urut tabel 
      
      $no = 1;

      // sql statement untuk menampilkan data dari tabel "tbl_barang_masuk", tabel "tbl_barang", dan tabel "tbl_satuan" berdasarkan "tanggal"
      $query = mysqli_query($mysqli, "SELECT a.id_barang, a.nama_barang, a.jenis, a.harga, a.stok_minimum, a.stok, a.satuan, b.nama_jenis, c.nama_satuan, d.tanggalm, d.jumlahm, d.totalm, e.tanggalk, e.jumlahk, e.totalk
                                      FROM tbl_barang as a INNER JOIN tbl_jenis as b INNER JOIN tbl_satuan as c INNER JOIN tbl_barang_masuk as d INNER JOIN tbl_barang_keluar as e
                                      ON a.jenis=b.id_jenis AND a.satuan=c.id_satuan AND d.barang=a.id_barang AND e.barang=a.id_barang
                                      WHERE LOWER(b.nama_jenis) LIKE LOWER('%$jenis_barang%') ORDER BY a.id_barang ASC")
                                      or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                                  
      // ambil data hasil query
      $total_jumlah = 0;
      $total_jumlahk = 0;
      $total_stok = 0;
      $total_bayar = 0;
      $total_bayark = 0;
      $selisih = 0;
      $total_selisih = 0;
      while ($data = mysqli_fetch_assoc($query)) { 
        $total_jumlah += $data['jumlahm'];
        $total_jumlahk += $data['jumlahk'];
        $total_stok += $data['stok'];
        $total_bayar += $data['totalm'];
        $total_bayark += $data['totalk'];
        $selisih = $data['totalm'] - $data['totalk'];
        $total_selisih += $selisih;

        
        ?>
        <!-- tampilkan data -->
        <tr>
        <td width="50"  align="center"><?php echo $no++; ?></td>
        <td width="130"  align="center"><?php echo date('d-m-Y', strtotime($data['tanggalm'])); ?></td>
        <td width="130"  align="center"><?php echo date('d-m-Y', strtotime($data['tanggalk'])); ?></td>
        <td width="180"><?php echo $data['id_barang']; ?> - <?php echo $data['nama_barang']; ?></td>
        <td width="90"  align="center"><?php echo $data['nama_satuan']; ?></td>
        <td width="130" align="center"><?php echo number_format($data['jumlahm'], 0, '', '.'); ?></td>
        <td width="130"  align="center"><?php echo number_format($data['jumlahk'], 0, '', '.'); ?></td>   
        <td width="100" align="center"><?php echo $data['stok']; ?></td>
        <td width="130" align="center">Rp. <?php echo number_format($data['harga'], 0, '', '.'); ?></td>
        <td width="130" align="center">Rp. <?php echo number_format($data['totalm'], 0, '', '.'); ?></td>
        <td width="130" align="center">Rp. <?php echo number_format($data['totalk'], 0, '', '.'); ?></td>
        <td width="130" align="center">Rp.<?=number_format($selisih, 0,'','.')?></td>
        <td width="130" > </td>

        </tr>
      <?php } ?>
      <tr>
        <th width="130" colspan="5"> Total</th>
        <th align="center"><?=$total_jumlah?></th>
        <th align="center"><?=$total_jumlahk?></th>
        <th align="center"><?=$total_stok?></th>
        <th></th>
        <th >Rp.<?=number_format($total_bayar, 0,'','.')?></th>
        <th >Rp.<?=number_format($total_bayark, 0,'','.')?></th>
        <th >Rp.<?=number_format($total_selisih, 0,'','.')?></th>
      </tr>
    </tbody>
  </table>
  <br>
  <div style="text-align:right">............, <?php echo tanggal_indo(date('Y-m-d')); ?></div>
<?php } ?>