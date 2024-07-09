<?php
session_start();

include "0_database_connection.php";

// Die ID der Liste wird aus der URL übergeben
if (isset($_GET['id'])) {
    $list_id = intval($_GET['id']);

    // Es wird überprüft ob die Liste existiert und gleichzeitig der Name der Liste abgerufen
    $names = $conn->prepare("SELECT name FROM lists WHERE id = ?");
    $names->bind_param("i", $list_id);
    $names->execute();
    $result = $names->get_result();

    // Wenn die Liste existiert, wird sie gelöscht
    if ($result->num_rows > 0) {
        $list_name = $result->fetch_assoc()["name"];
        $names->close();

        // Zuerst werden noch alle Aufgaben der Liste löschen
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
    }
}

//Weiterleitung auf die Listenübersicht
header("Location: 1_list_overview.php");
exit();
?>
