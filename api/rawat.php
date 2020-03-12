<?php

include('database_connection.php');

if (isset($_POST['tipe'])) {
    $nor = $_POST['norm'];
    $query = "
    SELECT  
    nomor_rm,
    nm_pasien,
    no_identitas,
    kode_lembaga,
    TIMESTAMPDIFF( 
    YEAR , tanggal_lahir, NOW() ) AS usia
    FROM pasien WHERE nomor_rm = '{$nor}'
";
    $statement = $connect->prepare($query);

    $statement->execute();

    $result = $statement->fetch(PDO::FETCH_ASSOC);


    echo json_encode($result);


    die;
}








$column = array('no_identitas', 'nm_pasien', 'jns_kelamin', 'alamat', null);

$query = "
SELECT * FROM pasien 
";

if (isset($_POST["search"]["value"]) && $_POST["search"]["value"]  != "") {
    $search = $_POST["search"]["value"];
    $query .= " WHERE no_identitas LIKE '%{$search}%' ";
    $query .= " OR nm_pasien LIKE '%{$search}%'";
}




if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= 'ORDER BY nm_pasien ASC ';
}

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



$data = array();
$i = 1;

foreach ($result as $row) {
    $nor = $row["nomor_rm"];
    $sub_array = array();
    $sub_array[] = $i;
    $sub_array[] = $row['no_identitas'];
    $sub_array[] = $row['nm_pasien'];
    $sub_array[] = $row['jns_kelamin'];
    $sub_array[] = $row['alamat'];
    $sub_array[] = "<button type='button' name='pilih' class='btn btn-primary btn-xs pilih' id='{$nor}'>Pilih</button>";
    $data[] = $sub_array;
    $i++;
}

function count_all_data($connect)
{
    $query = "SELECT * FROM pasien";
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
