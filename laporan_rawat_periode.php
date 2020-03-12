<?php

error_reporting(0);
$judul_halaman = 'EXPORT XLS';

#includekan fungsi paginasi
include 'pagination1.php';

#koneksi ke database
#$con = mysqli_connect("server","user","password","db");
$koneksidb = mysqli_connect("localhost", "root", "", "klinik-apotekdb");

#mengatur variabel reload dan sql
if (isset($_REQUEST['keyword']) && $_REQUEST['keyword'] <> "") {
  #jika ada kata kunci pencarian (artinya form pencarian disubmit dan tidak kosong) pakai ini
  $keyword = $_REQUEST['keyword'];
  $reload = "?pagination=true&keyword=$keyword";
  $sql =  "SELECT * FROM provinsi WHERE provinsi LIKE '%$keyword%' ORDER BY provinsi";
  $result = mysqli_query($con, $sql);
} else {
  #jika tidak ada pencarian pakai ini
  $reload = "?pagination=true";
  $sql =  "SELECT * FROM provinsi ORDER BY provinsi";
  $result = mysqli_query($con, $sql);
}

#pagination config start
$rpp = 10; // jumlah record per halaman
$page = intval($_GET["page"]);
if ($page <= 0) $page = 1;
$tcount = mysqli_num_rows($result);
$tpages = ($tcount) ? ceil($tcount / $rpp) : 1; // total pages, last page number
$count = 0;
$i = ($page - 1) * $rpp;
$no_urut = ($page - 1) * $rpp;
//pagination config end


include_once "library/inc.seslogin.php";

# Deklarasi variabel
$filterPeriode = "";
$tglAwal  = "";
$tglAkhir  = "";
$santri = "";
$idsantri = "";

# Membaca tanggal dari form, jika belum di-POST formnya, maka diisi dengan tanggal sekarang
$tglAwal   = isset($_POST['txtTglAwal']) ? $_POST['txtTglAwal'] : date('Y-m') . "-01";
$tglAkhir   = isset($_POST['txtTglAkhir']) ? $_POST['txtTglAkhir'] : date('Y-m-d');
$lembaga   = isset($_POST['cmbLembaga']) ? $_POST['cmbLembaga'] : '';
$santri   = isset($_POST['santri']) ? $_POST['santri'] : '';
$idsantri   = isset($_POST['idsantri']) ? $_POST['idsantri'] : '';




// Jika tombol filter tanggal (Tampilkan) diklik
if (isset($_POST['btnTampil'])) {
  // Membuat sub SQL filter data berdasarkan 2 tanggal (periode)
  $filterPeriode = "WHERE tgl_rawat BETWEEN '$tglAwal'AND '$tglAkhir'";
  if ($lembaga == null) {
    $filterlbg = "WHERE (tgl_rawat BETWEEN '$tglAwal'AND '$tglAkhir') ";
  } else {
    $filterlbg = "WHERE (tgl_rawat BETWEEN '$tglAwal'AND '$tglAkhir') AND jns_kelamin = '$lembaga'";
  }
} else {
  // Membaca data tanggal dari URL, saat menu Pages diklik
  $tglAwal   = isset($_GET['tglAwal']) ? $_GET['tglAwal'] : $tglAwal;
  $tglAkhir   = isset($_GET['tglAkhir']) ? $_GET['tglAkhir'] : $tglAkhir;





  // Membuat sub SQL filter data berdasarkan 2 tanggal (periode)
  $filterPeriode = "WHERE tgl_rawat BETWEEN '$tglAwal'AND '$tglAkhir'";
  $filterlbg = "WHERE (tgl_rawat BETWEEN '$tglAwal'AND '$tglAkhir') ";
}

if ($idsantri !=  "") {
  $filterlbg = "WHERE (tgl_rawat BETWEEN '$tglAwal'AND '$tglAkhir') AND rawat.nomor_rm = '$idsantri' ";
}



# UNTUK PAGING (PEMBAGIAN HALAMAN)
$row = 50;
$hal = isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql = "SELECT rawat.*, pasien.nm_pasien, pasien.kode_lembaga FROM rawat 
LEFT JOIN pasien ON rawat.nomor_rm = pasien.nomor_rm and pasien.kode_lembaga=pasien.kode_lembaga
$filterlbg";
$pageQry = mysqli_query($koneksidb, $pageSql);

$jml   = mysqli_num_rows($pageQry);


$max   = ceil($jml / $row);
?>

<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header">Laporan Rawat Pasien</h1>
  </div>
  <!-- /.col-lg-12 -->
</div>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self">
  <div class="dataTable-wrapper">
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="dataTables-example" width="100%" cellspacing="1" cellpadding="3">
        <tr>
          <td colspan="3" bgcolor="#CCCCCC"><strong>PERIODE RAWAT </strong></td>
        </tr>
        <tr>
          <td width="90"><strong>Periode </strong></td>
          <td width="5"><strong>:</strong></td>
          <td width="391"><input name="txtTglAwal" type="text" class="tcal" id="awal" value="<?php echo $tglAwal; ?>" />
            s/d
            <input name="txtTglAkhir" type="text" class="tcal" id="ahir" value="<?php echo $tglAkhir; ?>" /></td>
        </tr>
        <tr>
          <td width="90"><strong>Jenis Kelamin </strong></td>
          <td width="5"><strong>:</strong></td>
          <td>
            <select name="cmbLembaga" id="lembaga">
              <option value="">....</option>
              <?php
              $pilihan  = array(
                "Laki-laki",
                "Perempuan"
              );
              foreach ($pilihan as $nilai) {
                if ($lembaga == $nilai) {
                  $cek = " selected";
                } else {
                  $cek = "";
                }
                echo "<option value='$nilai' $cek>$nilai</option>";
              }
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td width="90"><strong>pilih Santri </strong></td>
          <td width="5"><strong>:</strong></td>
          <td width="391">
            <input name="santri" type="text" id="santri" value="<?= $santri ?>" />
            <button type="button" id="add-stok" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal">PILIH </button>
            <button type="button" id="clear" class="btn btn-primary btn-xs" data-target="#myModal">clear </button>

            <input name="idsantri" type="hidden" type="text" id="idsantri" value="<?= $idsantri ?>" />
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><input name="btnTampil" type="submit" value=" Tampilkan " /></td>

        </tr>
      </table>
    </div>
  </div>
  <td><a id="export">Export Ke Excell</a></td>
