<?php
include_once "../library/inc.seslogin.php";
include_once "../library/inc.library.php";
$alert = "";

if (!isset($_COOKIE['dokter'])) {
} else {
	$cdokter = $_COOKIE['dokter'];
}
if (!isset($_COOKIE['tindak'])) {
} else {
	$tindak = $_COOKIE['tindak'];
}
if (!isset($_COOKIE['diagnosa'])) {
} else {
	$diagnosa = $_COOKIE['diagnosa'];
}

# HAPUS DAFTAR tindakan DI TMP
if (isset($_GET['Aksi'])) {
	if (trim($_GET['Aksi']) == "Hapus") {
		# Hapus Tmp jika datanya sudah dipindah
		$id			= $_GET['id'];
		$userLogin	= $_SESSION['SES_LOGIN'];

		$mySql = "DELETE FROM tmp_rawat WHERE id='$id' AND kd_petugas='$userLogin'";
		mysqli_query($koneksidb, $mySql) or die("Gagal kosongkan tmp" . mysqli_error(mysqli_query($koneksidb, $mySql)));
	}
	if (trim($_GET['Aksi']) == "Sucsses") {
		echo "<b>DATA BERHASIL DISIMPAN</b> <br><br>";
	}
}
// =========================================================================

# TOMBOL TAMBAH DIKLIK
if (isset($_POST['btnTambah'])) {
	$pesanError = array();
	if (trim($_POST['cmbDokter']) == "KOSONG") {
		$pesanError[] = "Data <b>Nama Dokter</b> belum dipilih, harus Anda pilih dari combo !";
	}
	if ($_POST['cmbTindakan'] == "") {
		$pesanError[] = "Data <b>Nama Tindakan</b> belum dipilih, harus Anda pilih dari combo !";
	}
	if (trim($_POST['txtDiagnosa']) == "") {
		$pesanError[] = "Data <b>Diagnosa</b> belum diisi, silahkan pilih pada Diagnosa !";
	}


	# BACA VARIABEL DARI FORM INPUT tindakan
	$txtNomorRM	= $_POST['txtNomorRM'];

	$cmbDokter	= $_POST['cmbDokter'];
	$cmbTindakan = $_POST['cmbTindakan'];
	$cmbKodeObat = $_POST['cmbKodeObat'];
	$txtJumlah	= $_POST['txtJumlah'];
	$txtTanggal 	= $_POST['txtTanggal'];
	$txtDiagnosa	= $_POST['txtDiagnosa'];
	$dataDokter		= $_POST['cmbDokter'];


	# JIKA ADA PESAN ERROR DARI VALIDASI
	if (count($pesanError) >= 1) {
		echo "<div class='mssgBox'>";
		echo "<img src='../images/attention.png'> <br><hr>";
		$noPesan = 0;
		foreach ($pesanError as $indeks => $pesan_tampil) {
			$noPesan++;
			echo "&nbsp;&nbsp; $noPesan. $pesan_tampil<br>";
		}
		echo "</div> <br>";
	} else {






		$kode_obat = $_POST['cmbKodeObat'];
		$tanggal = $_POST['txtTanggal'];
		$jumlah = $_POST['txtJumlah'];
		$type = 2;
		$nomorRawat = buatKode("rawat", "RP");
		$tanggal	= InggrisTgl($_POST['txtTanggal']);
		$userLogin	= $_SESSION['SES_LOGIN'];
		for ($i = 0; $i < count($cmbKodeObat); $i++) {
			$kode_stok = buatKode("stok_obat", "ST");
			$mysqlde = "
			INSERT INTO obat_rawat (no_rawat , no_stok ) 
				VALUES ('$nomorRawat',
				'$kode_stok')      
			";


			mysqli_query($koneksidb, $mysqlde);


			$mySqlstok	= "INSERT INTO stok_obat (no_stok, kd_obat, tanggal, jumlah, jenis) 
                VALUES ('$kode_stok',
                        '$kode_obat[$i]',
                        '$tanggal',
                        '$jumlah[$i]',
						'$type')";
			$myQrystok	= mysqli_query($koneksidb, $mySqlstok);
		}





		// Skrip menyimpan data ke tabel transaksi utama
		$mySql	= "INSERT INTO rawat
		(no_rawat,
		tgl_rawat,
		nomor_rm,
		hasil_diagnosa,
		kd_petugas
		)
		VALUES  (
						'$nomorRawat', 
						'$tanggal', 
						'$txtNomorRM', 
						'$txtDiagnosa', 
						
						'$userLogin')";

		mysqli_query($koneksidb, $mySql) or die("Gagal query" . mysqli_error($koneksidb));



		// Membaca data bagi hasil yang diberikan kepada Dokter
		//	$bacaSql ="SELECT bagi_hasil FROM dokter WHERE kd_dokter='$cmbDokter'";
		//	$bacaQry = mysqli_query($koneksidb, $bacaSql) or die ("Gagal Query".mysqli_error());
		//	$bacaData = mysqli_fetch_array($bacaQry);

		# SIMPAN DATA KE DATABASE (tmp_rawat)
		# Jika jumlah error pesanError tidak ada, skrip di bawah dijalankan
		foreach ($cmbTindakan as $tindak) {
			$itemSql = "INSERT INTO rawat_tindakan (
				tgl_tindakan,
				no_rawat,
				kd_tindakan,
				kd_dokter,
			
				bagi_hasil_dokter)
				VALUES (
							 '$tanggal', 
							 '$nomorRawat', 
							 '$tindak', 
							 '$dataDokter', 
						
							 10)";
			$dsads = mysqli_query($koneksidb, $itemSql) or die(mysqli_error($koneksidb));




			$alert = "Swal.fire({
	position: 'top-end',
  icon: 'success',
  title: 'data berhasil dimasukan',
  showConfirmButton: false,
  timer: 1000
  })
