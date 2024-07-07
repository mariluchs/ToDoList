<?php

session_start();

include "../be_database_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_name = $_POST['task_name'];
    $list_id = $_POST['list_id'];
    $status = "ToDo";

    if (!empty($task_name)) {

        $newTask = $conn->prepare("INSERT INTO tasks (name, list_id, status) VALUES (?, ?, ?)");
        
        if ($newTask) {
            $newTask->bind_param("sss", $task_name, $list_id, $status);
            $newTask->execute(); // Add this line to execute the prepared statement
        } else {
            // Handle query preparation error
            echo "Error preparing SQL query: " . $conn->error;
            exit;
        }

    } else {

        $_SESSION["message"] = "Geben Sie einen Titel ein!";    
    }
}
else {
    // Handle missing POST parameters
    echo "Missing POST parameters";
    exit;

}



header ("Location: 1_list_overview.php"); // Vorläufige Weiterleitung
// header("Location: _list_detail.php?list_id=" . $list_id); --> individuelle Weiterleitung
?>
