<?php
include('db.php');
header('Content-Type: application/json');

// Update record if POST request and 'id' parameter are provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $username = $_POST['username'] ?? null;
    $list = $_POST['list'] ?? null;

    // Ensure required fields are provided
    if ($username && $list) {
        // Update the record
        $sql = "UPDATE list SET username = '$username', list = '$list' WHERE id = $id"; 
        if ($conn->query($sql)) {
            // Fetch and return the updated record
            $result = $conn->query("SELECT * FROM list WHERE id = $id");
            if ($result->num_rows > 0) {
                echo json_encode([
                    "message" => "Record updated successfully.",
                    "updatedRecord" => $result->fetch_assoc()
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
}
?>
