<?php
session_start();

include "0_database_connection.php";

if (isset($_GET['id']) && isset($_GET['list_id'])) {
    $task_id = intval($_GET['id']);
    $list_id = intval($_GET['list_id']);

    // Überprüfen, ob die Aufgabe existiert
    $names = $conn->prepare("SELECT name FROM tasks WHERE id = ?");
    $names->bind_param("i", $task_id);
    $names->execute();
    $result = $names->get_result();

    if ($result->num_rows > 0) {
        $task_name = $result->fetch_assoc()["name"];
        $names->close();

        // Aufgabe löschen
        $sql = "DELETE FROM tasks WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $task_id);
        $stmt->execute();

        // Number of Tasks für Liste verringern
        $sql_update = "UPDATE lists SET number_of_tasks = number_of_tasks - 1 WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $list_id);
        $stmt_update->execute();
        $stmt_update->close();

        if ($stmt->affected_rows > 0) {
            $_SESSION["message"] = "Die Aufgabe '$task_name' wurde erfolgreich gelöscht";
        } else {
            $_SESSION["message"] = "Fehler beim Löschen der Aufgabe '$task_name'";
        }

        $stmt->close();
    }
}

//header ("Location: 1_list_overview.php"); // Vorläufige Weiterleitung
header("Location: 4_detail_page.php?list_id=" . $list_id); // --> individuelle Weiterleitung
?>
