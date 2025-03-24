<?php
include('db.php');

// Set the content type to JSON
header('Content-Type: application/json');

// GET - Retrieve all records
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // SQL query to fetch all records from remainder table
    $sql = "SELECT username, task, datetime FROM remainder";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Initialize an array to hold the results
        $tasks = array();

        // Fetch all rows from the result
        while($row = $result->fetch_assoc()) {
            // Add each record to the array
            $tasks[] = $row;
        }

        // Return a success message along with the tasks as a JSON response
        echo json_encode(array(
            "message" => "Records retrieved successfully.",
            "data" => $tasks
        ));
    } else {
        // If no records are found, return a message with an empty array
        echo json_encode(array(
            "message" => "No records found.",
            "data" => array()
        ));
    }
}
?>
