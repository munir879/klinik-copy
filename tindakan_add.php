<?php
include_once "library/inc.seslogin.php";

$kodeBaru	= buatKode("tindakan", "T");
# Tombol Simpan diklik
if (isset($_POST['btnSimpan'])) {
	# Validasi form, jika kosong sampaikan pesan error
	$pesanError = array();
	if (trim($_POST['txtNama']) == "") {
		$pesanError[] = "Data <b>Nama Tindakan</b> tidak boleh kosong !";
	}
	/*if (trim($_POST['txtHarga']) == "") {
		$pesanError[] = "Data <b>Harga (Rp.)</b> tidak boleh kosong !";
	}*/

	# Baca Variabel Form
	$txtNama	= $_POST['txtNama'];
	$txtKode	= $_POST['txtKode'];

	# Validasi Nama tindakan, jika sudah ada akan ditolak
	$cekSql = "SELECT * FROM tindakan WHERE nm_tindakan='$txtNama'";
	$cekQry = mysqli_query($koneksidb, $cekSql) or die("Eror Query" . mysqli_error($cekQry));
	if (mysqli_num_rows($cekQry) >= 1) {
		$pesanError[] = "Maaf, Tindakan <b> $txtNama </b> sudah ada, ganti dengan yang lain";
	}

	# JIKA ADA PESAN ERROR DARI VALIDASI
	if (count($pesanError) >= 1) {
		echo "<div class='mssgBox'>";
		echo "<img src='images/attention.png'> <br><hr>";
		$noPesan = 0;
		foreach ($pesanError as $indeks => $pesan_tampil) {
			$noPesan++;
			echo "&nbsp;&nbsp; $noPesan. $pesan_tampil<br>";
		}
		echo "</div> <br>";
	} else {
		# SIMPAN DATA KE DATABASE. 
		// Jika tidak menemukan error, simpan data ke database

		$mySql	= "INSERT INTO tindakan (kd_tindakan, nm_tindakan) VALUES ('$txtKode','$txtNama')";
		$myQry	= mysqli_query($koneksidb, $mySql) or die("Gagal query" . mysqli_error($myQry));
		if ($myQry) {
			echo "<meta http-equiv='refresh' content='0; url=?page=Tindakan-Data'>";
		}
		exit;
	}
}

# VARIABEL DATA UNTUK DIBACA FORM
// Supaya saat ada pesan error, data di dalam form tidak hilang. Jadi, tinggal meneruskan/memperbaiki yg salah
// $dataKode	= buatKode("tindakan", "T");
$dataKode	= isset($_POST['txtKodoe']) ? $_POST['txtKode'] : '';
$dataNama	= isset($_POST['txtNama']) ? $_POST['txtNama'] : '';
//$dataHarga	= isset($_POST['txtHarga']) ? $_POST['txtHarga'] : '';
?>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self" id="form1">
	<table width="100%" class="table-list" border="0" cellpadding="4" cellspacing="1">
		<tr>
			<th colspan="3" scope="col">TAMBAH DIAGNOSA </th>
		</tr>
		<td>&nbsp;</td>
		<tr>
			<td width="181"><strong>Kode</strong></td>
			<td width="3">: </td>
			<td width="1019"><input name="txtKode" value="" size="10" maxlength="10" /></td>
		</tr>
		<td>&nbsp;</td>
		<tr>
			<td><strong>Nama Diagnosa </strong></td>
			<td>:</td>
			<td><input name="txtNama" value="<?php echo $dataNama; ?>" size="60" maxlength="100" /></td>
		</tr>

		<tr>
			<td>&nbsp;</td>
			<td><br><br><br>&nbsp;</td> <br>
			<td><input type="submit" name="btnSimpan" value=" SIMPAN "></td>
		</tr>
	</table>
</form>