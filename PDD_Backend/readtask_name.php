<?php
include('db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // SQL query to fetch all records from the add_task table
    $sql = "SELECT * FROM add_task";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Initialize an array to hold the results
        $tasks = array();

        // Fetch all rows from the result
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }

        // Return the data as a JSON response
        echo json_encode([
            "message" => "Records retrieved successfully.",
            "data" => $tasks
        ]);
    } else {
        // If no records are found
        echo json_encode([
            "message" => "No records found.",
            "data" => []
        ]);
    }
} else {
    // If the request method is not GET
    echo json_encode(["message" => "Invalid request method."]);
}
?>
