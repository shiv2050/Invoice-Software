<?php
include('./includes/db_connection.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare to fetch the logo filename
    $sql = "SELECT company_logo FROM company_profile WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $logoFile = $row['company_logo'];
        $logoPath = "uploads/" . $logoFile;

        // Delete the record
        $sql = "DELETE FROM company_profile WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Remove the logo file from the server
            if (!empty($logoFile) && file_exists($logoPath)) {
                if (unlink($logoPath)) {
                    echo "File successfully deleted.";
                } else {
                    echo "Error deleting file.";
                }
            } else {
                echo "File does not exist or no file associated.";
            }

            // Redirect after successful deletion
            header("Location: company_list.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "No record found for ID: " . $id;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No ID specified.";
}
?>
