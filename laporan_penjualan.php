<?php
include_once "library/inc.seslogin.php";
include('./api/database_connection.php');
$year = date('yy');
function tgl_indo($tanggal)
{
  $bulan = array(
    1 =>   'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
  );
  return $bulan[(int) $tanggal];
}




# UNTUK PAGING (PEMBAGIAN HALAMAN)
$row = 50;
$hal = isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql = "SELECT * FROM penjualan";
$pageQry = mysqli_query($koneksidb, $pageSql) or die("error paging: " . mysqli_error($pageQry));
$jml   = mysqli_num_rows($pageQry);
$max   = ceil($jml / $row);


$query = "
SELECT 
DISTINCT 
MONTH(tanggal) AS bulan
FROM stok_obat
WHERE YEAR(tanggal) = {$year} 
ORDER BY bulan ASC 
";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

$sb = "";
$s;


foreach ($result as $row) {
  if ((int) $row['bulan'] == (int) date('m')) {
    $s = "selected";
  } else {
    $s = "";
  }
  $bulan = tgl_indo($row['bulan']);
  echo $bulan;
  $sb .= "<option {$s} value='{$row['bulan']}'>{$bulan}</option>";
}


$queryb = "
SELECT 
DISTINCT 
YEAR(tanggal) AS tahun
FROM stok_obat 

ORDER BY tahun ASC 
";
$statement = $connect->prepare($queryb);
$statement->execute();
$resultb = $statement->fetchAll();

$st = "";

foreach ($resultb as $row) {
  if ((int) $row['tahun'] == (int) date('yy')) {
    $s = "selected";
  } else {
    $s = "";
  }

  $st .= "<option {$s} value='{$row['tahun']}'>{$row['tahun']}</option>";
}




?>


<div class="row">

  <div class="col-lg-12">
    <h1 class="page-header">Laporan Pengeluaran Obat</h1>

    <div class="row">
      <div class="col-md-4"></div>
      <div class="col-md-4">

        <div class="form-group">
          <select name="filter_country" id="filter_year" class="form-control" required>

            <?= $st; ?>
          </select>
        </div>
        <div class="form-group">
          <select name="filter_moth" id="filter_moth" class="form-control" required>

            <?= $sb ?>
          </select>
        </div>

        <div class="form-group" align="center">
          <button type="button" name="filter" id="filter" class="btn btn-info">Filter</button>
        </div>

      </div>
      <div class="col-md-4"></div>

    </div>
    <button type="button" id="export" class="btn btn-info">EXPORT EXCEL</button>
    <br>
    <br>



    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="asd">
        <thead class="" id="the">
          <tr id="collom">
            <th width="50" rowspan="2" align="center" bgcolor="#CCCCCC" class="rowspn">kode obat</th>
            <th rowspan="2" width="94" align="center" bgcolor="#CCCCCC" class="rowspn">nama obat</th>
            <th rowspan="2" width="94" align="center" bgcolor="#CCCCCC" class="rowspn">sisa</th>
            <th rowspan="2" width="94" align="center" bgcolor="#CCCCCC" class="rowspn">stok minimal</th>
            <th rowspan="2" width="94" align="center" bgcolor="#CCCCCC" class="rowspn">stok utama</th>



          </tr>
          <tr id="ddddd">





          </tr>

        </thead>
        <tbody id="isi">

        </tbody>
      </table>
    </div>

  </div>
</div>
<!-- /.col-lg-12 -->
</div>






<script>
  $(document).ready(function() {

    tabel();

    setTimeout(function() {

      data();
    }, 500);



    function tabel(filter_moth = null, filter_year = null) {
      $.ajax({
        type: "POST",
        url: "./api/tabel.php",
        data: {
          tabel: 1,
          filter_moth: filter_moth,
          filter_year: filter_year

        },
        dataType: 'JSON',
        success: function(data) {
          $("#ddddd").remove();
          $(".rowspn").removeAttr("rowspan");


          console.log(data);
          if (data == "") {

          } else {
            var tr = ' <tr id="ddddd" data="Tambah"></tr>'

            $("#the").append(tr);
            $(".rowspn").attr("rowspan", "2");

            data.forEach(function(item) {
              var head = '<th colspan="3" class="tgi" >' + item['tanggal'] + '</th>'
              $("#collom").append(head);
              var baris_baru = '<th>m</th><th>k</th><th>s</th>';
              $("#ddddd").append(baris_baru);

            });
            var total = ' <th rowspan="2" class="tgi" width="94" align="center" bgcolor="#CCCCCC" class="rowspn" >Total Stok</th>'
            $("#collom").append(total);



          }






        }
      });

    }













    function data(filter_moth = null, filter_year = null) {



      $('#asd').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "order": [],
        "searching": false,
        "ajax": {
          url: "./api/tabel.php",
          type: "POST",
          datatype: "json",
          data: {
            filter_moth: filter_moth,
            filter_year: filter_year
          }

        },





      });




    }


    $('#filter').click(function() {
      var filter_moth = $('#filter_moth').val();
      var filter_year = $('#filter_year').val();

      if (filter_moth != '' && filter_year != '') {
        $('#asd').DataTable().destroy();
        $(".tgi").remove();

        $("#ddddd").empty();
        $("#isi").empty();

        tabel(filter_moth, filter_year);
        setTimeout(function() {

          data(filter_moth, filter_year);;
        }, 500);
      } else {

        $('#asd').DataTable().destroy();
        $(".tgi").remove();

        $("#ddddd").empty();
        $("#isi").empty();

        tabel();

        setTimeout(function() {

          data();
        }, 500);
      }

    });

    $("#filter_year").change(function() {
      var filter_year = $('#filter_year').val();
      $.ajax({
        type: "POST",
        url: "./api/tabel.php",
        data: {
          filter: 1,
          filter_year: filter_year

        },
        dataType: 'JSON',
        success: function(data) {

          $("#filter_moth").empty();
          data.forEach(function(item) {
            var selec = '<option  value="' + item["value"] + '">' + item["bulan"] + '</option>'
            $("#filter_moth").append(selec);


          });


        }
      });



    });


    $('#export').click(function() {
      var filter_moth = $('#filter_moth').val();
      var filter_year = $('#filter_year').val();

      $.ajax({
        type: "POST",
        url: "./export/obat.php",
        data: {
          tabel: 1,
          filter_moth: filter_moth,
          filter_year: filter_year

        },
        dataType: 'JSON',
        success: function(data) {

          document.location.href = (data.url);
        }

      });


    });






  });
</script>