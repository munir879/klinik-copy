<?php
include('database_connection.php');

function stat1($connect)
{

    $query = "
    SELECT
    GROUP_CONCAT(
        DISTINCT CONCAT(
            'SUM( IF(jenis = 1 AND MONTH(tanggal) = 10 AND DAY(tanggal) = ',
            DAY(tanggal),
            ',jumlah,0) ) AS tanggalm_',
            DAY(tanggal),
            ', SUM( IF(jenis = 2 AND MONTH(tanggal) = 10 AND DAY(tanggal) = ',
            DAY(tanggal),
            ',jumlah,0) ) AS tanggalk_',
            DAY(tanggal),
            ', SUM( IF(jenis = 1 AND MONTH(tanggal) = 10 AND DAY(tanggal) = ',
            DAY(tanggal),
            ',jumlah,0) ) ',
            
            '- SUM( IF(jenis = 2 AND MONTH(tanggal) = 10 AND DAY(tanggal) = ',
            DAY(tanggal),
            ',jumlah,0) ) ',
            
            'AS KURANG',DAY(tanggal)
        )
    )AS DATA
FROM
    stok_obat
    ";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();

    return $result;
}



$query1 = stat1($connect);




$select = "";

foreach ($query1 as  $value) {
    $select .= $value["DATA"];
}





$query = "
SELECT kd_obat,
" . $select . " 
FROM stok_obat 
GROUP BY kd_obat WITH ROLLUP
";



$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();


echo json_encode($result);
