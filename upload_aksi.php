<?php
// menghubungkan dengan koneksi
include 'koneksi.php';
// menghubungkan dengan library excel reader
include "excel_reader2.php";

include_once "library/inc.seslogin.php";
?>

<form>
	<table class="table-list" width="100%" border="0" cellspacing="1" cellpadding="3">

		<?php
		// upload file xls

		$target = basename($_FILES['filepegawai']['name']);
		move_uploaded_file($_FILES['filepegawai']['tmp_name'], $target);

		// beri permisi agar file xls dapat di baca
		chmod($_FILES['filepegawai']['name'], 0777);

		// mengambil isi file xls
		$data = new Spreadsheet_Excel_Reader($_FILES['filepegawai']['name'], false);
		// menghitung jumlah baris data yang ada
		$jumlah_baris = $data->rowcount($sheet_index = 0);

		// jumlah default data yang berhasil di import
		$berhasil = 0;
		for ($i = 2; $i <= $jumlah_baris; $i++) {

			// menangkap data dan memasukkan ke variabel sesuai dengan kolumnya masing-masing
			$nama     = $data->val($i, 1);
			$alamat   = $data->val($i, 2);
			$telepon  = $data->val($i, 3);

			$nomor_rm  = $data->val($i, 1);
			$nm_pasien  = $data->val($i, 2);
			$no_identitas  = $data->val($i, 3);
			$jns_kelamin  = $data->val($i, 4);
			$gol_darah  = $data->val($i, 5);
			$agama  = $data->val($i, 6);
			$tempat_lahir  = $data->val($i, 7);
			$tanggal_lahir  = $data->val($i, 8);
			$no_telepon  = $data->val($i, 9);
			$alamat  = $data->val($i, 10);
			$kode_lembaga  = $data->val($i, 11);
			$tahun_ajaran  = $data->val($i, 12);




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
				// input data ke database (table data_pegawai)
				mysqli_query($koneksi, "INSERT into klinik-apotekdb values('',
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
		'$tahun_ajaran'
)");
				$berhasil++;
			}
		}

		// hapus kembali file .xls yang di upload tadi
		unlink($_FILES['filepegawai']['name']);

		// alihkan halaman ke index.php
		//header("location:pasien_data.php");
		?>

		<tr>
			<br>
			<td><strong>jumlah Data Berhasil </strong></td>
			<td><strong>:</strong></td>
			<td><input value="<?php echo $berhasil; ?>" size="40" maxlength="40" /></td>
		</tr>

	</table>
</form>