";
		}

		/*

		$tmpSql 	= "INSERT INTO tmp_rawat (kd_tindakan, kd_dokter, kd_obat, jumlah,  kd_petugas) 
					   VALUES ('$cmbTindakan', '$cmbDokter', '$cmbKodeObat', '$txtJumlah' ,  '".$_SESSION['SES_LOGIN']."')";
					  
		mysqli_query($koneksidb, $tmpSql ) or die ("Gagal Query tmp : ".mysqli_error());		*/
	}
}

# ========================================================================================================
# JIKA TOMBOL SIMPAN TRANSAKSI DIKLIK
if (isset($_POST['btnSimpan'])) {
	$pesanError = array();
	if (trim($_POST['txtNomorRM']) == "") {
		$pesanError[] = "Data <b>Nomor Rekam Medik (RM)</b> belum diisi, silahkan klik <b>daftar pasien</b> !";
	}
	if (trim($_POST['txtTanggal']) == "") {
		$pesanError[] = "Data <b>Tanggal Rawat</b> belum diisi, silahkan pilih pada kalender !";
	}
	if (trim($_POST['txtDiagnosa']) == "") {
		$pesanError[] = "Data <b>Diagnosa</b> belum diisi, silahkan pilih pada Diagnosa !";
	}


	# Validasi jika belum ada satupun data item yang dimasukkan
	$tmpSql = "SELECT COUNT(*) As qty FROM tmp_rawat WHERE kd_petugas='" . $_SESSION['SES_LOGIN'] . "'";
	$tmpQry = mysqli_query($koneksidb, $tmpSql) or die("Gagal Query Tmp" . mysqli_error($tmpQry));
	$tmpData = mysqli_fetch_array($tmpQry);
	if ($tmpData['qty'] < 1) {
		$pesanError[] = "<b>DAFTAR TINDAKAN MASIH KOSONG</b>, Daftar item tindakan belum ada yang dimasukan, <b>minimal 1 data</b>.";
	}

	# Baca variabel
	$txtTanggal 	= $_POST['txtTanggal'];
	$txtNomorRM		= $_POST['txtNomorRM'];
	$txtDiagnosa	= $_POST['txtDiagnosa'];
	$cmbKodeObat	= $_POST['cmbKodeObat'];
	$txtJumlah		= $_POST['txtJumlah'];
	echo $txtDiagnosa;

	# JIKA ADA PESAN ERROR DARI VALIDASI
	if (count($pesanError) >= 1) {
		echo "<div class='mssgBox'>";
		echo "<img src='../images/attention.png'> <br><hr>";
		$noPesan = 0;
		foreach ($pesanError as $indeks => $pesan_tampil) {
			$noPesan++;
			echo "&nbsp;&nbsp; $noPesan. $pesan_tampil<br>";
		}
		echo "</div> <br>";
	} else {
		# SIMPAN KE DATABASE
		# Jika jumlah error pesanError tidak ada, maka proses Penyimpanan akan dikalkukan

		// Membuat kode Transaksi baru
		$nomorRawat = buatKode("rawat", "RP");

		$tanggal	= InggrisTgl($_POST['txtTanggal']);
		$userLogin	= $_SESSION['SES_LOGIN'];

		// Skrip menyimpan data ke tabel transaksi utama
		$mySql	= "INSERT INTO rawat
		(no_rawat,
		tgl_rawat,
		nomor_rm,
		hasil_diagnosa,
		kd_petugas
		)
		VALUES  (
						'$nomorRawat', 
						'$tanggal', 
						'$txtNomorRM', 
						'$txtDiagnosa', 
						
						'$userLogin')";

		mysqli_query($koneksidb, $mySql) or die("Gagal query" . mysqli_error($koneksidb));

		# Ambil semua data tindakan/tindakan yang dipilih, berdasarkan user yg login
		$tmpSql = "SELECT * FROM tmp_rawat WHERE kd_petugas='$userLogin'";
		$tmpQry = mysqli_query($koneksidb, $tmpSql) or die("Gagal Query Tmp" . mysqli_error($koneksidb));
		while ($tmpData = mysqli_fetch_array($tmpQry)) {
			// Membaca data dari tabel TMP
			$kodeTindakan	= $tmpData['kd_tindakan'];
			$kodeDokter		= $tmpData['kd_dokter'];

			$KodeObat	= $tmpData['kd_obat'];
			$Jumlah		= $tmpData['jumlah'];
			$bagiHasilDokter = $tmpData['bagi_hasil_dokter'];





			// Masukkan semua tindakan dari TMP ke tabel rawat detail
			$itemSql = "INSERT INTO rawat_tindakan (
				tgl_tindakan,
				no_rawat,
				kd_tindakan,
				kd_dokter,
				kd_obat,
				jumlah,
				bagi_hasil_dokter)
				VALUES (
							 '$tanggal', 
							 '$nomorRawat', 
							 '$kodeTindakan', 
							 '$kodeDokter', 

							'$KodeObat',
							'$Jumlah',
							 '$bagiHasilDokter')";
			$dsads = mysqli_query($koneksidb, $itemSql) or die(mysqli_error($koneksidb));

			var_dump($tmpSql);
			// Skrip Update stok
			$stokSql = "UPDATE obat SET stok = stok - $Jumlah WHERE kd_obat='$KodeObat'";

			mysqli_query($koneksidb, $stokSql);
		}

		# Kosongkan Tmp jika datanya sudah dipindah
		$hapusSql = "DELETE FROM tmp_rawat WHERE kd_petugas='$userLogin'";
		mysqli_query($koneksidb, $hapusSql) or die("Gagal kosongkan tmp" . mysqli_error(mysqli_query($koneksidb, $hapusSql)));

		// Jalankan skrip Nota
		echo "<script>";
		echo "window.open('rawat_nota.php?nomorRawat=$nomorRawat', width=330,height=330,left=100, top=25)";
		echo "</script>";

		// Refresh form
		echo "<meta http-equiv='refresh' content='0; url=index.php'>";
	}
}

