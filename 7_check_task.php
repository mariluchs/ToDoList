<?php
session_start();
include "0_database_connection.php";

// Die ID der Aufgabe und der Liste wird aus dem Formular in 4_detail_page.php übergeben
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

            //Wenn der Status der Aufgabe 'Done' ist, wird er auf 'ToDo' gesetzt und umgekehrt
            if ($current_status == "Done") {
                $updateToDo = $conn->prepare("UPDATE tasks SET status = 'ToDo', done_at = NULL WHERE id = ?");
                if ($updateToDo) {
                    $updateToDo->bind_param("i", $task_id);
                    $updateToDo->execute();
                    $updateToDo->close();
                }
            } else {
                // Status auf 'Done' ändern und done_at setzen
                $updateDone = $conn->prepare("UPDATE tasks SET status = 'Done', done_at = NOW() WHERE id = ?");
                if ($updateDone) {
                    $updateDone->bind_param("i", $task_id);
                    $updateDone->execute();
                    $updateDone->close();
                }
            }
        }
    } else {
        echo "Keine Aufgabe oder Liste ausgewählt";
    }
}

// Nach der Aktualisierung zurück zur Liste leiten
header("Location: 4_detail_page.php?list_id=" . $list_id);

$conn->close();
?>
