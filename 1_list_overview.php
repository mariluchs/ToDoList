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

        .clickable-row {
            cursor: pointer;
        }

        .clickable-row a {
            text-decoration: none;
        }

        .clickable-row .edit-link {
            color: blue;
        }

        .clickable-row .delete-link {
            color: red;
        }

        .input-box {
            position: relative;
            max-width: 560px;
            width: 100%;
            background: #fff;
            padding: 10px;
            border-radius: 6px;
            box-shadow: 0 5px 10px rgba(0,0,0,0.2);
            margin: 0 auto;
            margin-bottom: 20px;
        }

        .input-box input {
            width: 100%;
            outline: none;
            border: 2px solid rgba(0,0,0,0.2);
            border-radius: 6px;
            padding: 15px;
            font-size: 15px;
            font-weight: 500;
            caret-color: rgb(197, 193, 193);
            box-sizing: border-box;
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
            margin-bottom: 10px;
            display: block;
            color: rgba(162,135,235);
        }

        .input-box .character {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            color: rgba(162,135,235);
            margin-top: 10px;
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

        .error-message, .invalid-symbol-message, .duplicate-name-message, .delete-error-message, .nonexistent-list-error-message, .no-list-selected-error-message {
            display: none; /* Hide by default */
            color: red;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Task Masters</h1>

    <div class="input-box">
        <form action="2_add_list.php" method="POST">
            <span class="title">Neue Liste</span>
            <input type="text" name="list_name" placeholder="Neue Liste hinzufügen..." required maxlength="50">
            <div class="character">
                <span class="signal-num">0</span>
                <span class="limit-num">/50</span>
            </div>
            <p class="error-message">Achtung! Zeichenlimit erreicht (50).</p>
            <p class="invalid-symbol-message">Ungültige Zeichen verwendet. Erlaubt sind nur alphanumerische Zeichen und ?! , ( ) -.</p>
            <p class="duplicate-name-message">Eine Liste mit diesem Namen existiert bereits.</p>
            <p class="delete-error-message">Die Liste kann nicht gelöscht werden, da noch nicht alle Aufgaben erledigt sind.</p>
            <p class="nonexistent-list-error-message">Die Liste existiert nicht.</p>
            <p class="no-list-selected-error-message">Keine Liste ausgewählt.</p>
            <button type="submit">Hinzufügen</button>
        </form>
    </div>

    <?php if (isset($_SESSION['error']) || isset($_SESSION['delete_error'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Fehlerbehandlung
                <?php if (isset($_SESSION['error'])): ?>
                    const errorType = "<?php echo $_SESSION['error']; ?>";
                    const errorMessages = {
                        "duplicate_name": ".duplicate-name-message",
                        "invalid_symbol": ".invalid-symbol-message",
                        "empty_title": ".error-message"
                    };
                    const messageClass = errorMessages[errorType];
                    if (messageClass) {
                        const messageElement = document.querySelector(messageClass);
                        if (messageElement) {
                            messageElement.style.display = 'block';
                            setTimeout(() => {
                                messageElement.style.display = 'none';
                            }, 5000); 
                        }
                    }
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                // Handle deletion errors
                <?php if (isset($_SESSION['delete_error'])): ?>
                    const deleteErrorType = "<?php echo $_SESSION['delete_error']; ?>";
                    const deleteErrorMessages = {
                        "Die Liste kann nicht gelöscht werden, da noch nicht alle Aufgaben erledigt sind.": ".delete-error-message",
                        "Die Liste existiert nicht.": ".nonexistent-list-error-message",
                        "Keine Liste ausgewählt.": ".no-list-selected-error-message"
                    };
                    const deleteMessageClass = deleteErrorMessages[deleteErrorType];
                    if (deleteMessageClass) {
                        const deleteMessageElement = document.querySelector(deleteMessageClass);
                        if (deleteMessageElement) {
                            deleteMessageElement.style.display = 'block';
                            setTimeout(() => {
                                deleteMessageElement.style.display = 'none';
                            }, 5000); 
                        }
                    }
                    <?php unset($_SESSION['delete_error']); ?>
                <?php endif; ?>
            });
        </script>
    <?php endif; ?>

    <?php
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
               echo "<td><a class='delete-link' href='3_delete_list.php?id=" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>Löschen</a> | <a class='edit-link' href='9_edit_list.php?id=" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>Bearbeiten</a></td>";
               echo "</tr>";
           }
           echo "</table>";
       } else {
           echo "<p style='text-align: center;'>Erstellen Sie eine neue Liste!</p>";
       }
    ?>

    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const inputBox = document.querySelector(".input-box");
        const inputField = inputBox.querySelector("input[name='list_name']");
        const signalNum = inputBox.querySelector(".signal-num");
        const errorMessage = inputBox.querySelector(".error-message");

        inputField.addEventListener("keyup", () => {
            let valLength = inputField.value.length;
            signalNum.innerText = valLength;

            if (valLength >= 50) {
                inputBox.classList.add("error");
                errorMessage.style.display = "block"; // Fehlermeldung sichtbar
            } else {
                inputBox.classList.remove("error");
                errorMessage.style.display = "none"; // Fehlermeldung nicht sichtbar
            }
        });

        const rows = document.querySelectorAll('.clickable-row');
        rows.forEach(row => {
            row.addEventListener('click', (event) => {
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
