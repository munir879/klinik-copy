<?php
include_once "../library/inc.seslogin.php";

// Periksa ada atau tidak variabel Kode pada URL (alamat browser)
if (isset($_GET['Kode'])) {
	$Kode	= $_GET['Kode'];

	// Hapus data sesuai Kode yang didapat di URL
	$mySqlrawat = "DELETE FROM rawat WHERE no_rawat='$Kode'";
	$mySqltindak = "DELETE FROM rawat_tindakan WHERE no_rawat='$Kode'";
	$sqldobat = "DELETE FROM obat_rawat WHERE no_rawat='$Kode'";
	$mysqlstok = "SELECT no_stok FROM obat_rawat WHERE no_rawat = '$Kode'";


	$sqlobat = mysqli_query($koneksidb, $mysqlstok);


	while ($no_stok = mysqli_fetch_array($sqlobat)) {
		$kdobat = $no_stok['no_stok'];

		mysqli_query($koneksidb, "DELETE FROM stok_obat WHERE no_stok='$kdobat'");
	}




	$myQry = mysqli_query($koneksidb, $mySqlrawat) or die("Eror hapus data" . mysqli_error($myQry));
	if ($myQry) {
		// Hapus data pada tabel anak (rawat_tindakan)
		$mySql = "DELETE FROM rawat_tindakan WHERE no_rawat='$Kode'";
		mysqli_query($koneksidb, $mySql) or die("Eror hapus data" . mysqli_error(mysqli_query($koneksidb, $mySql)));
		mysqli_query($koneksidb, $sqldobat);
		// Refresh halaman
		echo "<meta http-equiv='refresh' content='0; url=?page=Rawat-Tampil'>";
	}
} else {
	// Jika tidak ada data Kode ditemukan di URL
	echo "<b>Data yang dihapus tidak ada</b>";
}
