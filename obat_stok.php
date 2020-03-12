<?php
include_once "library/inc.seslogin.php";
include_once "library/inc.library.php";

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$Kode   = isset($_GET['Kode']) ?  $_GET['Kode'] : $_POST['txtKode'];
$row = 50;
$hal = isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql = "SELECT no_stok, stok_obat.kd_obat, nm_obat,stok,keterangan FROM stok_obat JOIN obat ON obat.kd_obat = stok_obat.kd_obat WHERE stok_obat.kd_obat='$Kode'";

$pageQry = mysqli_query($koneksidb, $pageSql) or die("error paging: " . mysqli_error($pageQry));
$jml   = mysqli_num_rows($pageQry);
$max   = ceil($jml / $row);

$filterPeriode = "";
$tglAwal  = "";
$tglAkhir  = "";

# Membaca tanggal dari form, jika belum di-POST formnya, maka diisi dengan tanggal sekarang
$tglAwal   = isset($_POST['txtTglAwal']) ? $_POST['txtTglAwal'] : date('Y-m') . "-01";
$tglAkhir   = isset($_POST['txtTglAkhir']) ? $_POST['txtTglAkhir'] : date('Y-m-d');
$lembaga   = isset($_POST['cmbLembaga']) ? $_POST['cmbLembaga'] : '';




// Jika tombol filter tanggal (Tampilkan) diklik
if (isset($_POST['btnTampil'])) {
  // Membuat sub SQL filter data berdasarkan 2 tanggal (periode)
  $filterPeriode = "AND tanggal BETWEEN '$tglAwal'AND '$tglAkhir'";
  if ($lembaga == null) {
    $filterlbg = "AND (tanggal BETWEEN '$tglAwal'AND '$tglAkhir') ";
  } else {
    $filterlbg = "AND (tanggal BETWEEN '$tglAwal'AND '$tglAkhir') AND jns_kelamin = '$lembaga'";
  }
} else {
  // Membaca data tanggal dari URL, saat menu Pages diklik
  $tglAwal   = isset($_GET['tglAwal']) ? $_GET['tglAwal'] : $tglAwal;
  $tglAkhir   = isset($_GET['tglAkhir']) ? $_GET['tglAkhir'] : $tglAkhir;





  // Membuat sub SQL filter data berdasarkan 2 tanggal (periode)
  $filterPeriode = "";
  $filterlbg = "AND (tanggal BETWEEN '$tglAwal'AND '$tglAkhir') AND kode_lembaga = '$lembaga'";
}




?>

<?php
$obatSql = "
SELECT
obat.kd_obat AS kode,
  obat.nm_obat AS nama,
  SUM( IF(jenis = 1,jumlah,0)) -  SUM( IF(jenis = 2,jumlah,0)) AS stok,
  keterangan
FROM
  obat
  JOIN stok_obat ON obat.kd_obat = stok_obat.kd_obat
  WHERE obat.kd_obat = '$Kode'";


$obatQry = mysqli_query($koneksidb, $obatSql) or die("error paging: " . mysqli_error($obatQry));

while ($myData = mysqli_fetch_array($obatQry)) {
  $kode_obat = $myData['kode'];
  $nm_obat = $myData['nama'];
  $st_obat = $myData['stok'];
  $k_obat = $myData['keterangan'];
};

?>

<style>
  .datepicker {
    z-index: 1600 !important;
    /* has to be larger than 1050 */
  }
</style>
<div class="row">
  <div class="col-lg-12">

    <h1 class="page-header">Stok Obat</h1>
  </div>
  <!-- /.col-lg-12 -->
