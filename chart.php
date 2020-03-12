<?php

include './library/inc.connection.php';
$moth = date('m');
$year = date('yy');
if (isset($_POST["year"])) {
    $year =  $_POST["year"];
    $moth = $_POST["moth"];
}




if (isset($_POST["tindakan"])) {





    $query = "
    SELECT tindakan.nm_tindakan AS nama, COUNT(rawat_tindakan.kd_tindakan) AS jumlah FROM `rawat_tindakan` 
    INNER JOIN tindakan
    ON rawat_tindakan.kd_tindakan = tindakan.kd_tindakan
    WHERE
    MONTH(rawat_tindakan.tgl_tindakan) = $moth 
    AND YEAR(rawat_tindakan.tgl_tindakan) = $year
    GROUP BY rawat_tindakan.kd_tindakan
    ORDER BY `jumlah`  DESC LIMIT 10
    
    ";



    $myQry = mysqli_query($koneksidb, $query)  or die("Query salah : " . mysqli_error($koneksidb));

    //var_dump(mysqli_fetch_array($myQry)); die;
    foreach ($myQry as $row) {
        $output[] = array(
            'month'   => $row["nama"],
            'profit'  => floatval($row["jumlah"])


        );
    }
    echo json_encode($output);
}


if (isset($_POST["pasien"])) {




    $query = "
    SELECT pasien.nm_pasien AS nama, COUNT(rawat.nomor_rm) AS jumlah
FROM `rawat` 
JOIN pasien 
ON pasien.nomor_rm = rawat.nomor_rm
WHERE

MONTH(rawat.tgl_rawat) = $moth 
AND YEAR(rawat.tgl_rawat) = $year


GROUP BY rawat.nomor_rm  
ORDER BY `jumlah` ASC
LIMIT 10
    
    ";



    $myQry = mysqli_query($koneksidb, $query)  or die("Query salah : " . mysqli_error($myQry));
    //var_dump(mysqli_fetch_array($myQry)); die;
    foreach ($myQry as $row) {
        $output[] = array(
            'month'   => $row["nama"],
            'profit'  => $row["jumlah"]
        );
    }
    echo json_encode($output);
}
