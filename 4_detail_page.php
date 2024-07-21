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

        .input-box {
            position: relative;
            max-width: 560px;
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            box-shadow: 0 5px 10px rgba(0,0,0,0.2);
            margin: 0 auto; /* Center the box */
            margin-bottom: 20px; /* Space between form and table */
        }

        .input-box input {
            width: 100%;
            outline: none;
            border: 2px solid rgba(0,0,0,0.2);
            border-radius: 6px;
            padding: 10px; /* Reduced padding for shorter height */
            font-size: 15px;
            font-weight: 500;
            caret-color: rgb(197, 193, 193);
            box-sizing: border-box; /* Ensure padding and border are included in the width */
            margin-bottom: 10px; /* Space between input and button */
            height: 40px; /* Set a fixed height if desired */
        }

        .input-box input:focus,
        .input-box input:valid {
            border-color: rgb(197, 193, 193);
        }

        .input-box input::placeholder {
            font-size: 15px;
            font-weight: 500;
        }

        .input-box .title {
            margin-bottom: 10px; /* Space between title and input field */
            display: block; /* Ensure the title is displayed as a block element */
            color: rgba(162,135,235);
        }

        .input-box .character {
            display: flex;
            justify-content: flex-end; /* Align the counter to the right */
            align-items: center;
            color: rgba(162,135,235);
            margin-top: 1px; /* Space between input field and character counter */
        }

        .input-box.error .title {
            color: red;
        }

        .input-box.error .character {
            color: red;
        }

        .input-box.error input {
            border-color: red;
            color: red;
        }

        .input-box button {
            display: block;
            margin: 0 auto;
            padding: 10px 20px;
            background-color: rgba(162,135,235);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .input-box button:hover {
            background-color: rgba(162,135,235, 0.8);
        }

        .error-message {
            display: none; /* Hide the message by default */
            color: red;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
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
        
        // Button zurück zur Listenübersicht
        echo "<div class='backButton'><button onclick=\"window.location.href='1_list_overview.php'\">Zurück zur Listenübersicht</button></div>";



        // Wenn die Liste gefunden wird, wird der Name als Überschrift ausgegeben, ansonsten wird eine Fehlermeldung ausgegeben
        if ($result_list !== false && $result_list->num_rows > 0) {
            $row_list = $result_list->fetch_assoc();
            echo "<h1>" . htmlspecialchars($row_list['name'], ENT_QUOTES, 'UTF-8') . "</h1>";
        } else {
            echo "<p style='text-align: center;'>Liste nicht gefunden</p>";
        }

        $stmt_list->close();
    ?>

    <div class="input-box">
        <form action="5_add_task.php" method="POST">
            <span class="title">Neue Aufgabe</span>
            <input type="text" name="task_name" placeholder="Neue Aufgabe hinzufügen..." required maxlength="50">
            <input type="hidden" name="list_id" value="<?php echo htmlspecialchars($list_id, ENT_QUOTES, 'UTF-8'); ?>">
            <div class="character">
                <span class="signal-num">0</span>
                <span class="limit-num">/50</span>
            </div>
            <p class="error-message">Achtung! Zeichenlimit erreicht (50).</p> <!-- Message for max length -->
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputBox = document.querySelector('.input-box');
            const inputField = inputBox.querySelector("input[name='task_name']");
            const signalNum = inputBox.querySelector(".signal-num");
            const errorMessage = inputBox.querySelector(".error-message");

            inputField.addEventListener("input", () => {
                let valLength = inputField.value.length;
                signalNum.innerText = valLength;

                if (valLength >= 50) {
                    inputBox.classList.add("error");
                    errorMessage.style.display = "block"; // Show the error message
                } else {
                    inputBox.classList.remove("error");
                    errorMessage.style.display = "none"; // Hide the error message
                }
            });
        });
    </script>
    
</body>
</html>