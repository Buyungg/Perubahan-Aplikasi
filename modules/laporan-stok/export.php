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
  header("Content-Disposition: attachment; filename=Kartu Persediaan Barang $jenis_barang .xls");
?>
  <!-- halaman HTML yang akan diexport ke excel -->
  <!-- judul tabel -->
  <center>
    <h4>
      KARTU PERSEDIAAN BARANG 
      <br>
    </h4>
  </center>

  <!-- tabel Penjelasan -->
  <table>
    <thead>
      <tr>
        <td> </td>
        <td> ODP</td>
        <td align="right">:</td>
        <td colspan="3"> DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL </td>
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
        <td colspan="3"> DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL </td>
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
        <th height="30" align="center" vertical="center" rowspan="2" >No.</th>
        <th colspan="2"> No/Tanggal Surat Dasar </th>
        <th height="30" align="center" vertical="center" rowspan="2" >Nama Barang</th>
        <th colspan="4"> Barang-Barang </th>
        <th height="30" align="center" vertical="center" rowspan="2" >Harga Satuan</th>
        <th colspan="3"> Jumlah Harga Barang yang Diterima/Dikeluarkan/Sisa </th>
        <th height="30" align="center" vertical="center" rowspan="2" >Keterangan</th>
      </tr>
      <tr>
          
          <th height="30" align="center" vertical="center">Penerimaan</th>
          <th height="30" align="center" vertical="center">Pengeluaran</th>

          <th height="30" align="center" vertical="center">Satuan</th>
          <th height="30" align="center" vertical="center">Masuk/Terima</th>
          <th height="30" align="center" vertical="center">Keluar/Penyerahan</th>
          <th height="30" align="center" vertical="center">Sisa</th>

          <th height="30" align="center" vertical="center">Berjumlah</th>
          <th height="30" align="center" vertical="center">Berkurang</th>
          <th height="30" align="center" vertical="center">Sisa</th>
          
      </tr>
    </thead>
    <tbody>
      <?php
      // variabel untuk nomor urut tabel 
      
      $no = 1;

      // sql statement untuk menampilkan data dari tabel "tbl_barang_masuk", tabel "tbl_barang", dan tabel "tbl_satuan" berdasarkan "tanggal"
      $query = mysqli_query($mysqli, "SELECT a.id_barang, a.nama_barang, a.jenis, a.stok_minimum, a.stok, a.satuan, b.nama_jenis, c.nama_satuan, d.tanggalm, d.jumlahm, d.hargam, d.totalm, e.tanggalk, e.jumlahk, e.totalk
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
        <td width="180"><?php echo $data['nama_barang']; ?></td>
        <td width="90"  align="center"><?php echo $data['nama_satuan']; ?></td>
        <td width="130" align="center"><?php echo number_format($data['jumlahm'], 0, '', '.'); ?></td>
        <td width="155" align="center"><?php echo number_format($data['jumlahk'], 0, '', '.'); ?></td>   
        <td width="150" align="center"><?php echo $data['stok']; ?></td>
        <td width="130" align="left">Rp. <?php echo number_format($data['hargam'], 0, '', '.'); ?></td>
        <td width="150" align="left">Rp. <?php echo number_format($data['totalm'], 0, '', '.'); ?></td>
        <td width="150" align="left">Rp. <?php echo number_format($data['totalk'], 0, '', '.'); ?></td>
        <td width="150" align="left">Rp.<?=number_format($selisih, 0,'','.')?></td>
        <td width="130" > </td>

        </tr>
      <?php } ?>
      <tr>
        <th width="130" colspan="4"> Total</th>
        <th> </th>
        <th align="center"><?=$total_jumlah?></th>
        <th align="center"><?=$total_jumlahk?></th>
        <th align="center"><?=$total_stok?></th>
        <th></th>
        <th align="left">Rp.<?=number_format($total_bayar, 0,'','.')?></th>
        <th align="left">Rp.<?=number_format($total_bayark, 0,'','.')?></th>
        <th align="left">Rp.<?=number_format($total_selisih, 0,'','.')?></th>
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
        <td></td>
        <td colspan="5" align="center"> PEJABAT PENATAUSAHAAN PENGGUNA BARANG </td>
        <td colspan="3" align="center"> Ngawi, <?php echo tanggal_indo(date('Y-m-d')); ?> </td>
      </tr>
      <tr> 
        <td> </td>
        <td colspan="3" align="center"> KABUPATEN NGAWI </td>
        <td> </td>
        <td colspan="5"> </td>
        <td colspan="3" align="center"> PENGURUS BARANG </td>
      </tr>
      <tr></tr>
      <tr></tr>
      <tr></tr>
      <tr>
        <td> </td>
        <th colspan="3" align="center"> <u> NOOR HASAN MUNTAHA, S.T, M.M </u> </th>
        <th> </th>
        <th colspan="5" align="center"> <u> TUTIK RAHAYU SRI UTAMI, SH </u> </th>
        <th colspan="3" align="center"> <u> HARI PURNAWAN </u> </th>
      </tr>
      <tr>
        <td> </td>
        <td colspan="3" align="center"> Pembina Tk. I</td>
        <td> </td>
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