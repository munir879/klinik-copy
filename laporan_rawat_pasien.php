<?php
include_once "library/inc.seslogin.php";

// Variabel SQL
$filterSQL = "";

// Temporary Variabel form
$dataPasien  = isset($_POST['cmbPasien']) ? $_POST['cmbPasien'] : 'SEMUA';

# PENCARIAN DATA BERDASARKAN FILTER DATA
if (isset($_POST['btnTampil'])) {
  # PILIH pasien
  if (trim($_POST['cmbPasien']) == "KOSONG") {
    $filterSQL = "";
  } else {
    $filterSQL = "WHERE rawat.nomor_rm='$dataPasien'";
  }
} else {
  $filterSQL = "";
}

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$row = 50;
$hal = isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql = "SELECT * FROM rawat $filterSQL";
$pageQry = mysqli_query($koneksidb, $pageSql);
$jml   = mysqli_num_rows($pageQry);
$max   = ceil($jml / $row);
?>

<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header">Laporan Rawat Pasien</h1>
  </div>
  <!-- /.col-lg-12 -->
</div>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self">
  <div class="dataTable-wrapper">
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="dataTables-example" width="100%" cellspacing="1" cellpadding="3">
        <tr>
          <td colspan="3" bgcolor="#CCCCCC"><strong>FILTER DATA PASIEN </strong></td>
        </tr>
        <tr>
          <td width="130"><strong>Nama Pasien </strong></td>
          <td width="5"><strong>:</strong></td>
          <td width="351">
            <select name="cmbPasien">
              <option value="KOSONG">....</option>
              <?php
              $dataSql = "SELECT * FROM pasien ORDER BY nomor_rm";
              $dataQry = mysqli_query($koneksidb, $dataSql) or die("Gagal Query" . mysqli_error($dataQry));
              while ($dataRow = mysqli_fetch_array($dataQry)) {
                if ($dataRow['nomor_rm'] == $dataPasien) {
                  $cek = " selected";
                } else {
                  $cek = "";
                }
                echo "<option value='$dataRow[nomor_rm]' $cek> $dataRow[nm_pasien] [ $dataRow[nomor_rm] ]</option>";
              }
              ?>
            </select>
            <input name="btnTampil" type="submit" value=" Tampilkan " /></td>
        </tr>
      </table>
    </div>
  </div>
</form>

<div class="dataTable-wrapper">
  <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example" width="100%" cellspacing="1" cellpadding="3">

      <tr>
        <td width="25" align="center" bgcolor="#CCCCCC"><strong>No</strong></td>

        <td width="50" bgcolor="#CCCCCC"><strong>Tgl. Rawat </strong></td>
        <td width="100" bgcolor="#CCCCCC"><strong>No Induk</strong></td>
        <td width="250" bgcolor="#CCCCCC"><strong>Nama Santri </strong></td>
        <td width="100" bgcolor="#CCCCCC"><strong>Jenis Kelamin </strong></td>
        <td width="50" bgcolor="#CCCCCC"><strong>Lembaga </strong></td>
        <!-- <td width="85" align="right" bgcolor="#CCCCCC"><strong> Bayar (Rp) </strong></td>-->
        <td width="300" bgcolor="#CCCCCC"><strong>Diagnosa</strong></td>
        <td width="39" align="center" bgcolor="#CCCCCC"><strong>Tools</strong></td>
      </tr>
      <?php
      # Perintah untuk menampilkan Semua Daftar Transaksi rawat
      $mySql = "SELECT rawat.*, pasien.nm_pasien, pasien.kode_lembaga, pasien.jns_kelamin, no_identitas FROM rawat 
  LEFT JOIN pasien ON rawat.nomor_rm = pasien.nomor_rm and pasien.kode_lembaga=pasien.kode_lembaga and pasien.jns_kelamin=pasien.jns_kelamin
				$filterSQL
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
          <!--  <td align="right"><?php echo format_angka($myData['uang_bayar']); ?></td>-->
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
            echo " <a href='?page=Laporan-Rawat-Pasien&hal=$list[$h]'>$h</a> ";
          }
          ?></td>
      </tr>
    </table>
  </div>
</div>