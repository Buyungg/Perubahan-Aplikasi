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
  $jenis_barang = $_GET['jenis_barang'];

  // fungsi header untuk mengirimkan raw data excel
  header("Content-type: application/vnd-ms-excel");
  // mendefinisikan nama file hasil ekspor "Laporan Data Barang Masuk.xls"
  header("Content-Disposition: attachment; filename=Laporan Data Barang Masuk $jenis_barang.xls");
?>
  <!-- halaman HTML yang akan diexport ke excel -->
  <!-- judul tabel -->
  <center>
    <h4>
      DAFTAR PENGADAAN BARANG 
      <h4>
  </center>
  <br>

  <!-- tabel Penjelasan -->
  <table>
    <thead>
      <tr>
        <td> </td>
        <td> ODP</td>
        <td align="right">:</td>
        <td colspan="2"> DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL </td>
      </tr>
      <tr>
        <td> </td>
        <td> KABUPATEN </td>
        <td align="right">:</td>
        <td colspan="2"> NGAWI </td>
      </tr>
      <tr>
        <td> </td>
        <td> PROVINSI</td>
        <td align="right">:</td>
        <td colspan="2"> JAWA TIMUR </td>
      </tr>
      <tr></tr>
      <tr></tr>
      <tr>
        <td> </td>
        <td> GUDANG </td>
        <td align="right">:</td>
        <td colspan="2"> DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL </td>
      </tr>
      <tr>
        <td> </td>
        <td> TAHUN ANGGARAN </td>
        <td align="right">:</td>
        <td align="left"> <?php echo date('Y', strtotime($tanggal_awal)); ?> </td>
      </tr>
      <tr>
        <td> </td>
        <td> JENIS</td>
        <td align="right">:</td>
        <td colspan="2"> <?php echo $jenis_barang; ?> </td>
      </tr>
    </thead>
  </table>
  <br>

  <!-- tabel untuk menampilkan data dari database -->
  <table border="1">
    <thead>
      <tr>
      <th height="30" align="center" vertical="center" rowspan="2">No.</th>
      <th height="30" align="center" vertical="center" rowspan="2">Jenis Barang Yang Dibeli</th>
        <th colspan="2"> SPK/Perjanjian/Kontrak </th>
        <th colspan="2"> DPA/SPM/Kwitansi</th>
        <th colspan="5"> Jumlah </th>
        <th height="30" align="center" vertical="center" rowspan="2">Dipergunakan Unit</th>
      </tr>
      <tr">
        
        
        <th height="30" align="center" vertical="center">Tanggal</th>
        <th height="30" align="center" vertical="center">Nomor</th>
        <th height="30" align="center" vertical="center">Tanggal</th>
        <th height="30" align="center" vertical="center">Nomor</th>
        <th width="150" align="center" vertical="center">Merk</th>
        <th height="30" align="center" vertical="center">Satuan</th>
        <th height="30" align="center" vertical="center">Banyak Barang</th>
        <th height="30" align="center" vertical="center">Harga Satuan</th>
        <th height="30" align="center" vertical="center">Jumlah Harga</th>
        
      </tr>
    </thead>
    <tbody>
      <?php
      // ubah format tanggal menjadi Tahun-Bulan-Hari (Y-m-d)
      $tanggal_awal  = date('Y-m-d', strtotime($tanggal_awal));
      $tanggal_akhir = date('Y-m-d', strtotime($tanggal_akhir));

      // variabel untuk nomor urut tabel 
      
      $no = 1;

      // sql statement untuk menampilkan data dari tabel "tbl_barang_masuk", tabel "tbl_barang", dan tabel "tbl_satuan" berdasarkan "tanggal"
      $query = mysqli_query($mysqli, "SELECT a.id_transaksi, a.tanggalm, a.nomor, a.barang, a.jumlahm, a.hargam, a.totalm, b.nama_barang, c.nama_satuan, d.nama_jenis
                                      FROM tbl_barang_masuk as a INNER JOIN tbl_barang as b INNER JOIN tbl_satuan as c INNER JOIN tbl_jenis as d
                                      ON a.barang=b.id_barang AND b.satuan=c.id_satuan AND b.jenis=d.id_jenis
                                      WHERE a.tanggalm BETWEEN '$tanggal_awal' AND '$tanggal_akhir' AND LOWER(d.nama_jenis) LIKE LOWER('%$jenis_barang%') ORDER BY a.id_transaksi ASC")
                                      or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                                  
      // ambil data hasil query
      $total_jumlah = 0;
      $total_bayar = 0; 
      while ($data = mysqli_fetch_assoc($query)) { 
        $total_jumlah += $data['jumlahm'];
        $total_bayar += $data['totalm'];
        
        ?>
        <!-- tampilkan data -->
        <tr>
          <td width="70" align="center"><?php echo $no++; ?></td>
          <td width="200" align="center"><?php echo $data['nama_barang']; ?></td>
          <td width="130"> </td>
          <td width="130"> </td>
          <td width="130" align="center"><?php echo date('d-m-Y', strtotime($data['tanggalm'])); ?></td>
          <td width="200" align="center"><?php echo $data['nomor']; ?></td>
          <td width="200"> </td>
          <td width="130"><?php echo $data['nama_satuan']; ?></td>
          <td width="130" align="right"><?php echo number_format($data['jumlahm'], 0, '', '.'); ?></td>
          <td width="130" align="center">Rp. <?php echo number_format($data['hargam'], 0, '', '.'); ?></td>
          <td width="130" align="center">Rp. <?php echo number_format($data['totalm'], 0, '', '.'); ?></td>
          <td width="150"> </td>
          
        </tr>
      <?php } ?>
      <tr>
        <th width="130" colspan="7"> JUMLAH </th>
        <th></th>
        <th align="right"><?=$total_jumlah?></th>
        <th></th>
        <th >Rp.<?=number_format($total_bayar, 0,'','.')?></th>
      </tr>
    </tbody>
  </table>
  <br>
  <!-- format ttd -->
  <table>
    <thead>
      <tr>
        <td> </td>
        <td colspan="3" align="center"> PENGGUNA BARANG </td>
      </tr>
      <tr>
        <td> </td>
        <td colspan="3" align="center"> DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL </td>
        <td colspan="5" align="center"> PEJABAT PENATAUSAHAAN PENGGUNA BARANG </td>
      </tr>
      <tr> 
        <td> </td>
        <td colspan="3" align="center"> KABUPATEN NGAWI </td>
        <td colspan="5"> </td>
        <td colspan="3" align="center"> PENGURUS BARANG </td>
      </tr>
      <tr></tr>
      <tr></tr>
      <tr></tr>
      <tr>
        <td> </td>
        <th colspan="3" align="center"> <u> NOOR HASAN MUNTAHA, S.T, M.M </u> </th>
        <th colspan="5" align="center"> <u> TUTIK RAHAYU SRI UTAMI, SH </u> </th>
        <th colspan="3" align="center"> <u> HARI PURNAWAN </u> </th>
      </tr>
      <tr>
        <td> </td>
        <td colspan="3" align="center"> Pembina Tk. I</td>
        <td colspan="5" align="center"> NIP.1968052 199103 2 011</td>
        <td colspan="3" align="center"> NIP.19790719 200901 1 005 </td>
      </tr>
      <tr>
        <td> </td>
        <td colspan="3" align="center"> NIP.19690927 199803 1 007</td>
      </tr>
    </thead>
  </table>
<?php } ?>