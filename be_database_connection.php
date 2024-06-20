<?php
$servername = "sql7.freesqldatabase.com";
$username = "sql7714935";
$password = "CMIIKkDGqi";
$dbname = "sql7714935";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    echo "Verbindung fehlgeschlagen: " . mysqli_connect_error();
}
?>