</div>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self">
  <div class="dataTable-wrapper">
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="dataTables-example" width="100%" cellspacing="1" cellpadding="3">
        <tr>
          <td width="401" colspan="2">
            <button type="button" id="add-stok" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" kode-obat="<?= $kode_obat ?>">Tambah Stok</button>
        </tr>

        <tr>
          <td colspan="3" bgcolor="#CCCCCC"><strong>Keterangan </strong></td>
        </tr>
        <tr>
          <td width="90"><strong>Kode obat </strong></td>
          <td width="5"><strong>:</strong></td>
          <td width="391"><strong><?php echo $kode_obat; ?></strong></td>

        </tr>






        <tr>
          <td width="90"><strong>Nama Obat </strong></td>
          <td width="5"><strong>:</strong></td>
          <td width="391">
            <strong><?php echo $nm_obat; ?></strong></td>
        </tr>


        <tr>
          <td width="90"><strong>Stok </strong></td>
          <td width="5"><strong>:</strong></td>
          <td width="391">
            <strong><?php echo $st_obat; ?></strong></td>
        </tr>



        <tr>
          <td colspan="3" bgcolor="#CCCCCC"><strong>PERIODE Stok </strong></td>
        </tr>
        <tr>
          <td width="90"><strong>Keterangan </strong></td>
          <td width="5"><strong>:</strong></td>
          <td width="391"><input name="txtTglAwal" type="text" class="tcal" id="awal" value="<?php echo $tglAwal; ?>" />
            s/d
            <input name="txtTglAkhir" type="text" class="tcal" id="ahir" value="<?php echo $tglAkhir; ?>" /></td>
        </tr>

        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><input name="btnTampil" type="submit" value=" Tampilkan " /></td>

        </tr>
      </table>
    </div>
  </div>

</form>



<table class="table table-striped table-bordered table-hover" id="dataTables-example" width="100%" cellspacing="1" cellpadding="3">

  <tr>
    <td colspan="2">
      <div class="dataTable-wrapper">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover" id="dataTables-example" width="100%" cellspacing="1" cellpadding="3">
            <tr>
              <th width="20" align="center"><strong>No</strong></th>
              <th width="30" align="center"><strong>Tanggal</strong></th>
              <th width="130"><strong>Jumlah</strong></th>


              <th width="180"><strong>Keterangan</strong></th>
              <td colspan="3" align="center" bgcolor="#CCCCCC"><strong>Tools</strong></td>
            </tr>
            <?php
            $mySql = "SELECT * FROM stok_obat WHERE kd_obat='$Kode' " . $filterPeriode . " ORDER BY kd_obat ASC LIMIT $hal, $row";


            $myQry = mysqli_query($koneksidb, $mySql)  or die("Query salah : " . mysqli_error($myQry));
            $nomor  = 0;
            while ($myData = mysqli_fetch_array($myQry)) {
              $nomor++;
              $Kode = $myData['kd_obat'];
            ?>
              <tr>
                <td align="center"><?php echo $nomor; ?></td>


                <td><?php echo $myData['tanggal']; ?></td>
                <td align="center"><?php echo $myData['jumlah']; ?></td>
                <td align="center"><?php echo $myData['jenis']; ?></td>


                <td width="45" align="center"><a id="edit-stok" data-toggle="modal" kode-obat="<?= $myData['no_stok'] ?>" data-target="#exampleModal">Edit</a></td>
                <td width="44" align="center"><a class="bthapus" href="?page=Obat-Delete&amp;Stok=<?php echo $myData['no_stok'];
                                                                                                  echo "&Obat=" . $Kode; ?>" target="_self" alt="Delete Data">Delete</a></td>

              </tr>

            <?php } ?>
          </table>
        </div>
      </div>
    </td>
  </tr>
  <tr>
    <td><strong>Jumlah Data :</strong> <?php echo $jml; ?> </td>
    <td align="right">
      <strong>Halaman ke :</strong>
      <?php
      for ($h = 1; $h <= $max; $h++) {
        $list[$h] = $row * $h - $row;
        echo " <a href='?page=Stoke-Obat&Kode=$Kode&hal=$list[$h]'>$h</a> ";
      }
      ?> </td>
  </tr>
</table>



<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Id Obat:</label>
            <input recruitment type="text" readonly class="form-control" id="id-obat">
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label">Tanggal</label>
            <input recruitment type="text" class="form-control" id="datem">
          </div>

          <div class="form-group">
            <label for="message-text" class="col-form-label">Jumlah</label>
            <input recruitment type="number" class="form-control" id="jumlah">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="save" class="btn btn-primary save">SAVE</button>
      </div>
    </div>
  </div>
