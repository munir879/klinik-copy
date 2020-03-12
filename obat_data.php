<?php
include_once "library/inc.seslogin.php";
include_once "library/inc.library.php";

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$row = 50;
$hal = isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql = "SELECT * FROM obat";
$pageQry = mysqli_query($koneksidb, $pageSql) or die("error paging: " . mysqli_error($pageQry));
$jml   = mysqli_num_rows($pageQry);
$max   = ceil($jml / $row);
?>

<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header">Data Obat</h1>
  </div>
  <!-- /.col-lg-12 -->
</div>

<table class="table table-striped table-bordered table-hover" id="dataTables-example" width="100%" cellspacing="1" cellpadding="3">
  <tr>
    <td width="401" colspan="2"><a href="?page=Obat-Add" target="_self"><img src="images/btn_add_data.png" height="30" border="0" /></a></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
      <div class="dataTable-wrapper">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover" id="dataTables-example" width="100%" cellspacing="1" cellpadding="3">
            <tr>
              <th width="20" align="center"><strong>No</strong></th>
              <th width="30" align="center"><strong>Kode</strong></th>
              <th width="130"><strong>Nama Obat</strong></th>
              <th width="130"><strong>Sisa Stok</strong></th>
              <th width="68" align="center"><strong>Stok Minimal</strong></th>

              <th width="180"><strong>Keterangan</strong></th>
              <td colspan="3" align="center" bgcolor="#CCCCCC"><strong>Tools</strong></td>
            </tr>
            <?php
            $mySql = "SELECT
  obat.kd_obat AS kd_obat,
  nm_obat,
  stok,
  keterangan,
  SUM(IF( jenis = 1, jumlah, 0)) - SUM(IF( jenis = 2, jumlah, 0)) AS stok_s

FROM
  obat
  LEFT JOIN stok_obat ON stok_obat.kd_obat = obat.kd_obat
  GROUP BY obat.kd_obat ORDER BY obat.kd_obat ASC LIMIT $hal, $row";
            $myQry = mysqli_query($koneksidb, $mySql)  or die("Query salah : " . mysqli_error($myQry));
            $nomor  = 0;
            while ($myData = mysqli_fetch_array($myQry)) {
              $nomor++;
              $Kode = $myData['kd_obat'];
            ?>
              <tr>
                <td align="center"><?php echo $nomor; ?></td>
                <td align="center"><?php echo $myData['kd_obat']; ?></td>
                <td><?php echo $myData['nm_obat']; ?></td>
                <td align="center"><?php echo $myData['stok_s']; ?></td>
                <td align="center"><?php echo $myData['stok']; ?></td>

                <td><?php echo $myData['keterangan']; ?></td>
                <td width="45" align="center"><a href="?page=Obat-Edit&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Edit Data">Edit</a></td>
                <td width="44" align="center"><a href="?page=Obat-Delete&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Delete Data" onclick="return confirm('ANDA YAKIN AKAN MENGHAPUS DATA OBAT INI ... ?')">Delete</a></td>
                <td width="45" align="center"><a href="?page=Stok-Obat&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Edit Data">Stok</a></td>
              </tr>

            <?php } ?>
          </table>
        </div>
      </div>
    </td>
  </tr>
  <tr>
    <td><strong>Jumlah Data :</strong> <?php echo $jml; ?> </td>
    <td align="right">
      <strong>Halaman ke :</strong>
      <?php
      for ($h = 1; $h <= $max; $h++) {
        $list[$h] = $row * $h - $row;
        echo " <a href='?page=Obat-Data&hal=$list[$h]'>$h</a> ";
      }
      ?> </td>
  </tr>
</table>