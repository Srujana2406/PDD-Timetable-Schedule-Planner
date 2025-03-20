<?php
include('db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Log incoming POST data
    error_log(print_r($_POST, true));

    // Check for required fields
    if (isset($_POST['username']) && isset($_POST['task'])) {
        $username = $_POST['username'];
        $task = $_POST['task'];

        // Check if the remainder already exists
        $check_sql = "SELECT * FROM remainder WHERE username = '$username' AND task = '$task'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            echo json_encode(["message" => "Task already exists. Duplicate entries are not allowed."]);
        } else {
            // Get the current timestamp
            $datetime = date('Y-m-d H:i:s.u');

            // Insert data into the database
            $sql = "INSERT INTO remainder (username, task, datetime) VALUES ('$username', '$task', '$datetime')";
            if ($conn->query($sql)) {
                // Fetch the newly inserted record
                $last_id = $conn->insert_id;
                $result = $conn->query("SELECT username, task, datetime FROM remainder WHERE username = '$username' AND task = '$task'");

                if ($result && $row = $result->fetch_assoc()) {
                    echo json_encode(["message" => "Task created successfully.", "data" => $row]); 
                } else { 
                    echo json_encode(["message" => "Error fetching inserted data."]);
                }
            } else {
                echo json_encode(["message" => "Error: " . $conn->error]);
            }
        } 
    } else {
        echo json_encode(["message" => "Invalid input or missing required fields."]); 
    } 
}  
?>