// Membaca Nomor RM data Pasien
$NomorRM = isset($_GET['NomorRM']) ?  $_GET['NomorRM'] : null;
$obat = isset($_GET['obat']) ?  $_GET['obat'] : null;
$diagnosa = isset($_COOKIE['diagnosa']) ?  $_COOKIE['diagnosa'] : null;

if ($NomorRM == null && $obat == null) {
	setcookie("dokter", "", time() - 3600);
	setcookie("tindak", "", time() - 3600);
	$cdokter = null;
	$tindak = null;
	setcookie("diagnosa", "", time() - 3600);
	$diagnosa = null;
}
if ($obat != null) {
	$bacaSql = "SELECT * FROM `obat` WHERE kd_obat = '$obat'";
	$bacaQry = mysqli_query($koneksidb, $bacaSql);
	$ddt = mysqli_fetch_array($bacaQry);

	$namaObat = $ddt[3];
} else {
	$namaObat = "";
}


$mySql	= "SELECT nomor_rm, nm_pasien, no_identitas, kode_lembaga FROM pasien WHERE nomor_rm='$NomorRM'";
$myQry	= mysqli_query($koneksidb, $mySql)  or die("Query salah : " . mysqli_error($myQry));
$myData = mysqli_fetch_array($myQry);
$dataPasien		= $myData['nm_pasien'];
$noInduk		= $myData['no_identitas'];
$kodeLembaga	= $myData['kode_lembaga'];
# Kode pasien
if ($NomorRM == "") {
	$NomorRM = isset($_POST['txtNomorRM']) ? $_POST['txtNomorRM'] : '';
}

