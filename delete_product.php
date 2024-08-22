<?php
include('./includes/db_connection.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Check if the record exists
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Record exists, proceed to delete
            $sql = "DELETE FROM products WHERE product_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                // Redirect after successful deletion
                header("Location: product_list.php");
                exit();
            } else {
                echo "Error deleting record: " . $stmt->error;
            }
        } else {
            echo "No record found for ID: " . $id;
        }
    } else {
        echo "Error executing query: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No ID specified.";
}
?>
