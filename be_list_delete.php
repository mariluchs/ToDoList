<?php

    include "be_database_connection.php";

    if (isset($_GET['id'])) {
        $list_id = $_GET['id'];

        $sql = "DELETE FROM lists WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $list_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION["message"] = "Liste erfolgreich gelöscht";
        } else {
            $_SESSION["message"] = "Fehler beim Löschen der Liste";
        }

        $stmt->close();
    }	

    header("Location: fe_list_overview.php");
?>