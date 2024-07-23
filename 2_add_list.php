<?php
session_start();
include "0_database_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $list_name = trim($_POST['list_name']);
    $date = date("Y-m-d");

    $allowed_chars = "/^[a-zA-Z0-9?!.,() -]*$/";

    if (!empty($list_name)) {
        if (preg_match($allowed_chars, $list_name)) {
            // Überprüfen, ob der Listennamen bereits existiert
            $sql_check = "SELECT COUNT(*) FROM lists WHERE name = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("s", $list_name);
            $stmt_check->execute();
            $stmt_check->bind_result($count);
            $stmt_check->fetch();
            $stmt_check->close();

            if ($count > 0) {
                // Fehlernachricht für doppelten Listennamen setzen
                $_SESSION['error'] = "duplicate_name";
            } else {
                // Neue Liste in die Datenbank einfügen
                $newList = $conn->prepare("INSERT INTO lists (name, created_at) VALUES (?, ?)");
                $newList->bind_param("ss", $list_name, $date);
                $newList->execute();
                $newList->close();
                // Weiterleitung zur Übersichtsseite
                header("Location: 1_list_overview.php");
                exit();
            }
        } else {
            // Fehlernachricht für ungültige Zeichen setzen
            $_SESSION['error'] = 'invalid_symbol';
        }
    } else {
        // Fehlernachricht für leeren Titel setzen
        $_SESSION['error'] = 'empty_title';
    }

    // Weiterleitung zur Übersichtsseite mit Fehlermeldung
    header("Location: 1_list_overview.php");
    exit();
}

$conn->close();
?>
