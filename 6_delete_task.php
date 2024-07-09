<?php
session_start();

include "0_database_connection.php";

// Die ID der Aufgabe und der Liste wird aus der URL übergeben
if (isset($_GET['id']) && isset($_GET['list_id'])) {
    $task_id = intval($_GET['id']);
    $list_id = intval($_GET['list_id']);

    // Überprüfen, ob die Aufgabe existiert und gleichzeitig den Namen der Aufgabe abrufen
    $names = $conn->prepare("SELECT name FROM tasks WHERE id = ?");
    $names->bind_param("i", $task_id);
    $names->execute();
    $result = $names->get_result();

    // Wenn die Aufgabe existiert, wird sie gelöscht
    if ($result->num_rows > 0) {
        $task_name = $result->fetch_assoc()["name"];
        $names->close();

        // Die Aufgabe wird gelöscht
        $sql = "DELETE FROM tasks WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $task_id);
        $stmt->execute();

        // Anzahl der tasks für die ausgewähle Liste um 1 verringern
        $sql_update = "UPDATE lists SET number_of_tasks = number_of_tasks - 1 WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $list_id);
        $stmt_update->execute();
        $stmt_update->close();
        $stmt->close();
    }
}

// Weiterleitung auf die Detailseite der Liste
header("Location: 4_detail_page.php?list_id=" . $list_id);
?>
