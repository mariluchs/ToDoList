<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Details zur Liste</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .addTask {
            width: 80%;
            margin: 20px auto;
            text-align: center;
        }

        .addTask input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .addTask button {
            padding: 10px 20px;
            border: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        .addTask button:hover {
            background-color: #45a049;
        }

        .backButton {
            display: block;
            width: 80%;
            margin: 20px auto;
            text-align: center;
        }

        .backButton a {
            text-decoration: none;
            color: black;
        }

        .backButton button {
            padding: 10px 20px;
            border: none;
            background-color: #f1f1f1;
            color: black;
            border-radius: 4px;
            cursor: pointer;
        }

        .backButton button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <?php
    include "0_database_connection.php";

    //Die list_id wird aus der URL übergeben
    if (!empty($_GET['list_id'])) {
        $list_id = intval($_GET['list_id']); 

        // Der Listenname wird abgerufen
        $sql_list = "SELECT name FROM lists WHERE id = ?";
        $stmt_list = $conn->prepare($sql_list);
        $stmt_list->bind_param("i", $list_id);
        $stmt_list->execute();
        $result_list = $stmt_list->get_result();

        // Button zurück zur Listenübersicht
        echo "<div class='backButton'><button><a href='1_list_overview.php'>Zurück zur Listenübersicht</a></button></div>";

        // Wenn die Liste gefunden wird, wird der Name als Überschrift ausgegeben, ansonsten wird eine Fehlermeldung ausgegeben
        if ($result_list !== false && $result_list->num_rows > 0) {
            $row_list = $result_list->fetch_assoc();
            echo "<h1>" . htmlspecialchars($row_list['name'], ENT_QUOTES, 'UTF-8') . "</h1>";
        } else {
            echo "<p style='text-align: center;'>Liste nicht gefunden</p>";
        }

        $stmt_list->close();
    ?>

    <div class="addTask"> <!-- Formular um einen Task hinzuzufügen -->
        <form action="5_add_task.php" method="POST">
            <input type="text" name="task_name" placeholder="Neue Aufgabe hinzufügen..." required>
            <input type="hidden" name="list_id" value="<?php echo htmlspecialchars($list_id, ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit">Hinzufügen</button>
        </form>
    </div>

    <?php
        // Es werden alle Aufgaben der Liste abgerufen
        $sql_tasks = "SELECT * FROM tasks WHERE list_id = ?";
        $stmt_tasks = $conn->prepare($sql_tasks);
        $stmt_tasks->bind_param("i", $list_id);
        $stmt_tasks->execute();
        $result_tasks = $stmt_tasks->get_result();

        // Wenn Aufgaben gefunden werden, werden sie in einer Tabelle ausgegeben, ansonsten wird eine Meldung ausgegeben
        if ($result_tasks !== false && $result_tasks->num_rows > 0) {
            echo "<form action='7_check_task.php' method='POST'>";
            echo "<table>";
            echo "<tr><th>Name</th><th>Status</th><th>Erledigt am</th><th>Erledigt</th><th>Aktionen</th></tr>";
            while ($row_tasks = $result_tasks->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row_tasks['name'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . htmlspecialchars($row_tasks['status'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . htmlspecialchars($row_tasks['done_at'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td><button name='task_id' value='" . htmlspecialchars($row_tasks['id'], ENT_QUOTES, 'UTF-8') . "'>Erledigt</button></td>";
                echo "<td><a href='6_delete_task.php?id=" . htmlspecialchars($row_tasks['id'], ENT_QUOTES, 'UTF-8') . "&list_id=" . $list_id . "'>Löschen</a></td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<input type='hidden' name='list_id' value='" . htmlspecialchars($list_id, ENT_QUOTES, 'UTF-8') . "'>";
            echo "</form>";
        } else {
            echo "<p style='text-align: center;'>Keine Aufgaben gefunden</p>";
        }

        $stmt_tasks->close();
    }

    $conn->close();
    ?>

    
    
</body>
</html>
