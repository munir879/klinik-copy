<?php
$NomorRM = isset($_GET['NomorRM']) ?  $_GET['NomorRM'] : '';
include_once "../library/inc.seslogin.php";

// Membaca variabel form
$KeyWord  = isset($_GET['KeyWord']) ? $_GET['KeyWord'] : '';
$dataCari  = isset($_POST['txtCari']) ? $_POST['txtCari'] : $KeyWord;

// Jika tombol Cari diklik
if (isset($_POST['btnCari'])) {
  if ($_POST) {
    $filterSql = "WHERE nm_obat LIKE '%$dataCari%'";
  }
} else {
  if ($KeyWord) {
    $filterSql = "WHERE nm_obat LIKE '%$dataCari%'";
  } else {
    $filterSql = "";
  }
}

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$row = 50;
$hal = isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql = "SELECT * FROM obat $filterSql";
$pageQry = mysqli_query($koneksidb, $pageSql) or die("error paging: " . mysqli_error($pageQry));
$jml   = mysqli_num_rows($pageQry);
$max   = ceil($jml / $row);
?>
<h1>Pencarian Obat </h1>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self" id="form1">
  <b>Cari Nama Obat :
    <input name="txtCari" type="text" value="<?php echo $dataCari; ?>" size="40" maxlength="100" />
    <input name="btnCari" type="submit" value="Cari" />
  </b>
</form>
<table class="table-list" width="700" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <th width="31" align="center" bgcolor="#CCCCCC">No</th>
    <th width="92" bgcolor="#CCCCCC"><strong>Kode </strong></th>
    <th width="392" bgcolor="#CCCCCC"><strong>Nama Obat </strong></th>

    <th width="56" bgcolor="#CCCCCC"><strong>Stok </strong></th>
  </tr>
  <?php
  $mySql = "SELECT * FROM obat $filterSql ORDER BY kd_obat ASC LIMIT $hal, $row";
  $myQry = mysqli_query($koneksidb, $mySql)  or die("Query salah : " . mysqli_error($myQry));
  $nomor = 0;
  while ($myData = mysqli_fetch_array($myQry)) {
    $nomor++;
  ?>
    <tr>
      <td align="center"><?php echo $nomor; ?></td>
      <td><?php echo $myData['kd_obat']; ?></td>
      <td><?php echo $myData['nm_obat']; ?></td>

      <td align="center"><?php echo $myData['stok']; ?></td>
      <td><?php if ($myData['stok'] >= 5) {
            echo '<a href="?page=Rawat-Baru&NomorRM=' . $NomorRM . '&obat=' . $myData['kd_obat'] . '". target="_self" alt="Rawat">pilih</a>';
          } else {
            echo 'maaf stok tidak mencukupi';
          } ?>

      </td>
    </tr>
  <?php } ?>
  <tr>
    <td colspan="2"><strong>Jumlah Data :</strong> <?php echo $jml; ?> </td>
    <td colspan="3" align="right"><strong>Halaman ke :</strong>
      <?php
      for ($h = 1; $h <= $max; $h++) {
        $list[$h] = $row * $h - $row;
        echo " <a href='?page=Pencarian-Obat&hal=$list[$h]&KeyWord=$dataCari'>$h</a> ";
      }
      ?></td>
  </tr>
</table>