<?php
session_start();
?>

<!DOCTYPE html> 
<html>
<head>
    <title>Übersicht To-Do-Listen</title>
    <style>

        h1 {
            text-align: center;
        }

        h2 {
            text-align: center;
        }

        .form-container-addList {
            text-align: center;
        }

        .form-container-addList input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid ;
            border-radius: 5px;
            margin: 20px 0;
            width: 30%;
            text-align: center;
        }

        .messages{
            text-align: center;
        }

        .button {
            padding: 15px 30px;
            font-size: 18px;
            background-color: #633ed5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 20px 0;
        }

        #table_toDoList {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-family: Arial, sans-serif;
        }

        #table_toDoList th {
            background-color: #633ed5;
            color: white;
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }

        #table_toDoList td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        #table_toDoList tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #table_toDoList tr:hover {
            background-color: #ddd;
        }

        #table_toDoList th {
            background-color: #633ed5;
            color: white;
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }

        #table_toDoList td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        #table_toDoList {
            border-collapse: collapse;
            width: 100%;
        }

        #table_toDoList caption {
            font-size: 1.5em;
            margin: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    
    <h1>Task Masters</h1>
    

    <div class="form-container-addList">
        <form action="be_list_add.php" method="POST">
            <input type="text" id="list_name" name="list_name" placeholder="Neue Liste hinzfügen..." >
        </form>
    </div>  
    
    <div class="messages">
        <?php
            if (isset($_SESSION["message"])) {
                echo '<p>' . $_SESSION["message"] . '</p>';
                unset($_SESSION["message"]); // remove it after displaying
            }
            ?>
    </div>

    <div>
    <?php
        include "be_list_overview.php";
    ?>
    </div>
            
</body>
</html>
