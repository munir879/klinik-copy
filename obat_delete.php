<?php
include_once "library/inc.seslogin.php";

// Periksa ada atau tidak variabel Kode pada URL (alamat browser)
if (isset($_GET['Kode'])) {
	// Hapus data sesuai Kode yang didapat di URL
	$mySql = "DELETE FROM obat WHERE kd_obat='" . $_GET['Kode'] . "'";
	$myQry = mysqli_query($koneksidb, $mySql) or die("Eror hapus data" . mysqli_error($myQry));
	if ($myQry) {
		// Refresh halaman
		echo "<meta http-equiv='refresh' content='0; url=?page=Obat-Data'>";
	}
} else if (isset($_GET['Stok'])) {

	// Hapus data sesuai Kode yang didapat di URL
	$obat = $_GET['Obat'];
	$viewsql = "SELECT JUMLAH";

	$update = "UPDATE obat
	SET stok = stok - (SELECT jumlah FROM stok_obat WHERE no_stok = '" . $_GET['Stok'] . "')
	WHERE kd_obat = '$obat'";

	$updatesql = mysqli_query($koneksidb, $update);

	$mySql = "DELETE FROM stok_obat WHERE no_stok='" . $_GET['Stok'] . "'";
	$myQry = mysqli_query($koneksidb, $mySql) or die("Eror hapus data" . mysqli_error($myQry));
	if ($myQry) {
		// Refresh halaman



		echo "<meta http-equiv='refresh' content='0; url=?page=Stok-Obat&Kode=" . $obat . "'>";
	}
} else {
	// Jika tidak ada data Kode ditemukan di URL
	echo "<b>Data yang dihapus tidak ada</b>";
}
