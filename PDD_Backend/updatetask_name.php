<?php
include('db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $task_name = $_POST['task_name'] ?? null;
    $category = $_POST['category'] ?? null;
    $status = $_POST['status'] ?? null;
    $created_at = $_POST['created_at'] ?? null;
    $updated_at = $_POST['updated_at'] ?? null;

    if ($task_name && $category && $status && $created_at && $updated_at) {
        // Update query
        $sql = "UPDATE add_task 
                SET task_name = '$task_name', 
                    category = '$category', 
                    status = '$status', 
                    created_at = '$created_at', 
                    updated_at = '$updated_at' 
                WHERE id = $id";

        if ($conn->query($sql)) {
            // Fetch the updated record
            $result = $conn->query("SELECT * FROM add_task WHERE id = $id");

            if ($result && $row = $result->fetch_assoc()) {
                echo json_encode([
                    "message" => "Record updated successfully.",
                    "updatedRecord" => $row
                ]);
            } else {
                echo json_encode(["message" => "Error: Record not found after update."]);
            }
        } else {
            echo json_encode(["message" => "Error: " . $conn->error]);
        }
    } else {
        echo json_encode(["message" => "Invalid input or missing required fields."]);
    }
} else {
    echo json_encode(["message" => "Invalid request method or missing 'id' parameter."]);
}
?>
