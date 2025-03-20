<?php
include('db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input data
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    $uniqueid=uniqid('user_',true);

    // Check if email and password are provided
    if ($email && $password) {
        // Fetch user details based on email
        $sql = "SELECT email, username, password FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $users = $result->fetch_assoc();
            
            // Verify passwords
            if ($password === $users['password']) { // Use password_hash() for better security
               
                echo json_encode([
                    "status" => true,
                    "message" => "Login successful.",
                   
                    "users" => [
                        "id" => $uniqueid,
                        "email" => $users['email'],
                        "username" => $users['username']
                    ]
                ]);
            } else {
                echo json_encode(["status"=> false, "message" => "Incorrect password."]);
            }
        } else {
            echo json_encode(["status"=>false,"message" => "User not found."]);
        }
    } else {
        echo json_encode(["status"=>false,"message" => "Email and password are required."]);
    }
}
?>