# MEMBACA DATA DARI FORM UTAMA TRANSAKSI, Nilai datanya dimasukkan kembali ke Form utama DATA TRANSAKSI
$noTransaksi 	= buatKode("rawat", "RP");
$dataTanggal 	= isset($_POST['txtTanggal']) ? $_POST['txtTanggal'] : date('d-m-Y');
$dataDiagnosa	= isset($_POST['txtDiagnosa']) ? $_POST['txtDiagnosa'] : '';
$dataDokter		= isset($_POST['cmbDokter']) ? $_POST['cmbDokter'] : '';
$dataTindakan	= isset($_POST['cmbTindakan']) ? $_POST['cmbTindakan'] : '';
$dataKodeObat	= isset($_POST['cmbKodeObat']) ? $_POST['cmbKodeObat'] : '';
$dataJumlah		= isset($_POST['txtJumlah']) ? $_POST['txtJumlah'] : '';

?>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self">
	<table width="800" cellspacing="1" class="table-list">
		<tr>
			<td colspan="3">
				<h1> RAWAT PASIEN </h1>
			</td>
		</tr>
		<tr>
			<td bgcolor="#CCCCCC"><strong>DATA RAWAT </strong></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td width="26%"><strong>No. Rawat </strong></td>
			<td width="1%"><strong>:</strong></td>
			<td width="73%"><input name="txtNomor" value="<?php echo $noTransaksi; ?>" size="23" maxlength="20" readonly="readonly" /></td>
		</tr>
		<tr>
			<td><strong>Tgl. Rawat </strong></td>
			<td><strong>:</strong></td>
			<td><input name="txtTanggal" type="text" class="tcal" value="<?php echo $dataTanggal; ?>" size="23" /></td>
		</tr>
		<tr>
			<td><strong>Nomor RM </strong></td>
			<td><strong>:</strong></td>
			<td><input name="txtNomorRM" id="norm" value="" autocomplete="off" size="23" maxlength="20" />
				* pilih dari <button type="button" id="add-stok" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal" kode-obat="<?= $kode_obat ?>">PILIH </button>, lalu klik menu <strong>Rawat</strong> </td>
		</tr>
		<tr>
			<td><strong>Nama Pasien </strong></td>
			<td><strong>:</strong></td>
			<td><input name="txtPasien" id="txtPasien" value="<?php echo $dataPasien; ?>" size="80" maxlength="100" readonly="readonly" /></td>
		</tr>
		<tr>
			<td><strong>Umur </strong></td>
			<td><strong>:</strong></td>
			<td><input name="umrPasien" id="umrPasien" size="80" maxlength="100" readonly="readonly" /></td>
		</tr>
		<tr>
			<td><strong>No Induk </strong></td>
			<td><strong>:</strong></td>
			<td><input name="txtInduk" id='txtInduk' value="<?php echo $noInduk; ?>" size="80" maxlength="100" readonly="readonly" /></td>
		</tr>
		<tr>
			<td><strong>Lembaga </strong></td>
			<td><strong>:</strong></td>
			<td><input name="txtLembaga" id="txtLembaga" value="<?php echo $kodeLembaga; ?>" size="80" maxlength="100" readonly="readonly" /></td>
		</tr>

		<tr>
			<td><strong>Hasil Anamnesa </strong></td>
			<td><strong>:</strong></td>
			<td>


				<textarea name="txtDiagnosa" rows="4" cols="50"></textarea>


			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>

	
		<tr>
			<td bgcolor="#CCCCCC"><strong>DIAGNOSA </strong></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><strong>Dokter </strong></td>
			<td><strong>:</strong></td>
			<td><select id="dokter" name="cmbDokter">
					<option value="KOSONG">....</option>
					<?php
					$bacaSql = "SELECT * FROM dokter ORDER BY kd_dokter";
					$bacaQry = mysqli_query($koneksidb, $bacaSql) or die("Gagal Query" . mysqli_error($bacaQry));
					while ($bacaData = mysqli_fetch_array($bacaQry)) {
						if ($bacaData['kd_dokter'] == $cdokter) {
							$cek = " selected";
						} else {
							$cek = "";
						}

						echo "<option id='diagnosa' value='$bacaData[kd_dokter]' $cek>[ $bacaData[kd_dokter] ]  $bacaData[nm_dokter]</option>";
					}
					?>
				</select> </td>
		</tr>

		<tr class="copi">
			<td><strong width="40">diagnosa Pasien </strong></td>
			<td><strong>:</strong></td>
			<td width="40"><select id="tindak" style="width: 500px" name="cmbTindakan[]">6
					<option value="KOSONG">....</option>
					<?php
					$bacaSql = "SELECT * FROM tindakan ORDER BY nm_tindakan";
					$bacaQry = mysqli_query($koneksidb, $bacaSql) or die("Gagal Query" . mysqli_error($bacaQry));
					while ($bacaData = mysqli_fetch_array($bacaQry)) {
						if ($bacaData['kd_tindakan'] == $tindak) {
							$cek = " selected";
						} else {
							$cek = "";
						}


						echo "<option value='$bacaData[kd_tindakan]' $cek> $bacaData[nm_tindakan]  [ $bacaData[kd_tindakan] ]</option>";
					}
					?>
				</select>
				&nbsp;&nbsp;
				<button class="btn btn-success add-more btn-xs" type="button"></i> Add</button>
			</td>



		</tr>

		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>



	<table width="800" cellspacing="1" class="table-list">
		<thead>
			<tr>
				<td bgcolor="#CCCCCC" tyle="width: 100px"><strong>INPUT OBAT </strong></td>
				<td tyle="width: 100px">
					<button type="button" id="add-obat" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal" kode-obat="<?= $kode_obat ?>">PILIH </button>
				</td>
				<td tyle="width: 100px">&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Kode Obat</td>
				<td>Nama Obat</td>
				<td>Stok</td>
				<td>Jumlah</td>
				<td>Action</td>
			</tr>
		</thead>
		<tbody id="isi-obat">

		</tbody>
		
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>		
	</table>
	
	<tr>	
			<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
			<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
			<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
			<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
			<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
			<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
			
		</tr>
	<td tyle="width: 100px"><input name="btnTambah" type="submit" class="btn btn-success" value=" Simpan " /></td>
	<br>
	<!--
  <table class="table-list" width="800" border="0" cellspacing="1" cellpadding="2">
    <tr>
      <th colspan="9"><strong>DAFTAR TINDAKAN </strong></th>
    </tr>
    <tr>
      <td width="27" bgcolor="#CCCCCC"><strong>No</strong></td>
      <td width="58" bgcolor="#CCCCCC"><strong>Kode </strong></td>
      <td width="400" bgcolor="#CCCCCC"><strong>Nama Diagnosa </strong></td>
      <td width="200" bgcolor="#CCCCCC"><strong>Dokter</strong></td>
	  <td width="200" bgcolor="#CCCCCC"><strong>Obat</strong></td>
	  <td align="center" width="190" bgcolor="#CCCCCC"><strong>Jumlah</strong></td>
      <td width="34">&nbsp;</td>
    </tr>
	<?php
	/*
	

	// Query SQL menampilkan data Tindakan dalam TMP_RAWAT
	$tmpSql ="SELECT tmp_rawat.*, tindakan.nm_tindakan, dokter.nm_dokter, obat.nm_obat FROM tmp_rawat
			  LEFT JOIN tindakan ON tmp_rawat.kd_tindakan=tindakan.kd_tindakan 
			  LEFT JOIN dokter ON tmp_rawat.kd_dokter=dokter.kd_dokter
			  LEFT JOIN obat ON tmp_rawat.kd_obat=obat.kd_obat
			 
			  WHERE tmp_rawat.kd_petugas='".$_SESSION['SES_LOGIN']."' ORDER BY id";
	$tmpQry = mysqli_query($koneksidb, $tmpSql) or die ("Gagal Query Tmp".mysqli_error());
	$nomor=0; 
	while($tmpData = mysqli_fetch_array($tmpQry)) {
	$nomor++;
	
	
	?>

	  <tr>
		<td><?php echo $nomor; ?></td>
		<td><?php echo $tmpData['kd_tindakan']; ?></td>
		<td><?php echo $tmpData['nm_tindakan']; ?>
		</td>
		<td><?php echo $tmpData['nm_dokter']; ?></td>
		<td><?php echo $tmpData['nm_obat']; ?></td>
		<td align="center"><?php echo $tmpData['jumlah']; ?></td>
	
		
		<td><a href="?Aksi=Hapus&id=<?php echo $tmpData['id']; ?>" target="_self">Delete</a></td>
	  </tr>
		<?php  } */ ?>	

    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    
     
    </tr>
    <tr>
      <td colspan="6" align="center"><input name="btnSimpan" type="submit" style="cursor:pointer;" value=" SIMPAN TRANSAKSI " /></td>
    
    </tr>
    
  </table>
