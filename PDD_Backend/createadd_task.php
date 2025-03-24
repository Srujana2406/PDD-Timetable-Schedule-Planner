<?php
include('db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for required fields
    if (!empty($_POST['username']) && !empty($_POST['task_name']) && !empty($_POST['list']) && !empty($_POST['status'])) {

        $username = $conn->real_escape_string($_POST['username']);
        $task_name = $conn->real_escape_string($_POST['task_name']);
        $list = $conn->real_escape_string($_POST['list']);
        $status = $conn->real_escape_string($_POST['status']);
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        $task_id = uniqid(); // Generate unique random ID

        // Check for duplicate task
        $check_sql = "SELECT * FROM add_task WHERE username = '$username' AND task_name = '$task_name' AND list = '$list'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            echo json_encode(["status" => false, "message" => "Duplicate task exists."]);
        } else {
            // Insert data into the database
            $sql = "INSERT INTO add_task (id, username, task_name, list, status, created_at, updated_at) VALUES ('$id', '$username', '$task_name', '$list', '$status', '$created_at', '$updated_at')";

            if ($conn->query($sql)) {
                // Fetch the newly inserted record
                $result = $conn->query("SELECT * FROM add_task WHERE id = '$id'");

                if ($result && $row = $result->fetch_assoc()) {
                    echo json_encode(["status" => true, "message" => "Record added successfully.", "data" => $row]);
                } else {
                    echo json_encode(["status" => false, "message" => "Error fetching inserted data."]);
                }
            } else {
                echo json_encode(["status" => false, "message" => "Error: " . $conn->error]);
            }
        }
    } else {
        echo json_encode(["status" => false, "message" => "Invalid input or missing required fields."]);
    }
}
?>