</div>






<script>
  $(document).ready(function() {
    $(".tcal").datepicker({
      dateFormat: "yy-mm-dd",
      autoclose: true,
      todayHighlight: true,
      container: '#myModal modal-body'

    });



    $('#exampleModal').on('shown.bs.modal', function(e) {
      clear();
      console.log(e.relatedTarget.attributes[2].value)
      var atribut = e.relatedTarget.id
      console.log(e.relatedTarget.id);
      if (atribut == "add-stok") {
        $(".save").attr("id", "save");
        $(".save").text("save")
        var kode_obat = $("#add-stok").attr('kode-obat');
      }

      if (atribut == "edit-stok") {
        $(".save").attr("id", "edit");
        $(".save").text("edit")
        var kode_obat = e.relatedTarget.attributes[2].value;

        var view = {
          send: "view",
          no_obat: kode_obat
        };

        $.ajax({
          url: "api/Stoke-ajax.php",
          dataType: 'json',
          method: "POST",
          data: view,
          success: function(data) {
            console.log(data)
            $("#id-obat").val(data.no_stok);
            $("#datem").val(data.tanggal);
            $("#jumlah").val(data.jumlah);
            $(".save").attr("id", "edit");


          }
        });

      }





      $("#id-obat").val(kode_obat);
      $('#datem').datepicker({
        dateFormat: "yy-mm-dd",
        autoclose: true,
        todayHighlight: true,
        container: '#myModal modal-body'
      });

      $('#save').click(function() {
        var tanggal = $("#datem").val();
        var jumlah = $("#jumlah").val();

        if (tanggal == "" || jumlah == "") {

          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'tanggal / jumlah belum di isi',
            footer: '<a href>harap di isi kedua colom tersebut</a>'
          })


        } else {


          var data = {
            send: "add",
            id_obat: kode_obat,
            tanggal: tanggal,
            jumlah: jumlah,
            jenis: 1
          };

          var succes = ajax("api/Stoke-ajax.php", data);

          if (succes) {
            Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: 'Stok berhasil di tambah',
              showConfirmButton: false,
              timer: 700
            })

            setTimeout(function() {
              $('#exampleModal').modal('hide');
              location.reload();
            }, 1000);

          }


        }

      });


      $('#edit').click(function() {
        var tanggal = $("#datem").val();
        var jumlah = $("#jumlah").val();


        if (tanggal == "" || jumlah == "") {

          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'tanggal / jumlah belum di isi',
            footer: '<a href>harap di isi kedua colom tersebut</a>'
          })


        } else {


          var data = {
            send: "edit",
            no_obat: kode_obat,
            tanggal: tanggal,
            jumlah: jumlah
          };

          var succes = ajax("api/Stoke-ajax.php", data);

          if (succes) {
            Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: 'data berhasil di edit',
              showConfirmButton: false,
              timer: 700
            })
            $('#exampleModal').hide;
            setTimeout(function() {

              location.reload();
            }, 1000);
          }


        }

      });






    });


    function clear() {
      $("#datem").val(null);
      $("#jumlah").val(null);
    }



    function ajax(url, data) {


      $.ajax({
        url: url,
        dataType: 'json',
        method: "POST",
        data: data,
        success: function(data) {


        }
      });

      return "succes";

    }


    $("#export").click(function() {
      var tglawal = $("#awal").val();
      var tglahir = $("#ahir").val();
      var lembaga = $("#lembaga").val();




      $.ajax({
        url: "export.php",
        dataType: 'json',
        method: "POST",
        data: {
          "tgawal": tglawal,
          "tglahir": tglahir,
          "lembaga": lembaga
        },
        success: function(data) {

          document.location.href = ('./export/saved_File.xlsx');
        }
      });


    });






    $('.bthapus').click(function(e) {
      e.preventDefault();
      const href = $(this).attr('href')

      Swal.fire({
        title: 'APAKAH ANDA YAKIN ?',
        text: "STOK AKAN DI HAPUS",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.value) {
          document.location.href = href;
        }
      })

    });




  });
</script>