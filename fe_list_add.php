<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Neue Liste</title>
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
    </style>
</head> 
<body>

    <h1>Task Masters</h1>
    <h2>Neue To-Do-Liste</h2>
        
    <div class="messages">
        <?php
            if (isset($_SESSION["message"])) {
                echo '<p>' . $_SESSION["message"] . '</p>';
                unset($_SESSION["message"]); // remove it after displaying
            }
            ?>
    </div>

    <div class="form-container-addList">
        <form action="be_list_add.php" method="POST">
            <input type="text" id="list_name" name="list_name" placeholder="Titel" >
        </form>
        <button class="button" id="button_list_overview"onclick="window.location.href='fe_list_overview.php' ">Übersicht</button>
    </div>  

</body>
</html>