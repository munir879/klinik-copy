
<?php
// menghubungkan dengan koneksi
include './library/inc.connection.php';
// menghubungkan dengan library excel reader
include "excel_reader2.php";

?>

<?php
// upload file xls


$target = basename($_FILES['pasien']['name']);
move_uploaded_file($_FILES['pasien']['tmp_name'], $target);

// beri permisi agar file xls dapat di baca
chmod($_FILES['pasien']['name'], 0777);

// mengambil isi file xls
$data = new Spreadsheet_Excel_Reader($_FILES['pasien']['name'], false);
// menghitung jumlah baris data yang ada
$jumlah_baris = $data->rowcount($sheet_index = 0);

// jumlah default data yang berhasil di import
$berhasil = 0;
for ($i = 6; $i <= $jumlah_baris; $i++) {
	// menangkap data dan memasukkan ke variabel sesuai dengan kolumnya masing-masing

	$nomor_rm = buatKode("pasien", "RM");
	$nm_pasien     = addslashes($data->val($i, 1));
	$no_identitas     = $data->val($i, 2);
	$jns_kelamin = $data->val($i, 3);
	$gol_darah = $data->val($i, 4);
	$agama = $data->val($i, 5);
	$tempat_lahir = $data->val($i, 6);
	$original_date = $data->val($i, 7);
	$timestamp = str_replace('/', '-', $original_date);
	$tanggal_lahir = date("Y-m-d", strtotime($timestamp));
	$no_telepon = $data->val($i, 8);
	$alamat = $data->val($i, 9);
	$kode_lembaga = $data->val($i, 10);
	$tahun_ajaran = $data->val($i, 11);



	if (
		$nomor_rm != "" &&
		$nm_pasien != "" &&
		$no_identitas != "" &&
		$jns_kelamin != "" &&
		$gol_darah != "" &&
		$agama != "" &&
		$tempat_lahir != "" &&
		$tanggal_lahir != "" &&
		$no_telepon != "" &&
		$alamat != "" &&
		$kode_lembaga != "" &&
		$tahun_ajaran != ""
	) {

		$query = mysqli_query($koneksidb, "INSERT into pasien (
		nomor_rm,
		nm_pasien,
		no_identitas,
		jns_kelamin,
		gol_darah,
		agama,
		tempat_lahir,
		tanggal_lahir,
		no_telepon,
		alamat,
		kode_lembaga,
		tahun_ajaran
		) values(
		'$nomor_rm',
		'$nm_pasien',
		'$no_identitas',
		'$jns_kelamin',
		'$gol_darah',
		'$agama',
		'$tempat_lahir',
		'$tanggal_lahir',
		'$no_telepon',
		'$alamat',
		'$kode_lembaga',
		'$tahun_ajaran')");
		$berhasil++;
	}
}

echo $berhasil;








// hapus kembali file .xls yang di upload tadi
unlink($_FILES['pasien']['name']);

// alihkan halaman ke index.php
//header("location:pasien_data.php");
?>