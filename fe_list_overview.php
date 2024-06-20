<!DOCTYPE html>
<html>
<head>
    <title>Übersicht To-Do-Listen</title>
    <style>
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
            cursor: pointer;
        }

        #table_toDoList caption {
            font-size: 1.5em;
            margin: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Übersicht To-Do-Listen</h1>

    <div>
        <?php
        include "be_list_overview.php";
        ?>
    </div>

</body>
</html>
