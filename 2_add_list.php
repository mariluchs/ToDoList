<?php
session_start();
include "0_database_connection.php";

$messages = "";

// Die Variable list_name wird aus dem Formular in 1_list_overview.php übergeben, das Datum wird neu erzeugt
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $list_name = $_POST['list_name'];
    $date = date("Y-m-d");

    // Wenn der Titel nicht leer ist, wird die Liste erstellt
    if (!empty($list_name)) {
        $newList = $conn->prepare("INSERT INTO lists (name, created_at) VALUES (?, ?)");
        $newList->bind_param("ss", $list_name, $date);
        $newList->execute();
        $newList->close();
    }
}

$conn->close();

// Weiterleitung auf die Listenübersicht
header("Location: 1_list_overview.php");
exit();
?>
