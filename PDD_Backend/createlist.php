<?php
include('db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['list'])) {
        $email = trim($_POST['email']);
        $list = trim($_POST['list']); // Trim spaces
        
        // Generate a unique ID
        $unique_id = uniqid('', true);

        // Check if the email exists in the users table
        $user_check_sql = "SELECT email FROM users WHERE email = ?";
        $stmt = $conn->prepare($user_check_sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user_result = $stmt->get_result();

        if ($user_result->num_rows == 0) {
            echo json_encode(["status" => false, "message" => "Error: Email not found in users table."]);
        } else {
            // Check if the same list already exists for the email (case insensitive)
            $check_sql = "SELECT email FROM list WHERE email = ? AND LOWER(list) = LOWER(?)";
            $stmt = $conn->prepare($check_sql);
            $stmt->bind_param("ss", $email, $list);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo json_encode(["status" => false, "message" => "Error: Duplicate list entry for the same email."]);
            } else {
                // Insert into lists table with unique ID
                $insert_sql = "INSERT INTO list (id, email, list, created_at) VALUES (?, ?, ?, NOW())";
                $stmt = $conn->prepare($insert_sql);
                $stmt->bind_param("sss", $unique_id, $email, $list);
                
                if ($stmt->execute()) {
                    // Retrieve the inserted record with created_at
                    $fetch_sql = "SELECT id, email, list, created_at FROM list WHERE id = ?";
                    $stmt = $conn->prepare($fetch_sql);
                    $stmt->bind_param("s", $unique_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($row = $result->fetch_assoc()) {
                        echo json_encode([
                            "status" => true,
                            "message" => "Record added successfully.",
                            "data" => $row
                        ]);
                    }
                } else {
                    echo json_encode(["status" => false, "message" => "Error: " . $stmt->error]);
                }
            }
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => false, "message" => "Invalid input or missing required fields."]);
    }
}
$conn->close();
?>