</form>

<div class="dataTable-wrapper">
  <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example" width="100%" cellspacing="1" cellpadding="3">
      <tr>
        <td width="25" align="center" bgcolor="#CCCCCC"><strong>No</strong></td>
        <td width="25" align="center" bgcolor="#CCCCCC"><strong>Tanggal</strong></td>
        <td width="100" bgcolor="#CCCCCC"><strong>Nomor Induk </strong></td>
        <td width="250" bgcolor="#CCCCCC"><strong>Nama Santri </strong></td>
        <td width="100" bgcolor="#CCCCCC"><strong>Jenis Kelamin </strong></td>
        <td width="50" bgcolor="#CCCCCC"><strong>lembaga</strong></td>
        <td width="250" bgcolor="#CCCCCC"><strong>diagnosa </strong></td>
        <td width="39" align="center" bgcolor="#CCCCCC"><strong>Tools</strong></td>
      </tr>
      <?php
      # Perintah untuk menampilkan data Rawat dengan filter Periode
      $mySql = "SELECT rawat.*, pasien.nm_pasien, pasien.kode_lembaga, pasien.jns_kelamin, no_identitas FROM rawat 
  LEFT JOIN pasien ON rawat.nomor_rm = pasien.nomor_rm and pasien.kode_lembaga=pasien.kode_lembaga and pasien.jns_kelamin=pasien.jns_kelamin
  $filterlbg
 
            ORDER BY rawat.no_rawat ASC LIMIT $hal, $row
            
            ";


      $myQry = mysqli_query($koneksidb, $mySql)  or die("Query salah : " . mysqli_error($myQry));
      $nomor = $hal;
      while ($myData = mysqli_fetch_array($myQry)) {
        $nomor++;
      ?>
        <tr>
          <td><?php echo $nomor; ?></td>
          <td><?php echo $myData['tgl_rawat']; ?></td>
          <td><?php echo $myData['no_identitas']; ?></td>
          <td><?php echo $myData['nm_pasien']; ?></td>
          <td><?php echo $myData['jns_kelamin']; ?></td>
          <td><?php echo $myData['kode_lembaga']; ?></td>
          <td><?php echo $myData['hasil_diagnosa']; ?></td>

          <td align="center"><a href="cetak/rawat_cetak.php?noRawat=<?php echo $myData['no_rawat']; ?>" target="_blank">Cetak</a></td>
        </tr>
      <?php } ?>
      <tr>
        <td colspan="3"><strong>Jumlah Data :</strong><?php echo $jml; ?></td>
        <td colspan="5" align="right"><strong>Halaman ke :</strong>
          <?php
          for ($h = 1; $h <= $max; $h++) {
            $list[$h] = $row * $h - $row;
            echo " <a href='?page=Laporan-Rawat-Periode&hal=$list[$h]&tglAwal=$tglAwal&tglAkhir=$tglAkhir'>$h</a> ";
          }
          ?></td>
      </tr>
    </table>
  </div>
</div>

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



        <script>
          $(document).ready(function() {
            $(".tcal").datepicker({
              dateFormat: "yy-mm-dd"
            });

            $("#export").click(function() {
              var tglawal = $("#awal").val();
              var tglahir = $("#ahir").val();
              var lembaga = $("#lembaga").val();
              var idsantri = $('#idsantri').val();




              $.ajax({
                url: "export.php",
                dataType: 'json',
                method: "POST",
                data: {
                  "tgawal": tglawal,
                  "tglahir": tglahir,
                  "lembaga": lembaga,
                  "idsantri": idsantri
                },
                success: function(data) {

                  document.location.href = ('./export/saved_File.xlsx');
                }
              });


            });



            $('#myModal').on('shown.bs.modal', function(e) {



              $(document).on("click", ".pilih", function() {
                var data = $(this).attr('id')
                var url = "./api/rawat.php"
                var tipe = 'rawat'

                pilih(url, data, tipe);

              });




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
              $('#idsantri').val(data.nomor_rm)
              $('#santri').val(data.nm_pasien)

            }

            $('#clear').click(function() {
              $('#idsantri').val(null)
              $('#santri').val(null)

            });



            $('#add-stok').click(function() {
              var tabelj = "<th>NO</th><th>NO IDENTITAS</th><th>NAMA</th><th>JENIS KELAMIN</th><th>ALAMAT</th><th></th>"
              var url = "./api/rawat.php"
              $('.modal-title').text('pilih pasien');
              $('#tabel-judul').append(tabelj);
              setTimeout(function() {
                tabel(url);
              }, 500);

            })
            $('#myModal').on('hidden.bs.modal', function() {
              $('#tabelp').DataTable().clear().destroy();
              $('#tabel-judul').empty();
            })


          });
        </script>