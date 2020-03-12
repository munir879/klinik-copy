<?php
$namaBulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
$moth = date("m");


if (isset($_SESSION['SES_ADMIN'])) {
?>



    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Home - Admin</h1>
        </div>

        <h4>Selamat datang di Klinik NUSANTARA HEALTH CARE</h4></b><br />


        <!-- /.row -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-user fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">Pasien</div>
                                <div>Pengelolaan Data</div>
                            </div>
                        </div>
                    </div>
                    <a href="?page=Pasien-Data">
                        <div class="panel-footer">
                            <span class="pull-left">Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-user fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">Dokter</div>
                                <div>Pengelolaan Data</div>
                            </div>
                        </div>
                    </div>
                    <a href="?page=Dokter-Data">
                        <div class="panel-footer">
                            <span class="pull-left">Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-shopping-cart fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">Obat</div>
                                <div>Pengolahan Data</div>
                            </div>
                        </div>
                    </div>
                    <a href="?page=Obat-Data">
                        <div class="panel-footer">
                            <span class="pull-left">Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-file fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">Laporan</div>
                                <div>Rincian</div>
                            </div>
                        </div>
                    </div>
                    <a href="?page=Laporan">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- /.row -->


        <div class="container">
            <h3 align="center">DATA DIAGNOSA </h3>
            <br />

            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-4">
                            <h3 class="panel-title">TOP 10 DIAGNOSA </h3>
                        </div>
                        <div class="col-md-3">
                            <select name="year" class="form-control" id="dmoth">
                                <?php foreach ($namaBulan as $key => $bulan) {
                                    if ($key + 1 == $moth) {
                                        $selec = "selected";
                                    } else {
                                        $selec = "";
                                    }

                                ?>



                                    <option value="<?= $key + 1 ?>" <?= $selec ?>><?= $bulan ?></option>

                                <?php } ?>

                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="year" class="form-control" id="dyear">
                                <?php for ($i = date('yy') - 5; $i <= date('yy'); $i++) {
                                    if (date('yy') == $i) {
                                        $selec = "selected";
                                    } else {
                                        $selec = "";
                                    }
                                ?>

                                    <option value="<?= $i ?>" <?= $selec ?>><?= $i ?></option>

                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="tdiagnosa" class="btn btn-primary">filter</button>
                        </div>

                    </div>
                </div>
                <div class="panel-body">
                    <div id="chart_area" style="width: 1000px; height: 620px;"></div>
                </div>
            </div>
        </div>

        <div class="container">
            <h3 align="center">DATA RAWAT PASIEN 30 HARI TERAKHIR</h3>
            <br />

            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-4">
                            <h3 class="panel-title">TOP 10 PASIEN </h3>
                        </div>
                        <div class="col-md-3">
                            <select name="year" class="form-control" id="pmoth">
                                <?php foreach ($namaBulan as $key => $bulan) {
                                    if ($key + 1 == $moth) {
                                        $selec = "selected";
                                    } else {
                                        $selec = "";
                                    }

                                ?>



                                    <option value="<?= $key + 1 ?>" <?= $selec ?>><?= $bulan ?></option>

                                <?php } ?>

                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="year" class="form-control" id="pyear">
                                <?php for ($i = date('yy') - 5; $i <= date('yy'); $i++) {
                                    if (date('yy') == $i) {
                                        $selec = "selected";
                                    } else {
                                        $selec = "";
                                    }
                                ?>

                                    <option value="<?= $i ?>" <?= $selec ?>><?= $i ?></option>

                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="pdiagnosa" class="btn btn-primary">filter</button>
                        </div>

                    </div>

                </div>
                <div class="panel-body">
                    <div id="rawat" style="width: 1000px; height: 620px;"></div>
                </div>
            </div>
        </div>



    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {
            packages: ['corechart', 'bar']
        });
        google.charts.setOnLoadCallback();

        function tindakan(moth, year) {

            $.ajax({
                url: "chart.php",
                method: "POST",
                data: {
                    tindakan: 1,
                    moth: moth,
                    year: year
                },
                dataType: "JSON",
                success: function(data) {

                    drawMonthwiseChart(data, "Data");

                }
            });

        }

        function pasien(moth, year) {
            $.ajax({
                url: "chart.php",
                method: "POST",
                data: {
                    pasien: 1,
                    moth: moth,
                    year: year
                },
                dataType: "JSON",
                success: function(data) {

                    chartrawat(data, "Data");

                }
            });


        }


        function drawMonthwiseChart(chart_data, chart_main_title) {
            var jsonData = chart_data;
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Month');
            data.addColumn('number', 'Profit');
            $.each(jsonData, function(i, jsonData) {
                var month = jsonData.month;
                var profit = parseFloat($.trim(jsonData.profit));
                data.addRows([
                    [month, profit]
                ]);
            });
            var options = {
                title: chart_main_title,
                hAxis: {
                    title: "Nama"
                },
                vAxis: {
                    title: 'Jumlah'
                }
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('chart_area'));
            chart.draw(data, options);
        }


        function chartrawat(chart_data, chart_main_title) {
            var jsonData = chart_data;
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Month');
            data.addColumn('number', 'Profit');
            $.each(jsonData, function(i, jsonData) {
                var month = jsonData.month;
                var profit = parseInt($.trim(jsonData.profit));
                data.addRows([
                    [month, profit]
                ]);
            });
            var options = {
                title: chart_main_title,
                hAxis: {
                    title: "Pasien"
                },
                vAxis: {
                    title: 'Jumlah'
                }
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('rawat'));
            chart.draw(data, options);
        }
    </script>

    <script>
        $(document).ready(function() {



            $.ajax({
                url: "chart.php",
                method: "POST",
                data: {
                    tindakan: 1
                },
                dataType: "JSON",
                success: function(data) {

                    setTimeout(function() {
                        drawMonthwiseChart(data, 'Data');
                    }, 1000);

                }
            });

            $.ajax({
                url: "chart.php",
                method: "POST",
                data: {
                    pasien: ''
                },
                dataType: "JSON",
                success: function(data) {

                    setTimeout(function() {
                        chartrawat(data, "Data");
                    }, 1000);


                }
            });

            $('#tdiagnosa').click(function(e) {
                var moth = $("#dmoth").val();
                var year = $("#dyear").val();


                tindakan(moth, year)
            });

            $('#pdiagnosa').click(function(e) {
                var moth = $("#pmoth").val();
                var year = $("#pyear").val();


                pasien(moth, year)
            });




        });
    </script>



<?php
} else if (isset($_SESSION['SES_KLINIK'])) {

?>


    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Home - Klinik</h1>
        </div>

        <h4>Selamat datang di Klinik NUSANTARA HEALTH CARE</h4></b><br />


        <!-- /.row -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-user fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">Pasien</div>
                                <div>Tambah Data</div>
                            </div>
                        </div>
                    </div>
                    <a href="pendaftaran/">
                        <div class="panel-footer">
                            <span class="pull-left">Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-user fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">Rawat</div>
                                <div>Tambah Data</div>
                            </div>
                        </div>
                    </div>
                    <a href="rawat-pasien/">
                        <div class="panel-footer">
                            <span class="pull-left">Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- /.row -->

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    </div>
<?php

} else if (isset($_SESSION['SES_APOTEK'])) {
?>


    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Home - Obat</h1>
        </div>

        <h4>Selamat datang di Klinik NUSANTARA HEALTH CARE</h4></b><br />


        <!-- /.row -->
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-shopping-cart fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">Obat</div>
                            <div>Data Pengolahan</div>
                        </div>
                    </div>
                </div>
                <a href="penjualan/">
                    <div class="panel-footer">
                        <span class="pull-left">Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <!-- /.row -->
    </div>
<?php
} else {
?>
    <br><br><br>
    <img src="./images/logo.png" width="650px" style="opacity:3.3">
    <br><br>
    <h3>Selamat datang di Klinik</h3>
    <h2>NUSANTARA HEALTH CARE</h2>
    <b>Anda belum login, silahkan <a href='?page=Login' alt='Login'> LOGIN </a>untuk mengakses sitem ini </b>
<?php
}
?>