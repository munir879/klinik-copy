<?php
include_once "library/inc.seslogin.php";

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$row = 50;
$hal = isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql = "SELECT * FROM tindakan";
$pageQry = mysqli_query($koneksidb, $pageSql) or die("error paging: " . mysqli_error($pageQry));
$jml   = mysqli_num_rows($pageQry);
$max   = ceil($jml / $row);

// Jika tombol Cari diklik
if (isset($_POST['btnCari'])) {
  if ($_POST) {
    // Cari berdasarkan Nomor RM dan Nama Pasien yang mirip
    $txtKataKunci  = $_POST['txtKataKunci'];
    $mySql = "SELECT * FROM tindakan WHERE kd_tindakan='$txtKataKunci' OR nm_tindakan LIKE '%$txtKataKunci%' 
				  ORDER BY kd_tindakan ASC LIMIT $hal, $row";
  }
} else {
  $mySql = "SELECT * FROM tindakan ORDER BY kd_tindakan ASC LIMIT $hal, $row";
}

// Membaca variabel form
$dataKataKunci  = isset($_POST['txtKataKunci']) ? $_POST['txtKataKunci'] : '';
?>

<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header">Data Diagnosa</h1>
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
          <td width="139"><strong>Kode / Nama Diagnosa</strong></td>
          <td width="1"><strong>:</strong></td>
          <td width="332"><b>
              <input name="txtKataKunci" type="text" value="<?php echo $dataKataKunci; ?>" size="40" maxlength="100" />
            </b>
            <input name="btnCari" type="submit" value="Cari" />
          </td>
        </tr>

        <!-- <table class="table table-striped table-bordered table-hover" id="dataTables-example" width="100%" cellspacing="1" cellpadding="3"> -->
        <tr>
          <td colspan="2"><a href="?page=Tindakan-Add" target="_self"><img src="images/btn_add_data.png" height="30" border="0" /></a></td>
        </tr>

      </table>
    </div>
  </div>
</form>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>
<tr>
  <td colspan="2">
    <div class="dataTable-wrapper">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" id="dataTables-example" width="100%" cellspacing="1" cellpadding="3">
          <thead>
            <tr>
              <th width="5%"><strong>No</strong></th>
              <th width="5%"><strong>Kode</strong></th>
              <th width="67%"><strong>Nama Diagnosa</strong></th>

              <th colspan="2" align="center" bgcolor="#CCCCCC"><strong>
                  <center>Tools</center>
                </strong></th>
            </tr>
          </thead>
          <?php
          // Menampilkan daftar tindakan
          // $mySql = "SELECT * FROM tindakan ORDER BY kd_tindakan ASC LIMIT $hal, $row";
          $myQry = mysqli_query($koneksidb, $mySql)  or die("Query salah : " . mysqli_error($myQry));
          $nomor = 0;
          while ($myData = mysqli_fetch_array($myQry)) {
            $nomor++;
            $Kode = $myData['kd_tindakan'];
          ?>
            <tbody>
              <tr>
                <td><?php echo $nomor; ?></td>
                <td><?php echo $myData['kd_tindakan']; ?></td>
                <td><?php echo $myData['nm_tindakan']; ?></td>

                <td width="7%" align="center"><a href="?page=Tindakan-Edit&Kode=<?php echo $Kode; ?>" target="_self">Edit</a></td>
                <td width="7%" align="center"><a href="?page=Tindakan-Delete&Kode=<?php echo $Kode; ?>" target="_self" onclick="return confirm('ANDA YAKIN AKAN MENGHAPUS DATA DIAGNOSA KLINIK INI ... ?')">Delete</a></td>
              </tr>
            </tbody>
          <?php } ?>
        </table>

      </div>
    </div>
  </td>
</tr>
<tr>
  <td width="350"><strong>Jumlah Data :</strong> <?php echo $jml; ?> </td>
  <td width="339" align="right"><strong>Halaman ke :</strong>
    <?php
    for ($h = 1; $h <= $max; $h++) {
      $list[$h] = $row * $h - $row;
      echo " <a href='?page=Tindakan-Data&hal=$list[$h]'>$h</a> ";
    }
    ?></td>
</tr>
</table>