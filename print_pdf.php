<?php
require('includes/fpdf186/fpdf.php'); // Adjust the path to where you store FPDF

include('./includes/db_connection.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch data
$sql = "SELECT * FROM expenses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Create instance of FPDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    // Title
    $pdf->Cell(0, 10, 'Expense Details', 0, 1, 'C');
    $pdf->Ln(10);

    // Expense details
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Name: ' . htmlspecialchars($row['name']), 0, 1);
    $pdf->Cell(0, 10, 'Head: ' . htmlspecialchars($row['head']), 0, 1);
    $pdf->Cell(0, 10, 'Amount: ' . htmlspecialchars($row['amount']), 0, 1);
    $pdf->Cell(0, 10, 'Payment Mode: ' . htmlspecialchars($row['payment_mode']), 0, 1);
    $pdf->Cell(0, 10, 'Payment Id: ' . htmlspecialchars($row['payment_id']), 0, 1);
    $pdf->Cell(0, 10, 'Message: ' . htmlspecialchars($row['message']), 0, 1);
    $pdf->Cell(0, 10, 'Created At: ' . htmlspecialchars($row['created_at']), 0, 1);

    $pdf->Output('D', 'expense_' . $id . '.pdf');
} else {
    echo "No data found for ID: " . $id;
}

// Close connection
$conn->close();
?>
