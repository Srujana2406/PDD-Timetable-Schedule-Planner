<?php
include('db.php');
header('Content-Type: application/json');

// Update record if POST request and 'id' parameter are provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $email = $_POST['email'] ?? null;
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;

    // Ensure required fields are provided
    if ($email && $username &&  $password) {
        // Update the record
        $sql = "UPDATE users SET email = '$email', username = '$username', password = '$password' WHERE id = $id";
        if ($conn->query($sql)) {
            // Fetch and return the updated record
            $result = $conn->query("SELECT * FROM users WHERE id = $id");
            if ($result->num_rows > 0) {
                echo json_encode(["message" => "Record updated successfully.", "updatedRecord" => $result->fetch_assoc()]);
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