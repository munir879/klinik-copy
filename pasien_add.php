<?php
include_once "library/inc.seslogin.php";

# Tombol Simpan diklik
if (isset($_POST['btnSimpan'])) {
  # Validasi form, jika kosong sampaikan pesan error
  $pesanError = array();
  if (trim($_POST['txtNama']) == "") {
    $pesanError[] = "Data <b>Nama Pasien</b> tidak boleh kosong !";
  }
  if (trim($_POST['txtNoIdentitas']) == "") {
    $pesanError[] = "Data <b>No. Identitas</b> tidak boleh kosong !";
  }
  if (trim($_POST['cmbKelamin']) == "KOSONG") {
    $pesanError[] = "Data <b>Jenia Kelamin</b> belum dipilih !";
  }
  if (trim($_POST['cmbGDarah']) == "KOSONG") {
    $pesanError[] = "Data <b>Golongan Darah</b> belum dipilih !";
  }
  if (trim($_POST['cmbAgama']) == "KOSONG") {
    $pesanError[] = "Data <b>Agama</b> belum dipilih !";
  }
  if (trim($_POST['txtTempatLahir']) == "") {
    $pesanError[] = "Data <b>Tempat Lahir</b> tidak boleh kosong !";
  }
  if (trim($_POST['txtAlamat']) == "") {
    $pesanError[] = "Data <b>Alamat Tinggal</b> tidak boleh kosong !";
  }
  if (trim($_POST['txtTelepon']) == "") {
    $pesanError[] = "Data <b>No. Telepon</b> tidak boleh kosong !";
  }
  if (trim($_POST['cmbSttsNikah']) == "KOSONG") {
    $pesanError[] = "Data <b>Status Nikah</b> belum dipilih !";
  }
  if (trim($_POST['cmbLembaga']) == "KOSONG") {
    $pesanError[] = "Data <b>Lembaga</b> belum dipilih !";
  }
  if (trim($_POST['cmbSttsKeluarga']) == "KOSONG") {
    $pesanError[] = "Data <b>Status Keluarga</b> tidak boleh kosong !";
  }
  if (trim($_POST['txtKlgNama']) == "") {
    $pesanError[] = "Data <b>Nama Keluarga</b> tidak boleh kosong !";
  }
  if (trim($_POST['txtKlgTelepon']) == "") {
    $pesanError[] = "Data <b>No. Telepon Keluarga</b> tidak boleh kosong !";
  }
  if (trim($_POST['cmbThnAjaran']) == "") {
    $pesanError[] = "Data <b>Isi Tahun Masuk</b> tidak boleh kosong !";
  }

  # Baca Variabel Form
  $txtNama    = $_POST['txtNama'];
  $txtNoIdentitas  = $_POST['txtNoIdentitas'];
  $cmbKelamin    = $_POST['cmbKelamin'];
  $cmbGDarah    = $_POST['cmbGDarah'];
  $cmbAgama    = $_POST['cmbAgama'];
  $txtAlamat    = $_POST['txtAlamat'];
  $txtTelepon    = $_POST['txtTelepon'];
  $cmbSttsNikah  = $_POST['cmbSttsNikah'];
  $cmbLembaga  = $_POST['cmbLembaga'];
  $cmbSttsKeluarga = $_POST['cmbSttsKeluarga'];
  $txtKlgNama    = $_POST['txtKlgNama'];
  $txtKlgTelepon  = $_POST['txtKlgTelepon'];
  $txtTempatLahir  = $_POST['txtTempatLahir'];
  //$cmbThnAjaran	= $_POST['cmbThnAjaran'];

  // Membaca form tanggal lahir (comboBox : tanggal, bulan dan tahun lahir)
  $cmbTglLahir  = $_POST['cmbTglLahir'];
  $cmbBlnLahir  = $_POST['cmbBlnLahir'];
  $cmbThnLahir  = $_POST['cmbThnLahir'];
  $tanggalLahir  = "$cmbThnLahir-$cmbBlnLahir-$cmbTglLahir";
  $cmbThnAjaran  = $_POST['cmbThnAjaran'];
  $cmbTahunAjaran  = "$cmbThnAjaran";


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
    $tanggal  = date('Y-m-d');
    $petugas  = $_SESSION['SES_LOGIN'];
    $kodeBaru  = buatKode("pasien", "RM");
    $mySql  = "INSERT INTO pasien (nomor_rm, nm_pasien, no_identitas, jns_kelamin, 
						gol_darah, agama, tempat_lahir, tanggal_lahir, tahun_ajaran,
						no_telepon, alamat, stts_nikah, kode_lembaga, 
						keluarga_status, keluarga_nama, keluarga_telepon, tgl_rekam, 
						kd_petugas) 
					VALUES ('$kodeBaru', '$txtNama', '$txtNoIdentitas', '$cmbKelamin', 
							'$cmbGDarah', '$cmbAgama', '$txtTempatLahir', '$tanggalLahir', '$cmbTahunAjaran',
							'$txtTelepon', '$txtAlamat', '$cmbSttsNikah', '$cmbLembaga', 
							'$cmbSttsKeluarga', '$txtKlgNama', '$txtKlgTelepon', '$tanggal', '$petugas')";

    $myQry  = mysqli_query($koneksidb, $mySql) or die("Gagal query" . mysqli_error($myQry));
    if ($myQry) {
      echo "<meta http-equiv='refresh' content='0; url=?page=Pasien-Add'>";
    }
    exit;
  }
} // Penutup Tombol Simpan

