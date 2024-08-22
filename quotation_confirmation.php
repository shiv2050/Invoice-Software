<?php
include('./includes/db_connection.php');

// Check if id is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Quotation ID is missing.";
    exit();
}

$quotation_id = intval($_GET['id']);

// Fetch quotation details
$stmt = $conn->prepare("SELECT * FROM quotation WHERE id = ?");
$stmt->bind_param("i", $quotation_id);
$stmt->execute();
$result = $stmt->get_result();
$quotation = $result->fetch_assoc();

if (!$quotation) {
    echo "Quotation not found.";
    exit();
}

// Fetch client details
$client_sql = "SELECT * FROM client WHERE client_id = ?";
$stmt = $conn->prepare($client_sql);
$stmt->bind_param("i", $quotation['client_id']);
$stmt->execute();
$client = $stmt->get_result()->fetch_assoc();

// Fetch company profile details
$company_id = $quotation['company_profile_id'];
$company_profile_sql = "SELECT * FROM company_profile WHERE id = ?";
$stmt = $conn->prepare($company_profile_sql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$company_profile = $stmt->get_result()->fetch_assoc();

if (!$company_profile) {
    die("Company profile not found");
}
// Fetch company profile details including terms
$company_profile_sql = "SELECT * FROM company_profile WHERE id = ?";
$stmt = $conn->prepare($company_profile_sql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$company_profile = $stmt->get_result()->fetch_assoc();
// Fetch terms acceptance status
$terms_accepted = isset($company_profile['terms_accepted']) ? $company_profile['terms_accepted'] : 0;

if (!$company_profile) {
    die("Company profile not found");
}
// Decode product details
$product_ids = json_decode($quotation['product_ids'], true);
$quantities = json_decode($quotation['quantities'], true);
$prices = json_decode($quotation['prices'], true); // Ensure prices are decoded as an array

if (!is_array($product_ids) || !is_array($quantities) || !is_array($prices)) {
    die("Data is not in the expected format.");
}

$product_details = [];
$total_amount_before_discount = 0;
$total_discount_amount = 0;

// Calculate total amount and discounts
foreach ($product_ids as $index => $product_id) {
    $price = isset($prices[$index]) ? $prices[$index] : 0;
    $quantity = isset($quantities[$index]) ? $quantities[$index] : 0;

    // Fetch product details for name and discount if necessary
    $product_sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($product_sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $product_name = isset($product['product_name']) ? $product['product_name'] : 'Unknown Product';
    $discount = isset($product['discount']) ? $product['discount'] : 0;

    $discount_amount = ($price * $discount) / 100;
    $price_after_discount = $price - $discount_amount;
    $total = $price_after_discount * $quantity;

    $product_details[] = [
        'hsn_sac_no' => isset($product['hsn_sac_no']) ? $product['hsn_sac_no'] : '', // Adjust if hsn_sac_no is available
        'product_name' => $product_name,
        'price' => $price,
        'discount' => $discount_amount,
        'quantity' => $quantity,
        'total' => $total
    ];

    $total_amount_before_discount += $price * $quantity;
    $total_discount_amount += $discount_amount * $quantity;
}

// Calculate tax and grand total
$advance_payment = isset($quotation['adv_payment']) ? $quotation['adv_payment'] : 0;
$tax_rate = isset($quotation['tax_rate']) ? $quotation['tax_rate'] / 100 : 0; // Default to 0 if tax_rate not set
$total_after_discount = $total_amount_before_discount - $total_discount_amount;
$tax_amount = $total_after_discount * $tax_rate;
$grand_total = $total_after_discount + $tax_amount;
$grand_total = ($grand_total - $advance_payment);


?>
<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8" />
    <title>OM Software - Company List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="MyraStudio" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    
    <style>
    .invoice {
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .invoice-header,
    .invoice-footer {
        margin-bottom: 20px;
    }

    .invoice-items th,
    .invoice-items td {
        padding: 8px;
        text-align: right;
    }

    .invoice-items th {
        background: #f8f9fa;
    }

    .invoice-items td {
        border-bottom: 1px solid #ddd;
    }
    </style>
    
    <?php include('./includes/header.php')?>
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- Other HTML content -->
                    <div class="row">
                        <div class="col-lg-10 mx-auto">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="invoice">
                                        <!-- Company Profile -->
                                        <div class="invoice-header">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h2 class="text-center py-3">Tax Invoice</h2>
                                                    <div
                                                        class="company-details d-flex justify-content-between align-items-center">
                                                        <div class="company-left">
                                                            <img src="<?php echo htmlspecialchars($company_profile['company_logo']); ?>"
                                                                alt="Company Logo" style="max-width: 100px;">
                                                            <h1><?php echo htmlspecialchars($company_profile['company_name']); ?>
                                                            </h1>
                                                        </div>
                                                        <div class="company-detail-right text-right">
                                                            <p><strong>Email:</strong>
                                                                <?php echo htmlspecialchars($company_profile['email']); ?>
                                                            </p>
                                                            <p><strong>Phone:</strong>
                                                                <?php echo htmlspecialchars($company_profile['phone']); ?>
                                                            </p>
                                                            <p><strong>Address:</strong>
                                                                <?php echo htmlspecialchars($company_profile['address']); ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div
                                                        class="invoice-details d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h5>Client Details</h5>
                                                            <p><strong>Name:</strong>
                                                                <?php echo htmlspecialchars($client['client_name']); ?>
                                                            </p>
                                                            <p><strong>Email:</strong>
                                                                <?php echo htmlspecialchars($client['email']); ?></p>
                                                            <p><strong>Phone:</strong>
                                                                <?php echo htmlspecialchars($client['phone']); ?></p>
                                                        </div>
                                                        <div class="text-md-right">
                                                            <h5>Quotation Details</h5>
                                                            <p><strong>Date:</strong>
                                                                <?php echo htmlspecialchars($quotation['created_at']);?>
                                                            </p>
                                                            <p><strong>Quotation ID:</strong>
                                                                <?php echo htmlspecialchars($quotation_id);?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Invoice Body -->
                                        <div class="invoice-body">
                                            <h5>Product Details</h5>
                                            <table class="table table-bordered invoice-items">
                                                <thead>
                                                    <tr>
                                                        <th>HSN/SAC No.</th>
                                                        <th>Product Name</th>
                                                        <th>Price</th>
                                                        <th>Quantity</th>
                                                        <th>Discount (Per Unit)</th>
                                                        <th>Total Discount</th>
                                                        <th>Tax %</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($product_details as $item): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($item['hsn_sac_no']); ?></td>
                                                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                                        <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                                        <td>₹<?php echo number_format($item['discount'], 2); ?></td>
                                                        <td>₹<?php echo number_format($item['discount'] * $item['quantity'], 2); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($quotation['tax_rate']); ?>%
                                                        </td>
                                                        <td>₹<?php echo number_format($item['total'], 2); ?></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Invoice Footer -->
                                        <div class="invoice-footer">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h5>Summary</h5>
                                                    <table class="table table-bordered invoice-items">
                                                        <thead>
                                                            <tr>
                                                                <th>Total Amount (Before Discount)</th>
                                                                <th>Discount Amount</th>
                                                                <th>Tax Amount</th>
                                                                <th>Advance payment</th>
                                                                <th>Grand Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>₹<?php echo number_format($total_amount_before_discount, 2); ?>
                                                                </td>
                                                                <td>₹<?php echo number_format($total_discount_amount, 2); ?>
                                                                </td>
                                                                <td>₹<?php echo number_format($tax_amount, 2); ?></td>
                                                                <td>₹<?php echo number_format($advance_payment, 2); ?>
                                                                </td>
                                                                <td>₹<?php echo number_format($grand_total, 2); ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <div class="d-flex justify-content-end">
                                                        <strong style="font-size:18px">Total Amount:
                                                            ₹<?php echo number_format($grand_total, 2); ?> (INR Rupees
                                                            Only)</strong>
                                                    </div>
                                                    <!-- Terms and Conditions -->
                                                    <?php if ($terms_accepted): ?>
                                                    <div class="terms-conditions border p-2 mt-3 ">
                                                        <h4>Terms and Conditions</h4>
                                                        <div class="d-flex align-items-end justify-content-between">
                                                            <?php echo nl2br($company_profile['terms']); ?>
                                                            <div class="text-center">
                                                                <strong>For Orbit Technologies</strong>
                                                                <br><br>
                                                                <p>Authorized Signatory</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button class="btn btn-primary no-print" onclick="printTable()">Print Invoice</button>
                    </div>
                </div>
            </div>
        </div>
        <?php include('./includes/footer.php')?>