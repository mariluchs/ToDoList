<?php
session_start();

include "0_database_connection.php";

// Die ID der Liste wird aus der URL übergeben
if (isset($_GET['id'])) {
    $list_id = intval($_GET['id']);

    // Es wird überprüft, ob die Liste existiert und gleichzeitig der Name der Liste abgerufen
    $names = $conn->prepare("SELECT name FROM lists WHERE id = ?");
    $names->bind_param("i", $list_id);
    $names->execute();
    $result = $names->get_result();

    // Wenn die Liste existiert, wird überprüft, ob alle Aufgaben erledigt sind
    if ($result->num_rows > 0) {
        $list_name = $result->fetch_assoc()["name"];
        $names->close();

        // Überprüfen, ob alle Aufgaben den Status "Done" haben
        $check_tasks = $conn->prepare("SELECT COUNT(*) AS incomplete_tasks FROM tasks WHERE list_id = ? AND status != 'Done'");
        $check_tasks->bind_param("i", $list_id);
        $check_tasks->execute();
        $tasks_result = $check_tasks->get_result();
        $incomplete_tasks = $tasks_result->fetch_assoc()["incomplete_tasks"];
        $check_tasks->close();

        // Wenn alle Aufgaben erledigt sind, kann die Liste gelöscht werden
        if ($incomplete_tasks == 0) {
            // Zuerst werden alle Aufgaben der Liste gelöscht
            $sql = "DELETE FROM tasks WHERE list_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $list_id);
            $stmt->execute();
            $stmt->close();

            // Anschließend wird die Liste gelöscht
            $sql = "DELETE FROM lists WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $list_id);
            $stmt->execute();
            $stmt->close();

            // Weiterleitung auf die Listenübersicht
            header("Location: 1_list_overview.php");
            exit();
        } else {
            // Weiterleitung zurück mit einer Fehlermeldung, wenn nicht alle Aufgaben erledigt sind
            $_SESSION['delete_error'] = "Die Liste kann nicht gelöscht werden, da noch nicht alle Aufgaben erledigt sind.";
            header("Location: 1_list_overview.php");
            exit();
        }
    } else {
        $_SESSION['delete_error'] = "Die Liste existiert nicht.";
        header("Location: 1_list_overview.php");
        exit();
    }
} else {
    $_SESSION['delete_error'] = "Keine Liste ausgewählt.";
    header("Location: 1_list_overview.php");
    exit();
}
?>
