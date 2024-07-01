<?php
include "be_database_connection.php";

$sql = "
    SELECT lists.id, lists.name, lists.created_at
    FROM lists
    ORDER BY lists.id DESC";
$result = $conn->query($sql);

if ($result !== false && $result->num_rows > 0) {
    echo "<table id='table_toDoList'>";
    echo "<tr><th>Liste-Nr.</th><th>Titel</th><th>Erstellungsdatum</th><th>Aktionen</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["created_at"] . "</td>";
        echo "<td><a href='be_list_delete.php?id=" . $row['id'] . "id" . $row['id'] . "'>Löschen</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Erstellen Sie eine neue Liste!";
}
?>
