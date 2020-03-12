<?php
include_once "../library/inc.seslogin.php";

// Periksa ada atau tidak variabel Kode pada URL (alamat browser)
if (isset($_GET['Kode'])) {
	$Kode	= $_GET['Kode'];

	// Hapus data sesuai Kode yang didapat di URL
	$mySql = "DELETE FROM penjualan WHERE no_penjualan='$Kode'";
	$myQry = mysqli_query($koneksidb, $mySql) or die("Eror hapus data" . mysqli_error($myQry));
	if ($myQry) {

		// Baca data dalam tabel anak (penjualan_item)
		$bacaSql = "SELECT * FROM penjualan_item WHERE no_penjualan='$Kode'";
		$bacaQry = mysqli_query($koneksidb, $bacaSql) or die("Gagal Query baca data" . mysqli_error($bacaQry));
		while ($bacaData = mysqli_fetch_array($bacaQry)) {
			$KodeObat	= $bacaData['kd_obat'];
			$jumlah		= $bacaData['jumlah'];

			// Skrip Kembalikan Jumlah Stok
			$stokSql = "UPDATE obat SET stok = stok + $jumlah WHERE kd_obat='$KodeObat'";
			mysqli_query($stokSql, $koneksidb) or die("Gagal Query Edit Stok" . mysqli_error(mysqli_query($stokSql, $koneksidb)));
		}

		// Hapus data pada tabel anak (penjualan_item)
		$mySql = "DELETE FROM penjualan_item WHERE no_penjualan='$Kode'";
		mysqli_query($koneksidb, $mySql) or die("Eror hapus data" . mysqli_error(mysqli_query($koneksidb, $mySql)));

		// Refresh halaman
		echo "<meta http-equiv='refresh' content='0; url=?page=Penjualan-Tampil'>";
	}
} else {
	// Jika tidak ada data Kode ditemukan di URL
	echo "<b>Data yang dihapus tidak ada</b>";
}
