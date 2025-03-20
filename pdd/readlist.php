<?php
include('db.php');

// Set the content type to JSON
header('Content-Type: application/json');

// GET - Retrieve all records from the list table
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // SQL query to fetch lists
    $sql = "SELECT id, email, list, created_at FROM list";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Initialize an array to hold the results
        $lists = array();

        // Fetch all rows from the result
        while($row = $result->fetch_assoc()) {
            // Add each list entry to the array
            $lists[] = $row;
        }

        // Return a success message along with the lists as a JSON response
        echo json_encode(array(
            "message" => "Records retrieved successfully.",
            "data" => $lists
        ));
    } else {
        // If no records are found, return a message with an empty array
        echo json_encode(array(
            "message" => "No records found.",
            "data" => array()
        ));
    }
}

$conn->close();
?>