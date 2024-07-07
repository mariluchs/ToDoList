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
    </style>
</head>
<body>

    <?php
    include "../be_database_connection.php";

    if (!empty($_GET['list_id'])) {
        $list_id = ($_GET['list_id']); 

        // Holen des Listennamens
        $sql_list = "SELECT name FROM lists WHERE id = ?";
        $stmt_list = $conn->prepare($sql_list);
        $stmt_list->bind_param("i", $list_id);
        $stmt_list->execute();
        $result_list = $stmt_list->get_result();

        if ($result_list !== false && $result_list->num_rows > 0) {
            $row_list = $result_list->fetch_assoc();
            echo "<h1>" . htmlspecialchars($row_list['name'], ENT_QUOTES, 'UTF-8') . "</h1>";
        } else {
            echo "<p style='text-align: center;'>Liste nicht gefunden</p>";
            exit; // Beenden, wenn die Liste nicht gefunden wird
        }

        $stmt_list->close();

        // Holen der Aufgaben für die Liste
        $sql_tasks = "SELECT * FROM tasks WHERE list_id = ?";
        $stmt_tasks = $conn->prepare($sql_tasks);
        $stmt_tasks->bind_param("i", $list_id);
        $stmt_tasks->execute();
        $result_tasks = $stmt_tasks->get_result();

        if ($result_tasks !== false && $result_tasks->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Name</th><th>Status</th><th>Erledigt am</th><th>Erledigt</th><th>Aktionen</th></tr>";
            while ($row_tasks = $result_tasks->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row_tasks['name'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . htmlspecialchars($row_tasks['status'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . htmlspecialchars($row_tasks['done_at'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td><input type='checkbox' name='task_checkbox[]' value='" . htmlspecialchars($row_tasks['id'], ENT_QUOTES, 'UTF-8') . "'></td>";
                echo "<td><a href='6_delete_task.php?id=" . htmlspecialchars($row_tasks['id'], ENT_QUOTES, 'UTF-8') . "&list_id=" . $list_id . "'>Löschen</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='text-align: center;'>Keine Aufgaben gefunden</p>";
        }

        $stmt_tasks->close();
    } else {
        echo "<p style='text-align: center;'>Keine Liste ausgewählt</p>";
    }

    $conn->close();
    ?>

    <div class="addTask"> <!-- add a task -->
        <form action="5_add_task.php" method="POST">
            <input type="text" name="task_name" placeholder="Neue Aufgabe hinzufügen..." required>
            <input type="hidden" name="list_id" value="<?php echo $list_id; ?>"> <!-- list_id wird hier eingefügt -->
            <button type="submit">Hinzufügen</button>
        </form>
    </div>
    
</body>
</html>