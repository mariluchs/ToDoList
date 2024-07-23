<?php
session_start();
include "0_database_connection.php";

// Define allowed characters for task names
$allowed_chars = "/^[a-zA-Z0-9?!,() -]*$/";

// The task_name and list_id are passed from the form in 4_detail_page.php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['task_name']) && !empty($_POST['list_id'])) {
        $task_name = $_POST['task_name'];
        $list_id = intval($_POST['list_id']);

        // Validate the task name
        if (preg_match($allowed_chars, $task_name)) {
            // Check if the length is within the limit
            if (strlen($task_name) <= 50) {
                // Check if the task name already exists in the list
                $sql_check = "SELECT COUNT(*) FROM tasks WHERE name = ? AND list_id = ?";
                $stmt_check = $conn->prepare($sql_check);
                $stmt_check->bind_param("si", $task_name, $list_id);
                $stmt_check->execute();
                $stmt_check->bind_result($count);
                $stmt_check->fetch();
                $stmt_check->close();

                if ($count > 0) {
                    // Error message for duplicate task names
                    $_SESSION['error'] = 'duplicate_task';
                } else {
                    // Insert new task
                    $sql_insert = "INSERT INTO tasks (name, status, list_id) VALUES (?, 'ToDo', ?)";
                    $stmt_insert = $conn->prepare($sql_insert);
                    $stmt_insert->bind_param("si", $task_name, $list_id);
                    $stmt_insert->execute();
                    $stmt_insert->close();

                    // Increment the task count for the list
                    $sql_update = "UPDATE lists SET number_of_tasks = number_of_tasks + 1 WHERE id = ?";
                    $stmt_update = $conn->prepare($sql_update);
                    $stmt_update->bind_param("i", $list_id);
                    $stmt_update->execute();
                    $stmt_update->close();
                    
                    // Redirect to the detail page
                    header("Location: 4_detail_page.php?list_id=" . $list_id);
                    exit();
                }
            } else {
                $_SESSION['error'] = 'length_exceeded';
            }
        } else {
            $_SESSION['error'] = 'invalid_symbol';
        }
    } else {
        $_SESSION['error'] = 'empty_task';
    }

    // Redirect back to the detail page with the error message
    header("Location: 4_detail_page.php?list_id=" . $list_id);
    exit();
}

$conn->close();
?>
