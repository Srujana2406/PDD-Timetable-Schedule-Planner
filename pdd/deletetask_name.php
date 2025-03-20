<?php
include('db.php');
header('Content-Type: application/json');

// DELETE - Remove a record
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // First, fetch the record to be deleted
    $selectSql = "SELECT * FROM add_task WHERE id = $id";
    $result = $conn->query($selectSql);
    
    if ($result->num_rows > 0) {
        $recordToDelete = $result->fetch_assoc();
        
        // Now, delete the record
        $sql = "DELETE FROM add_task WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            // Return the deleted record
            echo json_encode(array(
                "message" => "Record deleted successfully.",
                "deletedRecord" => $recordToDelete
            ));
        } else {
            echo json_encode(array("message" => "Error: " . $conn->error));
        }
    } else {
        echo json_encode(array("message" => "Error: Record not found."));
    }
} else {
    echo json_encode(array("message" => "Invalid request method or missing 'id' parameter."));
}
?>
