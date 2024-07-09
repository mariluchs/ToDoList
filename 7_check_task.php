<?php
session_start();
include "0_database_connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['task_id']) && !empty($_POST['list_id'])) {
        $list_id = intval($_POST['list_id']);
        $task_id = intval($_POST['task_id']);

        // Status der Aufgabe abrufen
        $status = $conn->prepare("SELECT status FROM tasks WHERE id = ?");
        if ($status) {
            $status->bind_param("i", $task_id);
            $status->execute();
            $result = $status->get_result();
            $current_status = $result->fetch_assoc()['status'];
            $status->close();

            if ($current_status == "Done") {
                // Status auf 'ToDo' ändern und done_at zurücksetzen
                $updateToDo = $conn->prepare("UPDATE tasks SET status = 'ToDo', done_at = NULL WHERE id = ?");
                if ($updateToDo) {
                    $updateToDo->bind_param("i", $task_id);
                    $updateToDo->execute();
                    $updateToDo->close();
                } else {
                    echo "Fehler bei der Vorbereitung der SQL-Abfrage für ToDo: " . $conn->error;
                }
            } else {
                // Status auf 'Done' ändern und done_at setzen
                $updateDone = $conn->prepare("UPDATE tasks SET status = 'Done', done_at = NOW() WHERE id = ?");
                if ($updateDone) {
                    $updateDone->bind_param("i", $task_id);
                    $updateDone->execute();
                    $updateDone->close();
                } else {
                    echo "Fehler bei der Vorbereitung der SQL-Abfrage für Done: " . $conn->error;
                }
            }

            // Nach der Aktualisierung zurück zur Liste leiten
            header("Location: 4_detail_page.php?list_id=" . $list_id);
            exit;
        } else {
            echo "Fehler bei der Vorbereitung der SQL-Abfrage für Status: " . $conn->error;
        }
    } else {
        echo "Keine Aufgabe oder Liste ausgewählt";
    }
}

$conn->close();
?>
