<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lists";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    echo "Verbindung fehlgeschlagen: " . mysqli_connect_error();
}
?>
