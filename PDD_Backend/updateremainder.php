<?php
include('db.php');
header('Content-Type: application/json');

// Check if the request method is POST and 'username' is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['username'])) {
    $username = $_GET['username'];
    $new_task = $_POST['task'] ?? null;

    // Ensure the task is provided
    if ($new_task) {
        // Check if the user exists
        $check_sql = "SELECT * FROM remainder WHERE username = '$username'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            // Update the task
            $sql = "UPDATE remainder SET task = '$new_task' WHERE username = '$username'";
            if ($conn->query($sql)) {
                // Fetch and return the updated record
                $result = $conn->query("SELECT username, task, datetime FROM remainder WHERE username = '$username'");
                if ($result->num_rows > 0) {
                    echo json_encode(["message" => "Task updated successfully.", "updatedRecord" => $result->fetch_assoc()]);
                } else {
                    echo json_encode(["message" => "Error: Record not found after update."]);
                }
            } else {
                echo json_encode(["message" => "Error: " . $conn->error]);
            }
        } else {
            echo json_encode(["message" => "Error: Username not found."]);
        }
    } else {
        echo json_encode(["message" => "Invalid input or missing required fields."]);
    }
}
?>
