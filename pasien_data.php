<?php
include_once "library/inc.seslogin.php";

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$row = 50;
$hal = isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql = "SELECT * FROM pasien";
$pageQry = mysqli_query($koneksidb, $pageSql) or die("error paging: " . mysqli_error($pageQry));
$jml   = mysqli_num_rows($pageQry);
$max   = ceil($jml / $row);

// Jika tombol Cari diklik
if (isset($_POST['btnCari'])) {
  if ($_POST) {
    // Cari berdasarkan Nomor RM dan Nama Pasien yang mirip
    $txtKataKunci  = $_POST['txtKataKunci'];
    $mySql = "SELECT * FROM pasien WHERE no_identitas='$txtKataKunci' OR nm_pasien LIKE '%$txtKataKunci%' 
				  ORDER BY nomor_rm ASC LIMIT $hal, $row";
  }
} else {
  $mySql = "SELECT * FROM pasien ORDER BY nomor_rm ASC LIMIT $hal, $row";
}

// Membaca variabel form
$dataKataKunci  = isset($_POST['txtKataKunci']) ? $_POST['txtKataKunci'] : '';
?>

<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header">Data Pasien</h1>
  </div>
  <!-- /.col-lg-12 -->
</div>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self" id="form1">
  <div class="dataTable-wrapper">
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="dataTables-example" width="100%" cellspacing="1" cellpadding="3">
        <!-- <tr>
      <th colspan="3"><strong>CARI PASIEN </strong></th>
    </tr> -->
        <tr>
          <td width="139"><strong>Nomor Induk / Nama </strong></td>
          <td width="1"><strong>:</strong></td>
          <td width="332"><b>
              <input name="txtKataKunci" type="text" value="<?php echo $dataKataKunci; ?>" size="40" maxlength="100" />
            </b>
            <input name="btnCari" type="submit" value="Cari" />
          </td>
        </tr>



        <!-- <table  class="table table-striped table-bordered table-hover" id="dataTables-example"  width="100%"cellspacing="1" cellpadding="3"> -->
        <tr>
          <td colspan="2"><a href="?page=Pasien-Add" target="_self"><img src="images/btn_add_data.png" height="30" border="0" /></a></td>

          <!-- </tr>
  <tr> -->
          <td> <a href="?page=Pasien-Upload">Import Data Excell</a></td>
        </tr>

      </table>
    </div>
  </div>
</form>

<tr>
  <td colspan="2">
    <div class="dataTable-wrapper">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" id="dataTables-example" width="100%" cellspacing="1" cellpadding="3">
          <tr>
            <th width="25" align="center"><strong>No</strong></th>
            <th width="90"><strong>No Induk</strong></th>
            <th width="280"><strong>Nama Pasien </strong></th>
            <th width="95"><strong>Kelamin</strong></th>
            <th width="80"><strong>Tanggal Lahir</strong></th>
            <th width="80"><strong>Lembaga</strong></th>
            <th width="80"><strong>Tahun Masuk</strong></th>
            <td colspan="2" align="center" bgcolor="#CCCCCC"><strong>Tools</strong></td>
          </tr>
          <?php
          // $mySql = "SELECT * FROM pasien ORDER BY nomor_rm ASC LIMIT $hal, $row";
          $myQry = mysqli_query($koneksidb, $mySql)  or die("Query salah : " . mysqli_error($myQr));
          $nomor = 0;
          while ($myData = mysqli_fetch_array($myQry)) {
            $nomor++;
            $Kode = $myData['nomor_rm'];
          ?>
            <tr>
              <td><?php echo $nomor; ?></td>
              <td><?php echo $myData['no_identitas']; ?></td>
              <td><?php echo $myData['nm_pasien']; ?></td>
              <td><?php echo $myData['jns_kelamin']; ?></td>
              <td><?php echo $myData['tanggal_lahir']; ?></td>
              <td><?php echo $myData['kode_lembaga']; ?></td>
              <td><?php echo $myData['tahun_ajaran']; ?></td>
              <td width="40" align="center"><a href="?page=Pasien-Edit&Kode=<?php echo $Kode; ?>" target="_self" alt="Edit Data">Edit</a></td>
              <td width="48" align="center"><a href="?page=Pasien-Delete&Kode=<?php echo $Kode; ?>" target="_self" alt="Delete Data" onclick="return confirm('ANDA YAKIN AKAN MENGHAPUS DATA PASIEN INI ... ?')">Delete</a></td>
            </tr>
          <?php } ?>
        </table>
      </div>
    </div>
  </td>
</tr>
<tr>
  <td width="418"><strong>Jumlah Data :</strong> <?php echo $jml; ?> </td>
  <td width="371" align="right"><strong>Halaman ke :</strong>
    <?php
    for ($h = 1; $h <= $max; $h++) {
      $list[$h] = $row * $h - $row;
      echo " <a href='?page=Pasien-Data&hal=$list[$h]'>$h</a> ";
    }
    ?></td>
</tr>
</table>