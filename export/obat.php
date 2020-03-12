<?php
//memanggil fungsi
require '../vendor/autoload.php';
include '../library/inc.connection.php';
include('../api/database_connection.php');
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





use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Laporan obat Bulanan');

$sheet->setCellValue('A3', 'periode');
$sheet->setCellValue('B3', tgl_indo($month));
$sheet->setCellValue('C3', $year);


$sheet->setCellValue('A5', 'kode obat');
$sheet->setCellValue('B5', 'nama obat');
$sheet->setCellValue('C5', 'sisa ');

$sheet->setCellValue('D5', 'stok minimal');
$sheet->setCellValue('E5', 'stok utama ');


$spreadsheet->getActiveSheet()->mergeCells("A5:A6");
$spreadsheet->getActiveSheet()->mergeCells("B5:B6");
$spreadsheet->getActiveSheet()->mergeCells("C5:C6");
$spreadsheet->getActiveSheet()->mergeCells("D5:D6");
$spreadsheet->getActiveSheet()->mergeCells("E5:E6");



if (isset($_POST['filter_moth'])) {


    if ($_POST['filter_moth'] != "") {
        $month = $_POST['filter_moth'];
        $year = $_POST['filter_year'];
    }
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

$qury1 = stat1($connect, $year, $month);
if ($qury1[0]['tanggal'] == 0) {
    $fi = 1;
    $b = 1;
    $c = 1;
} else {
    $fi = 0;
    $b = 0;
    $c = 0;
}


$count = (count($qury1) - $fi) * 3;
$coun = count($qury1);
$vipot = "";
$a = 'F';

$d = "H";
$e = "M";
$range = range('G', 'Z');


for ($i = $fi; $i < $count; $i++) {


    $sheet->setCellValue($a . "6", $e);

    if ($i == $b) {

        $sheet->setCellValue($a . "5", $qury1[$c]['tanggal']);

        $spreadsheet->getActiveSheet()->mergeCells($a . "5:" . $d . "5");

        $c++;
        $b += 3;
    }
    $a++;
    $d++;
    if ($e == "M") {
        $e = "K";
    } else if ($e == "K") {
        $e = "S";
    } else {
        $e = "M";
    }

    if ($count - $i == 1) {
        if ($fi == 1) {
            $a++;
        }

        $sheet->setCellValue($a . "5", 'total stok');
        $spreadsheet->getActiveSheet()->mergeCells($a . "5:" . $a . "6");
    }
}




if ($coun > 0) {


    for ($i = 0; $i < $coun; $i++) {




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


$statement = $connect->prepare($query);

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


$i = 7;
foreach ($data as $asd) {
    $abc = "a";
    for ($x = 0; $x < count($asd); $x++) {
        $sheet->setCellValue($abc . $i, $asd[$x]);
        $abc++;
    }
    $i++;
}





/*




if($lembaga == null){
    $filter = "WHERE tgl_rawat BETWEEN '$tglawal'AND '$tglahir'";
}else{
   
    $filter = "WHERE (tgl_rawat BETWEEN '$tglawal'AND '$tglahir') AND jns_kelamin = '$lembaga'";
}



$query = "SELECT rawat.*, pasien.nm_pasien, pasien.kode_lembaga FROM rawat 
LEFT JOIN pasien ON rawat.nomor_rm = pasien.nomor_rm and pasien.kode_lembaga=pasien.kode_lembaga
$filter
ORDER BY rawat.no_rawat ASC ";

// masukkan sql ke dalam query
$sql = mysqli_query($koneksidb, $query);




$x=6;

while ($row = mysqli_fetch_array($sql)) {

       $sheet->setCellValue('A'.$x, $x-5);
       $sheet->setCellValue('B'.$x, $row['no_rawat']);
       $sheet->setCellValue('C'.$x, $row['tgl_rawat']);
       $sheet->setCellValue('D'.$x, $row['nomor_rm']);
       $sheet->setCellValue('E'.$x, $row['nm_pasien']);
       $sheet->setCellValue('F'.$x, $row['kode_lembaga']);
       $sheet->setCellValue('G'.$x, $row['hasil_diagnosa']);
       $x++;
}

*/

$filePath = '../export/export-obat.xlsx';



$writer = new Xlsx($spreadsheet);
$writer->save($filePath);


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="export-obat.xlsx"');

$response = array(
    'success' => true,
    'url' => './export/export-obat.xlsx'
);
echo json_encode($response);