</form>
*/
-->




	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-center">Modal Header</h4>
				</div>
				<div class="modal-body">


					<table class="table table-hover" id="tabelp">
						<thead>
							<tr id="tabel-judul">

							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>









				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>







	<script src="../js/jquery.cookie.js"></script>
	<script>
		$(document).ready(function() {


			<?= $alert  ?>




			$(document).on("click", ".pilih", function() {
				var data = $(this).attr('id')
				var url = "../api/rawat.php"
				var tipe = 'rawat'

				pilih(url, data, tipe);

			});

			$(document).on("click", ".piliho", function(event) {

				var data = $(this).attr('id')
				var url = "../api/obat.php"
				var tipe = 'obat'

				pilih(url, data, tipe);

			});


			$('#add-stok').click(function() {
				var tabelj = "<th>NO</th><th>NO IDENTITAS</th><th>NAMA</th><th>JENIS KELAMIN</th><th>ALAMAT</th><th></th>"
				var url = "../api/rawat.php"
				$('.modal-title').text('Pilih Pasien');
				$('#tabel-judul').append(tabelj);
				setTimeout(function() {
					tabel(url);
				}, 500);

			})

			$('#add-obat').click(function() {
				var tabelj = "<th>NO</th><th>Kode Obat</th><th>NAMA Obat</th><th>Stok Minimal</th><th>Stok</th><th></th>"
				var url = "../api/obat.php"
				$('.modal-title').text('Pilih Obat');
				$('#tabel-judul').append(tabelj);
				setTimeout(function() {
					tabel(url);
				}, 500);

			})






			$('#myModal').on('hidden.bs.modal', function() {
				$('#tabelp').DataTable().clear().destroy();
				$('#tabel-judul').empty();
			})






			$("#dokter").change(function() {
				var dokter = $("#dokter option:selected").val();
				$.cookie("dokter", dokter);


			});
			$("#tindak").change(function() {
				var tindak = $("#tindak option:selected").val();
				$.cookie("tindak", tindak);

			});

			$("#diagnosa").keyup(function() {
				var diagnosa = $("#diagnosa").val();
				$.cookie("diagnosa", diagnosa);

			});

			var click = 1;


			$(".add-more").click(function() {
				var asd = $('#copi').clone();
				var input = `<?php
								$bacaSql = "SELECT * FROM tindakan ORDER BY nm_tindakan";
								$bacaQry = mysqli_query($koneksidb, $bacaSql) or die("Gagal Query" . mysqli_error($bacaQry));
								while ($bacaData = mysqli_fetch_array($bacaQry)) {
									if ($bacaData['kd_tindakan'] == $tindak) {
										$cek = " selected";
									} else {
										$cek = "";
									}


									echo "<option value='$bacaData[kd_tindakan]' $cek> $bacaData[nm_tindakan]  [ $bacaData[kd_tindakan] ]</option>";
								}
								?>`
				var html = `<tr class="control-group">
      <td><strong>diagnosa Pasien </strong></td>
      <td><strong>:</strong></td>
      <td><select id="tindak" style="width: 500px" name="cmbTindakan[]">6
	  <option value="KOSONG">....</option>
      ` +
					input +

					`
		   </select>
		   &nbsp;&nbsp;
		   <button class="btn btn-danger remove btn-xs" type="button"></i> remove</button>
		</td>
	  
		<button class="btn btn-danger remove btn-xs" type="button"></i> remove</button>
	  
	 </tr>
		   `



				if (click <= 4) {
					$(".copi").after(html);
					click = click + 1;
				}

			});
			$("body").on("click", ".remove", function() {
				$(this).parents(".control-group").remove();
				click = click - 1;

			});





			function tabel(url) {

				$('#tabelp').DataTable({
					"processing": true,
					"serverSide": true,
					"ajax": {
						url: url,
						type: "POST",
						datatype: "json",


					}
				});

			}


			function pilih(url, data, tipe) {

				$.ajax({
					url: url,
					dataType: 'json',
					method: "POST",
					data: {
						tipe: "getd",
						norm: data
					},
					success: function(data) {
						if (tipe == "rawat") {
							pilihpasien(data)
						}
						if (tipe == "obat") {
							pilihobat(data)
						}




					}
				});

			}

			function pilihpasien(data) {
				$('#myModal').modal('hide');
				$('#norm').val(data.nomor_rm)
				$('#txtPasien').val(data.nm_pasien)
				$('#txtInduk').val(data.no_identitas)
				$('#txtLembaga').val(data.kode_lembaga)
				$('#umrPasien').val(data.usia)
			}
			var no = 1;

			function pilihobat(data) {

				var obat = `<tr kode="` + no + `">
				<td><input name="cmbKodeObat[]" id=""  readonly="readonly" value="` + data.kode + `" /></td>
				<td><input name="" id=""  readonly="readonly" value="` + data.nm_obat + `"/></td>
				<td><input name="umrPasien" id=""  readonly="readonly" value="` + data.jmlstok + `"/></td>
				<td><input name="txtJumlah[]" autocomplete="off" type="number"/></td>
				<td>	<button value="` + no + `" type="button" id="add-obat" class="btn btn-danger btn-xs obat-remove">remove </button></td>
			</tr>`;
				$('#isi-obat').append(obat);
				$('#myModal').modal('hide');

				no += 1
			}


			$(document).on("click", ".obat-remove", function() {
				var id = $(this).val();
				$('tr[kode="' + id + '"]').remove();

			});



		});
	</script>