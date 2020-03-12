<?php
include '../library/inc.connection.php';
include_once "../library/inc.library.php";


if (isset($_POST['send'])) {
}

if ($_POST['send'] == "add") {
        $kode_stok = buatKode("stok_obat", "ST");

        $kode_obat = $_POST['id_obat'];
        $tanggal = $_POST['tanggal'];
        $jumlah = $_POST['jumlah'];
        $type = $_POST['jenis'];

        $mySql        = "INSERT INTO stok_obat (no_stok, kd_obat, tanggal, jumlah, jenis) 
                VALUES ('$kode_stok',
                        '$kode_obat',
                        '$tanggal',
                        '$jumlah',
                        '$type')";
        $myQry        = mysqli_query($koneksidb, $mySql);



        echo $mySql;
} else if ($_POST['send'] == "view") {
        $kode_stok = $_POST['no_obat'];
        $mySql        = "SELECT * FROM stok_obat WHERE no_stok = '$kode_stok'";
        $myQry        = mysqli_query($koneksidb, $mySql);

        echo json_encode(mysqli_fetch_array($myQry));
} else if ($_POST['send'] == "edit") {
        $kode_stok = $_POST['no_obat'];
        $tanggal = $_POST['tanggal'];
        $jumlah = $_POST['jumlah'];
        $mySql        = "UPDATE stok_obat SET tanggal	= '$tanggal',
							
                                        jumlah		= '$jumlah'
					
                                        WHERE no_stok ='" . $kode_stok . "'";

        $myQry = mysqli_query($koneksidb, $mySql) or die("Eror hapus data" . mysqli_error($myQry));
        if ($myQry) {
                // Refresh halaman

                echo $mySql;
        }
}
