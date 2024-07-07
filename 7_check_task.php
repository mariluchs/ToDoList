<?php
session_start();
include "../be_database_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['task_checkbox']) && !empty($_POST['list_id'])) {
        $task_ids = $_POST['task_checkbox'];
        $list_id = intval($_POST['list_id']);

        foreach ($task_ids as $task_id) {
            $task_id = intval($task_id); // Sicherstellen, dass task_id ein Integer ist

            // Update the status of the task
            $sql_update = "UPDATE tasks SET status = 'Done', done_at = NOW() WHERE id = ? AND list_id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ii", $task_id, $list_id);
            $stmt_update->execute();
            $stmt_update->close();
        }

        $_SESSION["message"] = "Status der ausgewählten Aufgaben wurde aktualisiert";
    }
}

// Redirect zurück zur Detailseite der Liste
header("Location: 4_list_detail.php?list_id=" . intval($_POST['list_id']));
exit();
?>
