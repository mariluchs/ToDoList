<?php
include "be_database_connection.php";

if (isset($_GET['id'])) {
    $list_id = $_GET['id'];

    $sql = "SELECT * FROM lists WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $list_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<h1>Details der Liste: " . $row['name'] . "</h1>";
        echo "<p>Erstellungsdatum: " . $row['created_at'] . "</p>";
        // Hier kannst du weitere Details der Liste anzeigen
    } else {
        echo "Liste nicht gefunden";
    }

    $stmt->close();
} else {
    echo "Keine Liste ausgewählt";
}

$conn->close();
?>
