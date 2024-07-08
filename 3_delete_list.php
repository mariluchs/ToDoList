<?php
session_start();

include "0_database_connection.php";

if (isset($_GET['id'])) {
    $list_id = intval($_GET['id']);

    // Überprüfen, ob die Liste existiert
    $names = $conn->prepare("SELECT name FROM lists WHERE id = ?");
    $names->bind_param("i", $list_id);
    $names->execute();
    $result = $names->get_result();

    if ($result->num_rows > 0) {
        $list_name = $result->fetch_assoc()["name"];
        $names->close();

        // Alle Aufgaben der Liste löschen
        $sql = "DELETE FROM tasks WHERE list_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $list_id);
        $stmt->execute();
        $stmt->close();

        // Liste löschen
        $sql = "DELETE FROM lists WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $list_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION["message"] = "Die Liste '$list_name' wurde erfolgreich gelöscht";
        } else {
            $_SESSION["message"] = "Fehler beim Löschen der Liste '$list_name'";
        }

        $stmt->close();
    }
}

header("Location: 1_list_overview.php");
exit();
?>
