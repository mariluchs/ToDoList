<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Overview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        .form-container-addList {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-container-addList input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        table {
            width: 80%;
            margin: 0 auto;
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

        .clickable-row {
            cursor: pointer;
        }

        .clickable-row a {
            color: red;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1>Task Masters</h1>

    <div class="form-container-addList"> <!-- form to add a new list -->
        <form action="2_add_list.php" method="POST">
            <input type="text" name="list_name" placeholder="Neue Liste hinzufügen...">
        </form>
    </div>
        
    <?php // table with lists
       include "0_database_connection.php";

       $sql = "SELECT lists.id, lists.name, lists.number_of_tasks, lists.created_at FROM lists ORDER BY lists.id DESC";
       $result = $conn->query($sql);
       
       if ($result !== false && $result->num_rows > 0) {
           echo "<table id='table_toDoList'>";
           echo "<tr><th>Liste-Nr.</th><th>Titel</th><th>Aufgaben</th><th>Erstellungsdatum</th><th>Aktionen</th></tr>";
       
           while ($row = $result->fetch_assoc()) {
               echo "<tr class='clickable-row' data-id='" . htmlspecialchars($row["id"], ENT_QUOTES, 'UTF-8') . "' data-name='" . htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8') . "'>";
               echo "<td>" . htmlspecialchars($row["id"], ENT_QUOTES, 'UTF-8') . "</td>";
               echo "<td>" . htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8') . "</td>";
               echo "<td>" . htmlspecialchars($row["number_of_tasks"], ENT_QUOTES, 'UTF-8') . "</td>";
               echo "<td>" . htmlspecialchars($row["created_at"], ENT_QUOTES, 'UTF-8') . "</td>";
               echo "<td><a href='3_delete_list.php?id=" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>Löschen</a></td>";
               echo "</tr>";
           }
           echo "</table>";
       } else {
           echo "<p style='text-align: center;'>Erstellen Sie eine neue Liste!</p>";
       }
    ?> 

    <script> // Clickable rows
    document.addEventListener('DOMContentLoaded', () => {
        const rows = document.querySelectorAll('.clickable-row');
        rows.forEach(row => {
            row.addEventListener('click', (event) => {
                // Verhindert das Navigieren beim Klicken auf den Löschen-Link
                if (event.target.tagName.toLowerCase() === 'a') {
                    return;
                }
                const id = row.getAttribute('data-id');
                window.location.href = `4_detail_page.php?list_id=${id}`;
            });
        });
    });
    </script>
</body>
</html>
