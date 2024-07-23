<?php
session_start();
include "0_database_connection.php";

// Erlaubte Zeichen definieren
$allowed_chars = "/^[a-zA-Z0-9?!,() -]*$/";

// Aufgaben Name und Listen-ID werden aus Formular von 4_detail_page übergeben
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['task_name']) && !empty($_POST['list_id'])) {
        $task_name = $_POST['task_name'];
        $list_id = intval($_POST['list_id']);

        if (preg_match($allowed_chars, $task_name)) {
            // Überprüfung ob Länge <= 50 Zeichen
            if (strlen($task_name) <= 50) {
                // Überprüfung ob Task-Name bereits existiert
                $sql_check = "SELECT COUNT(*) FROM tasks WHERE name = ? AND list_id = ?";
                $stmt_check = $conn->prepare($sql_check);
                $stmt_check->bind_param("si", $task_name, $list_id);
                $stmt_check->execute();
                $stmt_check->bind_result($count);
                $stmt_check->fetch();
                $stmt_check->close();

                if ($count > 0) {
                    // Fehlermeldung: Task existiert bereits
                    $_SESSION['error'] = 'duplicate_task';
                } else {
                    // Neuen Task erstellen
                    $sql_insert = "INSERT INTO tasks (name, status, list_id) VALUES (?, 'ToDo', ?)";
                    $stmt_insert = $conn->prepare($sql_insert);
                    $stmt_insert->bind_param("si", $task_name, $list_id);
                    $stmt_insert->execute();
                    $stmt_insert->close();

                    // Anzahl der Tasks um 1 erhöhen
                    $sql_update = "UPDATE lists SET number_of_tasks = number_of_tasks + 1 WHERE id = ?";
                    $stmt_update = $conn->prepare($sql_update);
                    $stmt_update->bind_param("i", $list_id);
                    $stmt_update->execute();
                    $stmt_update->close();
                    
                    // Weiterleitung zur Detailseite
                    header("Location: 4_detail_page.php?list_id=" . $list_id);
                    exit();
                }
            } else {
                $_SESSION['error'] = 'length_exceeded';
            }
        } else {
            $_SESSION['error'] = 'invalid_symbol';
        }
    } else {
        $_SESSION['error'] = 'empty_task';
    }

    // Weiterleitung zur Detailseite mit Fehlermeldung
    header("Location: 4_detail_page.php?list_id=" . $list_id);
    exit();
}

$conn->close();
?>
