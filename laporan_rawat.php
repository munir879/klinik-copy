<?php
include_once "library/inc.seslogin.php";

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$row = 50;
$hal = isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql = "SELECT * FROM rawat";
$pageQry = mysqli_query($koneksidb, $pageSql) or die("error paging: " . mysqli_error($pageQry));
$jml   = mysqli_num_rows($pageQry);
$max   = ceil($jml / $row);
?>


<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header">Laporan Rawat Pasien</h1>
  </div>
  <!-- /.col-lg-12 -->
</div>

<div class="dataTable-wrapper">
  <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example" width="100%" cellspacing="1" cellpadding="3">

      <tr>
        <td width="25" align="center" bgcolor="#CCCCCC"><strong>No</strong></td>

        <td width="60" bgcolor="#CCCCCC"><strong>Tgl. Rawat </strong></td>
        <td width="100" bgcolor="#CCCCCC"><strong>Nomor Identitas </strong></td>
        <td width="250" bgcolor="#CCCCCC"><strong>Nama Santri </strong></td>
        <td width="100" bgcolor="#CCCCCC"><strong>Jenis Kelamin </strong></td>
        <td width="50" bgcolor="#CCCCCC"><strong>Lembaga </strong></td>
        <!-- <td width="85" align="right" bgcolor="#CCCCCC"><strong> Bayar (Rp) </strong></td>-->
        <td width="300" bgcolor="#CCCCCC"><strong>Hasil Anamnesa </strong></td>
        <td width="39" align="center" bgcolor="#CCCCCC"><strong>Tools</strong></td>
      </tr>
      <?php
      # Perintah untuk menampilkan Semua Daftar Transaksi rawat
      $mySql = "SELECT rawat.*, pasien.nm_pasien, pasien.kode_lembaga, pasien.jns_kelamin, no_identitas FROM rawat 
      LEFT JOIN pasien ON rawat.nomor_rm = pasien.nomor_rm and pasien.kode_lembaga=pasien.kode_lembaga and pasien.jns_kelamin=pasien.jns_kelamin

				ORDER BY rawat.no_rawat ASC LIMIT $hal, $row";
      $myQry = mysqli_query($koneksidb, $mySql)  or die("Query salah : " . mysqli_error($myQry));
      $nomor = $hal;
      while ($myData = mysqli_fetch_array($myQry)) {
        $nomor++;

        # Membaca Nomor Rawat
        $noRawat = $myData['no_rawat'];
      ?>
        <tr>
          <td><?php echo $nomor; ?></td>

          <td><?php echo IndonesiaTgl($myData['tgl_rawat']); ?></td>
          <td><?php echo $myData['no_identitas']; ?></td>
          <td><?php echo $myData['nm_pasien']; ?></td>
          <td><?php echo $myData['jns_kelamin']; ?></td>
          <td><?php echo $myData['kode_lembaga']; ?></td>
          <!-- <td align="right"><?php echo format_angka($myData['uang_bayar']); ?></td>-->
          <td><?php echo $myData['hasil_diagnosa']; ?></td>
          <td align="center"><a href="cetak/rawat_cetak.php?noRawat=<?php echo $noRawat; ?>" target="_blank">Cetak</a></td>
        </tr>
      <?php } ?>
      <tr>
        <td colspan="3"><strong>Jumlah Data :</strong><?php echo $jml; ?></td>
        <td colspan="5" align="right"><strong>Halaman ke :</strong>
          <?php
          for ($h = 1; $h <= $max; $h++) {
            $list[$h] = $row * $h - $row;
            echo " <a href='?page=Laporan-Rawat&hal=$list[$h]'>$h</a> ";
          }
          ?></td>
      </tr>
    </table>
  </div>
</div>