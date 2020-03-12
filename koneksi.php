
<?php
# Konek ke Web Server Lokal
$myHost  = "localhost";
$myUser  = "root";
$myPass  = "";
$myDbs  = "klinik-apotekdb"; // nama database, disesuaikan dengan database di MySQL

# Konek ke Web Server Lokal
$koneksidb  = mysqli_connect($myHost, $myUser, $myPass, $myDbs);
if (!$koneksidb) {
  echo "Failed Connection !";
}
