<?php
include('./includes/db_connection.php');

// Check if id is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Quotation ID is missing.";
    exit();
}

$quotation_id = intval($_GET['id']);

// Fetch quotation data
$stmt = $conn->prepare("SELECT * FROM quotation WHERE id = ?");
$stmt->bind_param("i", $quotation_id);
$stmt->execute();
$result = $stmt->get_result();
$quotation = $result->fetch_assoc();

if (!$quotation) {
    echo "Quotation not found.";
    exit();
}

// Debugging: Output raw JSON data
echo "<pre>";
echo "Raw product_ids JSON: " . htmlspecialchars($quotation['product_ids']) . "<br>";
echo "Raw quantities JSON: " . htmlspecialchars($quotation['quantities']) . "<br>";
echo "Raw prices JSON: " . htmlspecialchars($quotation['prices']) . "<br>";
echo "</pre>";

// Decode JSON fields
$product_ids = json_decode($quotation['product_ids'], true);
$quantities = json_decode($quotation['quantities'], true);

// Handle non-JSON price data
$prices = json_decode($quotation['prices'], true);
if (json_last_error() === JSON_ERROR_NONE) {
    // If JSON decoding is successful
    if (!is_array($prices)) {
        $prices = [];
    }
} else {
    // If JSON decoding fails, handle as a single value
    if (is_numeric($quotation['prices'])) {
        $prices = [$quotation['prices']];
    } else {
        $prices = [];
    }
}

// Validate and handle the decoded data
if (!is_array($product_ids) || !is_array($quantities) || !is_array($prices)) {
    echo "Error: Data is not in the expected format.";
    exit();
}

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Error decoding JSON data: " . json_last_error_msg();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quotation Confirmation</title>
</head>
<body>
    <h1>Quotation Confirmation</h1>
    <p><strong>Client ID:</strong> <?php echo htmlspecialchars($quotation['client_id']); ?></p>
    <p><strong>Company Profile ID:</strong> <?php echo htmlspecialchars($quotation['company_profile_id']); ?></p>
    <p><strong>Total Amount:</strong> ₹<?php echo htmlspecialchars($quotation['total_amount']); ?></p>
    <p><strong>Tax Amount:</strong> ₹<?php echo htmlspecialchars($quotation['tax_amount']); ?></p>
    <p><strong>Grand Total:</strong> ₹<?php echo htmlspecialchars($quotation['grand_total']); ?></p>
    
    <h2>Product Details</h2>
    <?php if (count($product_ids) > 0 && count($quantities) > 0 && count($prices) > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < count($product_ids); $i++): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product_ids[$i]); ?></td>
                        <td><?php echo htmlspecialchars($quantities[$i]); ?></td>
                        <td><?php echo htmlspecialchars($prices[$i] ?? 'N/A'); ?></td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No product details available.</p>
    <?php endif; ?>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
