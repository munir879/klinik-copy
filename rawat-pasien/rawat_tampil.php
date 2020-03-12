<?php
include_once "../library/inc.seslogin.php";

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$row = 50;
$hal = isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql = "SELECT * FROM rawat";
$pageQry = mysqli_query($koneksidb, $pageSql) or die("error paging: " . mysqli_error($pageQry));
$jml   = mysqli_num_rows($pageQry);
$max   = ceil($jml / $row);
?><table width="800" border="0" cellpadding="2" cellspacing="1" class="table-border">
  <tr>
    <td width="5" align="right">&nbsp;</td>
    <td colspan="2" align="right">
      <h1><b>DATA RAWAT PASIEN </b></h1>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">
      <table class="table-list" width="100%" border="0" cellspacing="1" cellpadding="2">
        <tr>
          <th width="29" align="center"><strong>No</strong></th>
          <th width="102"><strong>No. Rawat </strong></th>
          <th width="103"><strong>Tgl. Rawat </strong></th>
          <th width="133"><strong>Nomor Induk </strong></th>
          <th width="291"><strong>Nama Pasien </strong></th>
          <th width="291"><strong>Hasil Diagnosa </strong></th>
          <td colspan="2" align="center" bgcolor="#CCCCCC"><strong>Tools</strong></td>
        </tr>
        <?php
        $mySql = "SELECT rawat.*, pasien.nm_pasien, no_identitas
				FROM rawat 
				LEFT JOIN pasien ON rawat.nomor_rm = pasien.nomor_rm and pasien.no_identitas = pasien.no_identitas
				ORDER BY rawat.no_rawat DESC LIMIT $hal, $row";
        $myQry = mysqli_query($koneksidb, $mySql)  or die("Query salah : " . mysqli_error($myQry));
        $nomor = 0;
        while ($myData = mysqli_fetch_array($myQry)) {
          $nomor++;
          $Kode = $myData['no_rawat'];
        ?>
          <tr>
            <td><?php echo $nomor; ?></td>
            <td><?php echo $myData['no_rawat']; ?></td>
            <td><?php echo IndonesiaTgl($myData['tgl_rawat']); ?></td>
            <td><?php echo $myData['no_identitas']; ?></td>
            <td><?php echo $myData['nm_pasien']; ?></td>
            <td><?php echo $myData['hasil_diagnosa']; ?></td>
            <!-- <td width="45" align="center"><a href="rawat_nota.php?nomorRawat=<?php echo $Kode; ?>" target="_blank">Nota</a></td> -->
            <td width="45" align="center"><a href="?page=Rawat-Hapus&Kode=<?php echo $Kode; ?>" target="_self" alt="Delete Data" onclick="return confirm('ANDA YAKIN AKAN MENGHAPUS DATA RAWAT INI ... ?')">Delete</a></td>
          </tr>
        <?php } ?>
      </table>
    </td>
  </tr>
  <tr class="selKecil">
    <td>&nbsp;</td>
    <td width="299"><b>Jumlah Data :</b></td>
    <td width="480" align="right"><b>Halaman ke :</b>
      <?php
      for ($h = 1; $h <= $max; $h++) {
        $list[$h] = $row * $h - $row;
        echo " <a href='?page=Rawat-Tampil&hal=$list[$h]'>$h</a> ";
      }
      ?></td>
  </tr>
</table>