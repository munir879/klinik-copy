<?php
//memanggil fungsi
require 'vendor/autoload.php';
include './library/inc.connection.php';
$tglawal = $_POST["tgawal"];
$tglahir = $_POST["tglahir"];
$lembaga = $_POST["lembaga"];
$idsantri = $_POST["idsantri"];

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Laporan Rawat Pasien');

$sheet->setCellValue('A3', 'periode');
$sheet->setCellValue('B3', $tglawal);
$sheet->setCellValue('C3', '-');
$sheet->setCellValue('D3', $tglahir);


$sheet->setCellValue('A5', 'NO URUT');
$sheet->setCellValue('B5', 'TGL RAWAT');
$sheet->setCellValue('C5', 'NO INDUK');
$sheet->setCellValue('D5', 'NAMA SANTRI');
$sheet->setCellValue('E5', 'LEMBAGA');
$sheet->setCellValue('F5', 'HASIL DIAGNOSA');
if ($lembaga == null) {
    $filter = "WHERE tgl_rawat BETWEEN '$tglawal'AND '$tglahir'";
} else {

    $filter = "WHERE (tgl_rawat BETWEEN '$tglawal'AND '$tglahir') AND jns_kelamin = '$lembaga'";
}

if ($idsantri != null) {
    $filter = "WHERE (tgl_rawat BETWEEN '$tglawal'AND '$tglahir') AND rawat.nomor_rm = '$idsantri' ";
}



$query = "SELECT rawat.*, pasien.nm_pasien, pasien.kode_lembaga, no_identitas FROM rawat 
LEFT JOIN pasien ON rawat.nomor_rm = pasien.nomor_rm and pasien.kode_lembaga=pasien.kode_lembaga
$filter
ORDER BY rawat.no_rawat ASC ";






// masukkan sql ke dalam query
$sql = mysqli_query($koneksidb, $query);




$x = 6;

while ($row = mysqli_fetch_array($sql)) {

    $sheet->setCellValue('A' . $x, $x - 5);
    $sheet->setCellValue('B' . $x, $row['tgl_rawat']);
    $sheet->setCellValue('C' . $x, $row['no_identitas']);
    $sheet->setCellValue('D' . $x, $row['nm_pasien']);
    $sheet->setCellValue('E' . $x, $row['kode_lembaga']);
    $sheet->setCellValue('F' . $x, $row['hasil_diagnosa']);
    $x++;
}



$filePath = './export/saved_File.xlsx';



$writer = new Xlsx($spreadsheet);
$writer->save($filePath);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="export.xlsx"');

$response = array(
    'success' => true,
    'url' => $filePath
);
echo json_encode($response);
