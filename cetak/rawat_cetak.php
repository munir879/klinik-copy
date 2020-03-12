<?php
include_once "../library/inc.connection.php";
include_once "../library/inc.library.php";

if ($_GET) {
  # Baca variabel URL
  $noRawat = $_GET['noRawat'];

  # Skrip untuk membaca data Rawat pasien
  $mySql = "SELECT rawat.*, pasien.nm_pasien, no_identitas FROM rawat 
				LEFT JOIN pasien ON rawat.nomor_rm = pasien.nomor_rm
				WHERE rawat.no_rawat='$noRawat'";
  $myQry = mysqli_query($koneksidb, $mySql)  or die("Query salah : " . mysqli_error($myQry));
  $myData = mysqli_fetch_array($myQry);
} else {
  echo "Nomor Rawat Tidak Terbaca";
  exit;
}
?>
<html>

<head>
  <title>:: Cetak Data Rawat Pasien per Nota | Klinik Nusantara Health Care</title>
  <link href="../styles/styles_cetak.css" rel="stylesheet" type="text/css">
</head>

<body>
  <h2> RAWAT PASIEN </h2>
  <table width="500" border="0" cellspacing="1" cellpadding="4" class="table-print">

    <tr>
      <td><b>Tgl. Rawat </b></td>
      <td><b>:</b></td>
      <td><?php echo IndonesiaTgl($myData['tgl_rawat']); ?></td>
    </tr>
    <tr>
      <td><b>Nomor Identitas </b></td>
      <td><b>:</b></td>
      <td><?php echo $myData['no_identitas']; ?></td>
    </tr>
    <tr>
      <td><strong>Nama Pasien </strong></td>
      <td><b>:</b></td>
      <td><?php echo $myData['nm_pasien']; ?></td>
    </tr>
    <tr>
      <td><strong>Diganosa</strong></td>
      <td><b>:</b></td>
      <td><?php echo $myData['hasil_diagnosa']; ?></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table class="table-list" width="700" border="0" cellspacing="1" cellpadding="2">
    <tr>
      <td colspan="5" bgcolor="#CCCCCC"><strong>DAFTAR TINDAKAN </strong></td>
    </tr>
    <tr>
      <td width="26" align="center" bgcolor="#F5F5F5"><b>No</b></td>
      <td width="76" bgcolor="#F5F5F5"><strong>Tanggal</strong></td>
      <td width="58" bgcolor="#F5F5F5"><strong>Kode </strong></td>
      <td width="332" bgcolor="#F5F5F5"><b>Nama Tindakan </b></td>
      <td width="182" bgcolor="#F5F5F5"><strong>Dokter</strong></td>
    </tr>
    <?php
    // Skrip untuk mengambil data Daftar Tindakan yang diambil Pasien
    $mySql = "SELECT rawat_tindakan.*, tindakan.nm_tindakan, dokter.nm_dokter FROM rawat_tindakan 
		 LEFT JOIN tindakan ON rawat_tindakan.kd_tindakan=tindakan.kd_tindakan 
		 LEFT JOIN dokter ON rawat_tindakan.kd_dokter=dokter.kd_dokter
		 WHERE rawat_tindakan.no_rawat='$noRawat' ORDER BY id_tindakan";
    $myQry = mysqli_query($koneksidb, $mySql) or die("Gagal Query Tmp" . mysqli_error($myQry));
    $nomor = 0;
    while ($myData = mysqli_fetch_array($myQry)) {
      $nomor++;
    ?>
      <tr>
        <td align="center"><?php echo $nomor; ?></td>
        <td><?php echo IndonesiaTgl($myData['tgl_tindakan']); ?></td>
        <td><?php echo $myData['kd_tindakan']; ?></td>
        <td><?php echo $myData['nm_tindakan']; ?></td>
        <td><?php echo $myData['nm_dokter']; ?></td>
      </tr>
    <?php } ?>
  </table>
  <br />
  <img src="../images/btn_print.png" height="20" onClick="javascript:window.print()" />
</body>

</html>