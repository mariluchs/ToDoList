<?php
session_start();
include "be_database_connection.php"; // Verbinde mit der Datenbank

$messages = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $list_name = $_POST['list_name'];
}

        if (!empty($list_name)) {

            $newList = $conn->prepare("INSERT INTO lists (name) VALUES (?)");
            $newList->bind_param("s", $list_name);

            if ($newList->execute()) {

                $messages = "Die Liste $list_name wurde erfolgreich erstellt!";

            } else {

                $messages = "Fehler beim Erstellen der Liste: " . $conn->error;
            }
        } else {

            $messages = "Geben Sie einen Titel ein!";
        }

$_SESSION["message"] = $messages;

$conn->close();

header("Location: fe_list_add.php");
exit();
?>
