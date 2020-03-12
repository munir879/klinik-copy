<?php
include_once "library/inc.seslogin.php";
?>


<form action="?page=Upload-Aksi" method="post" enctype="multipart/form-data" target="_self">
  <table class="table-list" width="100%" border="0" cellspacing="1" cellpadding="3">

    <br>
    <tr>
      <th colspan="3"><strong>TAMBAH DATA SANTRI </strong></th>

    </tr>
    <tr>
      <td><a href="./Book1.xls">file contoh</a></td>
    </tr>
    <td><strong>Pilih File</strong></td>
    <td><strong>:</strong></td>
    <td><input name="pasien" type="file" required="required"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td>&nbsp;<input name="upload" type="submit"></td>
    </tr>
  </table>
</form>