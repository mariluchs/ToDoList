<?php
session_start();
include "0_database_connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['task_name']) && !empty($_POST['list_id'])) {
        $task_name = htmlspecialchars($_POST['task_name'], ENT_QUOTES, 'UTF-8');
        $list_id = intval($_POST['list_id']);

        // Neue Aufgabe hinzufügen
        $sql_insert = "INSERT INTO tasks (name, status, list_id) VALUES (?, 'ToDo', ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ss", $task_name, $list_id);
        $stmt_insert->execute();
        $stmt_insert->close();

        // Number of Tasks für Liste erhöhen
        $sql_update = "UPDATE lists SET number_of_tasks = number_of_tasks + 1 WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $list_id);
        $stmt_update->execute();
        $stmt_update->close();

        // Nach dem Hinzufügen zurück zur Liste leiten
        header("Location: 4_detail_page.php?list_id=" . $list_id);
        exit;
    } else {
        echo "Fehlende Daten für das Hinzufügen einer Aufgabe";
    }
}

$conn->close();
?>
