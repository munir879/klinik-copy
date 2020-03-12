<?php

include('database_connection.php');
$month = date('m');
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


if (isset($_POST['filter_moth'])) {


    if ($_POST['filter_moth'] != "") {
        $month = $_POST['filter_moth'];
        $year = $_POST['filter_year'];
    }
}

if (isset($_POST['filter'])) {

    $year = $_POST['filter_year'];
    $querys = "
SELECT 
DISTINCT 
MONTH(tanggal) AS bulan
FROM stok_obat
WHERE YEAR(tanggal) = {$year} 
ORDER BY bulan ASC 
";

    $statement = $connect->prepare($querys);
    $statement->execute();
    $result = $statement->fetchAll();

    $coun = count($result);
    $selec = array();
    for ($i = 0; $i < $coun; $i++) {
        $jml = array();
        $jml["bulan"] = tgl_indo($result[$i]['bulan']);
        $jml["value"] = $result[$i]['bulan'];
        $selec[] = $jml;
    }

    echo json_encode($selec);
    die;
}





function stat1($connect, $year, $month)
{

    $query = "
    SELECT
    DISTINCT IF(MONTH(tanggal) = {$month} AND YEAR(tanggal) = {$year} , DAY(tanggal), 0) as tanggal
 
FROM
    stok_obat
ORDER BY
    tanggal ASC
   
      
    ";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();

    return $result;
}




$vipot = "";
$qury1 = stat1($connect, $year, $month);







$coun = count($qury1);


if (isset($_POST['tabel'])) {
    $tabel = array();
    for ($i = 0; $i < $coun; $i++) {


        if ($qury1[$i]['tanggal'] != 0) {
            $jumlah = array();
            $jumlah["tanggal"] = $qury1[$i]['tanggal'];
            $tabel[] = $jumlah;
        }
    }

    echo json_encode($tabel);
    die;
}



if ($coun > 0) {







    for ($i = 0; $i < $coun; $i++) {

        if ($qury1[0]['tanggal'] == 0) {
            $fi = 1;
        } else {
            $fi = 0;
        }


        if ($qury1[$i]['tanggal'] != 0) {
            if ($i == $fi) {
                $vipot .= ", ";
            }

            $vipot .= "
SUM(
    IF(
        jenis = 1
        AND YEAR(tanggal) = {$year}
        AND MONTH(tanggal) = {$month}
        AND DAY(tanggal) = {$qury1[$i]['tanggal']},
        jumlah,
        0
    )
) AS m{$qury1[$i]['tanggal']},
SUM(
    IF(
        jenis = 2
        AND YEAR(tanggal) = {$year}
        AND MONTH(tanggal) = {$month}
        AND DAY(tanggal) = {$qury1[$i]['tanggal']},
        jumlah,
        0
    )
) AS k{$qury1[$i]['tanggal']},
SUM(
    IF(
        jenis = 1
        AND YEAR(tanggal) = {$year}
        AND MONTH(tanggal) = {$month}
        AND DAY(tanggal) = {$qury1[$i]['tanggal']},
        jumlah,
        0
    )
) - SUM(
    IF(
        jenis = 2
        AND YEAR(tanggal) = {$year}
        AND MONTH(tanggal) = {$month}
        AND DAY(tanggal) = {$qury1[$i]['tanggal']},
        jumlah,
        0
    )
) AS j{$qury1[$i]['tanggal']}
";
            if ($coun - $i == 1) {
            } else {
                $vipot .= " , ";
            }
        }
    }
}




























$column = array('tanggal', 'jumlah');

$query = "
SELECT
stok_obat.kd_obat,
nm_obat,
SUM(if(tanggal < '{$year}-{$month}-01' AND jenis = 1, jumlah, 0)) - SUM(if(tanggal < '{$year}-{$month}-01' AND jenis = 2, jumlah, 0))  AS sisa,

    stok,
    SUM(if(tanggal < '{$year}-{$month}-01' AND jenis = 1, jumlah, 0)) - SUM(if(tanggal < '{$year}-{$month}-01' AND jenis = 2, jumlah, 0)) AS utama


" . $vipot . " 
FROM stok_obat
LEFT JOIN obat on stok_obat.kd_obat = obat.kd_obat
GROUP BY  stok_obat.kd_obat

";




/*
if(isset($_POST['filter_gender'], $_POST['filter_country']) && $_POST['filter_gender'] != '' && $_POST['filter_country'] != '')
{
 $query .= '
 WHERE Gender = "'.$_POST['filter_gender'].'" AND Country = "'.$_POST['filter_country'].'" 
 ';
}

if(isset($_POST['order']))
{
 $query .= 'ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else
{
 $query .= 'ORDER BY CustomerID DESC ';
}
*/
$query1 = '';

if ($_POST["length"] != -1) {
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $connect->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();




$jab = count($result[0]) / 2;



$data = array();


foreach ($result as $row) {
    $s = 7;
    $value = 0;

    $sub_array = array();
    $sub_array[] = $row[0];



    for ($i = 1; $i < $jab; $i++) {



        if ($i === $s) {

            if ($s === 7) {
                $sub_array[] = $row[$i] + $row[$i - 3];
                $value +=  $row[$i]  + $row[$i - 3];
                if ($jab - $i == 1) {
                    $sub_array[] = $row[$i] + $row[$i - 3];
                }
            } else {
                $sub_array[] = $row[$i] + $value;

                if ($jab - $i == 1) {
                    $sub_array[] = $row[$i] + $value;
                }


                $value +=  $row[$i];
            }







            $s = $s + 3;
        } else {
            $sub_array[] = $row[$i];
        }
    };














    $data[] = $sub_array;
}






function count_all_data($connect, $query)
{
    $query = $query;
    $statement = $connect->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}





$output = array(

    "draw"       =>  intval($_POST["draw"]),
    "recordsTotal"   =>  count_all_data($connect, $query),
    "recordsFiltered"  =>  $number_filter_row,
    "data"       =>  $data
);

echo json_encode($output);
