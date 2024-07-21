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
    margin: 20px auto; /* Add margin to create gap between input and table */
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

.input-box {
    position: relative;
    max-width: 560px;
    width: 100%;
    background: #fff;
    padding: 10px;
    border-radius: 6px;
    box-shadow: 0 5px 10px rgba(0,0,0,0.2);
    margin: 0 auto; /* Center the box */
    margin-bottom: 20px; /* Add margin to create space between form and table */
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
    box-sizing: border-box; /* Ensure padding and border are included in the width */
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
    display: block; /* Make sure the title is a block element */
    color: rgba(162,135,235);
}

.input-box .character {
    display: flex;
    justify-content: flex-end; /* Align character count to the right */
    align-items: center;
    color: rgba(162,135,235);
    margin-top: 10px; /* Space between the input field and character count */
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
    <h1>Task Masters</h1>

    <div class="input-box">
    <form action="2_add_list.php" method="POST">
        <span class="title">Neue Liste</span>
        <input type="text" name="list_name" placeholder="Neue Liste hinzufügen..." required maxlength="50">
        <div class="character">
            <span class="signal-num">0</span>
            <span class="limit-num">/50</span>
        </div>
        <p class="error-message">Achtung! Zeichenlimit erreicht (50).</p> <!-- Message for max length -->
        <button type="submit">Hinzufügen</button>
    </form>
</div>



        
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
               echo "<td><a href='3_delete_list.php?id=" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>Löschen</a></td>";
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
            errorMessage.style.display = "block"; // Show the error message
        } else {
            inputBox.classList.remove("error");
            errorMessage.style.display = "none"; // Hide the error message
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
