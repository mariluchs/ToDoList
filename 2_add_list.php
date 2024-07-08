<?php
session_start();
include "0_database_connection.php";

$messages = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $list_name = $_POST['list_name'];
    $date = date("Y-m-d");

    if (!empty($list_name)) {
        $newList = $conn->prepare("INSERT INTO lists (name, created_at) VALUES (?, ?)");
        $newList->bind_param("ss", $list_name, $date);

        if ($newList->execute()) {
            $messages = "Die Liste '$list_name' wurde erfolgreich erstellt!";
        } else {
            $messages = "Fehler beim Erstellen der Liste: " . $conn->error;
        }

        $newList->close();
    } else {
        $messages = "Geben Sie einen Titel ein!";
    }
}

$_SESSION["message"] = $messages;
$conn->close();

header("Location: 1_list_overview.php");
exit();
?>
