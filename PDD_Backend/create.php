<?php
include('db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for required fields
    if (isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password']; // Hash password securely

        // Check if the email already exists in the database
        $check_email_sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($check_email_sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(["status" => false, "message" => "Error: This email is already used."]);
        } else {
            // Ensure password is stored as a string in the database
            $insert_sql = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("sss", $email, $username, $password); // Changed type to "sss" (all strings)

            if ($stmt->execute()) {
                $last_id = $conn->insert_id;
                $result = $conn->query("SELECT id, email, username FROM users WHERE id = $last_id");

                if ($result && $row = $result->fetch_assoc()) {
                    echo json_encode(["status" => true, "message" => "Record added successfully. Signup Successful", "data" => $row]);
                } else {
                    echo json_encode(["message" => "Error fetching inserted data."]);
                }
            } else {
                echo json_encode(["message" => "Error: " . $stmt->error]);
            }
        }
        $stmt->close();
    } else {
        echo json_encode(["message" => "Invalid input or missing required fields."]);
    }
}
$conn->close();
?>
