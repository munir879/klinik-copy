<?php

include('database_connection.php');

if(isset($_POST['tipe'])){
    $nor = $_POST['norm'];
    $query = "
    SELECT
    obat.kd_obat AS kode,
    nm_obat,
    SUM(IF( jenis = 1, jumlah, 0)) - SUM(IF( jenis = 2, jumlah, 0)) AS jmlstok
    FROM
    obat
    JOIN stok_obat ON obat.kd_obat = stok_obat.kd_obat
    WHERE obat.kd_obat = '{$nor}'
";
$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetch(PDO::FETCH_ASSOC);


echo json_encode($result);


    die;
}











$column = array('kode', 'nm_obat', 'stok', 'jmlstok', null);

$query = "
SELECT
    obat.kd_obat AS kode,
    nm_obat,
    stok,
    SUM(IF( jenis = 1, jumlah, 0)) - SUM(IF( jenis = 2, jumlah, 0)) AS jmlstok
FROM
    obat
    LEFT JOIN stok_obat ON obat.kd_obat = stok_obat.kd_obat
 
";

if(isset($_POST["search"]["value"]) && $_POST["search"]["value"]  != "")  
{  
    $search = $_POST["search"]["value"];
    $query .= " WHERE obat.kd_obat LIKE '%{$search}%' ";
    $query .= " OR nm_obat LIKE '%{$search}%'";
    
}  

$query .= " GROUP BY obat.kd_obat ";





if(isset($_POST['order']))
{
 $query .= 'ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else
{
 $query .= 'ORDER BY nm_obat ASC ';
}

$query1 = '';

if($_POST["length"] != -1)
{
 $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $connect->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();



$data = array();
$i=1;

foreach($result as $row)
{
   
$nor = $row["kode"];
 $sub_array = array();
 $sub_array[] = $i;
 $sub_array[] = $row['kode'];
 $sub_array[] = $row['nm_obat'];
 $sub_array[] = $row['stok'];
 $sub_array[] = $row['jmlstok'];
    if($row['jmlstok'] > $row['stok'] ){
        $sub_array[] = "<button type='button' name='pilih' class='btn btn-success btn-xs piliho' id='{$nor}'>Pilih</button>";
    }else{
        $sub_array[] = "<button type='button' name='pilih' class='btn btn-danger btn-xs' id='{$nor}'>STOK HABIS</button>";
    }



 $data[] = $sub_array;
 $i++;
}

function count_all_data($connect)
{
 $query = "SELECT
 obat.kd_obat AS kode,
 nm_obat,
 stok,
 SUM(jumlah) AS jmlstok
FROM
 obat
 JOIN stok_obat ON obat.kd_obat = stok_obat.kd_obat
GROUP BY
 obat.kd_obat";
 $statement = $connect->prepare($query);
 $statement->execute();
 return $statement->rowCount();
}

$output = array(
 "draw"       =>  intval($_POST["draw"]),
 "recordsTotal"   =>  count_all_data($connect),
 "recordsFiltered"  =>  $number_filter_row,
 "data"       =>  $data
);

echo json_encode($output);