# VARIABEL DATA UNTUK DIBACA FORM
$dataKode  = buatKode("pasien", "RM");
$dataNama  = isset($_POST['txtNama']) ? $_POST['txtNama'] : '';
$dataNoIdentitas = isset($_POST['txtNoIdentitas']) ? $_POST['txtNoIdentitas'] : '';
$dataKelamin = isset($_POST['cmbKelamin']) ? $_POST['cmbKelamin'] : '';
$dataGDarah  = isset($_POST['cmbGDarah']) ? $_POST['cmbGDarah'] : '';
$dataAgama  = isset($_POST['cmbAgama']) ? $_POST['cmbAgama'] : '';
$dataAlamat = isset($_POST['txtAlamat']) ? $_POST['txtAlamat'] : '';
$dataTelepon = isset($_POST['txtTelepon']) ? $_POST['txtTelepon'] : '';
$dataSttsNikah  = isset($_POST['cmbSttsNikah']) ? $_POST['cmbSttsNikah'] : '';
$dataLembaga  = isset($_POST['cmbLembaga']) ? $_POST['cmbLembaga'] : '';
$dataSttsKeluarga = isset($_POST['cmbSttsKeluarga']) ? $_POST['cmbSttsKeluarga'] : '';
$dataKlgNama  = isset($_POST['txtKlgNama']) ? $_POST['txtKlgNama'] : '';
$dataKlgTelepon  = isset($_POST['txtKlgTelepon']) ? $_POST['txtKlgTelepon'] : '';
$dataThnAjaran = isset($_POST['cmbThnAjaran']) ? $_POST['cmbThnAjaran'] : date('Y');
// Tempat, Tgl Lahir
$dataTempatLahir = isset($_POST['txtTempatLahir']) ? $_POST['txtTempatLahir'] : '';
$dataThn    = isset($_POST['cmbThnLahir']) ? $_POST['cmbThnLahir'] : date('Y');
$dataBln    = isset($_POST['cmbBlnLahir']) ? $_POST['cmbBlnLahir'] : date('m');
$dataTgl    = isset($_POST['cmbTglLahir']) ? $_POST['cmbTglLahir'] : date('d');
$dataTglLahir   = $dataThn . "-" . $dataBln . "-" . $dataTgl;
//$dataThnAjaran  = $dataThn."-".$dataBln;
$dataTahunAjaran  = $dataThnAjaran;
?>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" target="_self">
  <table class="table-list" width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <th colspan="3"><strong>TAMBAH DATA SANTRI </strong></th>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <br>
    <tr>
      <td width="15%"><strong>Nama Santri </strong></td>
      <td width="1%"><strong>:</strong></td>
      <td width="84%"><input name="txtNama" value="<?php echo $dataNama; ?>" size="80" maxlength="100" /></td>
      <td><input name="textfield" type="hidden" value="<?php echo $dataKode; ?>" size="10" maxlength="10" readonly="readonly" /></td>
    </tr>
    <tr>
      <td><strong>No. Identitas </strong></td>
      <td><strong>:</strong></td>
      <td><input name="txtNoIdentitas" value="<?php echo $dataNoIdentitas; ?>" size="40" maxlength="40" /></td>
    </tr>

    <tr>
      <td><b>Lembaga</b></td>
      <td><b>:</b></td>
      <td><b>
          <select name="cmbLembaga">
            <option value="KOSONG">....</option>
            <?php
            $pilihan  = array(
              "SD",
              "MTs",
              "MA",
              "SMK",
              "Santri",
              "Kuliah"
            );
            foreach ($pilihan as $nilai) {
              if ($dataLembaga == $nilai) {
                $cek = " selected";
              } else {
                $cek = "";
              }
              echo "<option value='$nilai' $cek>$nilai</option>";
            }
            ?>
          </select>
        </b></td>
    </tr>
    <tr>
      <td><strong>Tahun Ajaran </strong></td>
      <td><strong>:</strong></td>
      <td>

        <form action="action">
          <select name='cmbThnAjaran'>
            <option value=""> <strong> Cari ... </strong> </option>
            <?php
            $thn_skr = date('Y');
            for ($dataTahunAjaran = $thn_skr; $dataTahunAjaran >= 2010; $dataTahunAjaran--) {
            ?>
              <option value="<?php echo $dataTahunAjaran ?>"><?php echo $dataTahunAjaran ?></option>

            <?php
            }
            ?>
          </select>

        </form>
      </td>


    </tr>
    <tr>
      <td><b>Jenis Kelamin </b></td>
      <td><b>:</b></td>
      <td><b>
          <select name="cmbKelamin">
            <option value="KOSONG">....</option>
            <?php
            $pilihan  = array("Laki-laki", "Perempuan");
            foreach ($pilihan as $nilai) {
              if ($dataKelamin == $nilai) {
                $cek = " selected";
              } else {
                $cek = "";
              }
              echo "<option value='$nilai' $cek>$nilai</option>";
            }
            ?>
          </select>
        </b></td>
    </tr>
    <tr>
      <td><b>Gol. Darah </b></td>
      <td><b>:</b></td>
      <td><b>
          <select name="cmbGDarah">
            <option value="KOSONG">....</option>
            <?php
            $pilihan  = array("A", "B", "AB", "O");
            foreach ($pilihan as $nilai) {
              if ($dataGDarah == $nilai) {
                $cek = " selected";
              } else {
                $cek = "";
              }
              echo "<option value='$nilai' $cek>$nilai</option>";
            }
            ?>
          </select>
        </b></td>
    </tr>
    <tr>
      <td><b>Agama</b></td>
      <td><b>:</b></td>
      <td><b>
          <select name="cmbAgama">
            <option value="KOSONG">....</option>
            <?php
            $pilihan  = array("Islam", "Kristen", "Katolik", "Buda", "Hindu");
            foreach ($pilihan as $nilai) {
              if ($dataAgama == $nilai) {
                $cek = " selected";
              } else {
                $cek = "";
              }
              echo "<option value='$nilai' $cek>$nilai</option>";
            }
            ?>
          </select>
        </b></td>
    </tr>
    <tr>
      <td><strong>Tempat, Tgl. Lahir </strong></td>
      <td><strong>:</strong></td>
      <td><input name="txtTempatLahir" type="text" value="<?php echo $dataTempatLahir; ?>" size="20" maxlength="100" />
        , <?php echo listTanggal("Lahir", $dataTglLahir); ?></td>
    </tr>
    <tr>
      <td><strong>Alamat Tinggal </strong></td>
      <td><strong>:</strong></td>
      <td><input name="txtAlamat" value="<?php echo $dataAlamat; ?>" size="80" maxlength="200" /></td>
    </tr>
    <tr>
      <td><strong>No. Telepon </strong></td>
      <td><strong>:</strong></td>
      <td><input name="txtTelepon" value="<?php echo $dataTelepon; ?>" size="20" maxlength="20" /></td>
    </tr>
    <tr>
      <td><b>Status Nikah </b></td>
      <td><b>:</b></td>
      <td><b>
          <select name="cmbSttsNikah">
            <option value="KOSONG">....</option>
            <?php
            $pilihan  = array("Menikah", "Belum Nikah");
            foreach ($pilihan as $nilai) {
              if ($dataSttsNikah == $nilai) {
                $cek = " selected";
              } else {
                $cek = "";
              }
              echo "<option value='$nilai' $cek>$nilai</option>";
            }
            ?>
          </select>
        </b></td>
    </tr>

    <tr>
      <td bgcolor="#CCCCCC"><strong> KELUARGA</strong> </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><b>Status Keluarga </b></td>
      <td><b>:</b></td>
      <td><b>
          <select name="cmbSttsKeluarga">
            <option value="KOSONG">....</option>
            <?php
            $pilihan  = array("Ayah", "Ibu", "Suami", "Istri", "Saudara");
            foreach ($pilihan as $nilai) {
              if ($dataSttsKeluarga == $nilai) {
                $cek = " selected";
              } else {
                $cek = "";
              }
              echo "<option value='$nilai' $cek>$nilai</option>";
            }
            ?>
          </select>
        </b></td>
    </tr>
    <tr>
      <td><strong>Nama Keluarga </strong></td>
      <td><strong>:</strong></td>
      <td><input name="txtKlgNama" value="<?php echo $dataKlgNama; ?>" size="80" maxlength="200" /></td>
    </tr>
    <tr>
      <td><strong>No. Telepon </strong></td>
      <td><strong>:</strong></td>
      <td><input name="txtKlgTelepon" value="<?php echo $dataKlgTelepon; ?>" size="20" maxlength="20" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" name="btnSimpan" value=" SIMPAN "></td>
    </tr>
  </table>
</form>