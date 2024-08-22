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

// Decode product details
$product_ids = json_decode($quotation['product_ids'], true);
$quantities = json_decode($quotation['quantities'], true);
$prices = json_decode($quotation['prices'], true);

if (!is_array($product_ids) || !is_array($quantities) || !is_array($prices)) {
    die("Data is not in the expected format.");
}

$product_details = [];
$total_amount_before_discount = 0;
$total_discount_amount = 0;
$total_quantity = 0;

// Calculate total amount, discounts, and quantity
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
        'hsn_sac_no' => isset($product['hsn_sac_no']) ? $product['hsn_sac_no'] : '',
        'product_name' => $product_name,
        'price' => $price,
        'discount' => $discount_amount,
        'quantity' => $quantity,
        'total' => $total
    ];

    $total_amount_before_discount += $price * $quantity;
    $total_discount_amount += $discount_amount * $quantity;
    $total_quantity += $quantity;
}

// Calculate tax and grand total
$advance_payment = isset($quotation['adv_payment']) ? $quotation['adv_payment'] : 0;
$tax_rate = isset($quotation['tax_rate']) ? $quotation['tax_rate'] / 100 : 0;
$total_after_discount = $total_amount_before_discount - $total_discount_amount;
$tax_amount = $total_after_discount * $tax_rate;
$grand_total = $total_after_discount + $tax_amount;
$grand_total = ($grand_total - $advance_payment);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>OM Software - Invoice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    * {
        margin: 0px;
        padding: 0px;
    }
    </style>
</head>

<body style="font-family: Arial, sans-serif; font-size: 12px;">
    <h2 style="text-align:center;padding:10px 0px">Tax Invoice</h2>
    <div class="invoice" style="border: 1px solid #000; border-radius: 5px; width: 600px; margin: auto;">
        <!-- Company Profile and Quotation Details -->
        <div class="invoice-header" style="border-bottom: 1px solid #000;">
            <table width="100%" style="border-collapse: collapse;">
                <tr>
                    <td style="width: 50%; vertical-align: top;">
                        <div style="padding:5px 0px 5px 5px;">
                            <h3><?php echo htmlspecialchars($company_profile['company_name']); ?></h3>
                            <p><?php echo htmlspecialchars($company_profile['address']); ?></p>
                            <p><strong>CON. NO:</strong> <?php echo htmlspecialchars($company_profile['phone']); ?></p>
                            <p><strong>E-Mail:</strong> <?php echo htmlspecialchars($company_profile['email']); ?></p>

                            <p><strong>GSTIN/UIN:</strong> 27GUSPD4817C1ZI</p>
                            <p><strong>State Name:</strong> Maharashtra, Code: 27</p>
                        </div>
                        <hr>
                        <div style="padding:5px 0px 5px 5px;">
                            <h4>Buyer (Bill to)</h4>
                            <p><?php echo htmlspecialchars($client['client_name']); ?></p>
                            <p><?php echo htmlspecialchars($client['address']); ?></p>
                            <p><strong>E-Mail:</strong> <?php echo htmlspecialchars($client['email']); ?></p>
                            <p><strong>MO NO:</strong> <?php echo htmlspecialchars($client['phone']); ?></p>
                            <p>State Name: Maharashtra, Code: 27</p>
                        </div>
                    </td>
                    <td style="width: 50%; text-align: left; vertical-align: top; border-left: 1px solid #000;">
                        <table width="100%" style="border-collapse: collapse;">
                            <tr>
                                <td
                                    style="width: 50%; padding: 0px 10px; border-bottom: 1px solid #000; border-right: 1px solid #000;">
                                    Date <br><strong><?php echo htmlspecialchars($quotation['created_at']); ?></strong>
                                </td>
                                <td style="width: 50%; padding: 5px; border-bottom: 1px solid #000;">
                                    Invoice NO <br> <strong><?php echo htmlspecialchars($quotation_id); ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    style="width: 50%; padding: 0px 10px; border-bottom: 1px solid #000; border-right: 1px solid #000;">
                                    Delivery Note <br><strong></strong>
                                </td>
                                <td style="width: 50%; padding: 5px; border-bottom: 1px solid #000;">
                                    Mode/Terms of Payment <br> <strong></strong>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    style="width: 50%; padding: 0px 10px; border-bottom: 1px solid #000; border-right: 1px solid #000;">
                                    Reference No. & Date. <br><strong></strong>
                                </td>
                                <td style="width: 50%; padding: 5px; border-bottom: 1px solid #000;">
                                    Other References <br> <strong></strong>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    style="width: 50%; padding: 0px 10px; border-bottom: 1px solid #000; border-right: 1px solid #000;">
                                    Buyer’s Order No. <br><strong></strong>
                                </td>
                                <td style="width: 50%; padding: 5px; border-bottom: 1px solid #000;">
                                    Dated <br> <strong></strong>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    style="width: 50%; padding: 0px 10px; border-bottom: 1px solid #000; border-right: 1px solid #000;">
                                    Dispatch Doc No. <br><strong></strong>
                                </td>
                                <td style="width: 50%; padding: 5px; border-bottom: 1px solid #000;">
                                    Delivery Note Date <br> <strong></strong>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    style="width: 50%; padding: 0px 10px; border-bottom: 1px solid #000; border-right: 1px solid #000;">
                                    Dispatched through <br><strong></strong>
                                </td>
                                <td style="width: 50%; padding: 5px; border-bottom: 1px solid #000;">
                                    Destination <br> <strong></strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%; padding: 10px 10px 50px 10px;">
                                    Terms of Delivery <br><strong></strong>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Product Details -->
        <table width="100%" style="border-collapse: collapse;">
            <thead style="background: #f4f4f4; border:1px solid;">
                <tr>
                    <th style="border-bottom: 1px solid #000; padding: 5px;">Sr. No</th>
                    <th style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">HSN/SAC</th>
                    <th style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">Item Name</th>
                    <th style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">Price</th>
                    <th style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">Quantity</th>
                    <th style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">Discount</th>
                    <th style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($product_details as $index => $item): ?>
                <tr>
                    <td style="border-right: 1px solid #000; border-left: 1px solid #000; padding: 5px; text-align: center;">
                        <?php echo $index + 1; ?>
                    </td>
                    <td style="border-right: 1px solid #000; padding: 5px; text-align: center;">
                        <?php echo htmlspecialchars($item['hsn_sac_no']); ?>
                    </td>
                    <td style="border-right: 1px solid #000; padding: 5px; text-align: center;">
                        <?php echo htmlspecialchars($item['product_name']); ?>
                    </td>
                    <td style="border-right: 1px solid #000; padding: 5px; text-align: center;">
                        <?php echo number_format($item['price'], 2); ?>
                    </td>
                    <td style="border-right: 1px solid #000; padding: 5px; text-align: center;">
                        <?php echo htmlspecialchars($item['quantity']); ?>
                    </td>
                    <td style="border-right: 1px solid #000; padding: 5px; text-align: center;">
                        <?php echo number_format($item['discount'], 2); ?>
                    </td>
                    <td style="border-right: 1px solid #000; padding: 5px; text-align: center;">
                        <?php echo number_format($item['total'], 2); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <thead style="background: #f4f4f4; border:1px solid;">
                <tr>
                    <th style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;"></th>
                    <th style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">Total</th>
                    <th style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;"></th>
                    <th style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;"></th>
                    <th style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">
                        <?php echo $total_quantity; ?> NOS</th>
                    <th style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;"></th>
                    <th style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">
                        <?php echo number_format($grand_total, 2); ?></th>
                </tr>
            </thead>
        </table>
        <!-- Bank Details -->
        <div class="footer" style="margin-top:200px;">
            <div class="footer-p1" style="display:flex; justify-content:space-between; padding:0px 5px;">
                <div>
                    <p>Amount Chargeable (in words)</p>
                    <strong>INR Twenty Eight Thousand Six Hundred Forty
                        Six Only</strong>
                </div>
                <p style="font-style:italic">E. & O.E</p>
            </div>
            <div class="footer-p2" style="display:flex; justify-content:end; padding:0px 5px;">

                <div class="bank-details" style="margin-top: 20px;">
                    <strong>Bank Details:</strong>
                    <ul style="list-style-type: none; padding: 0;">
                        <li style="padding: 3px 0;"><strong>Bank Name:</strong> HDFC</li>
                        <li style="padding: 3px 0;"><strong>Account No:</strong> 4353455454</li>
                        <li style="padding: 3px 0;"><strong>Branch & IFSC Code:</strong> HT4536</li>
                    </ul>
                </div>
            </div>
            <div class="footer-p3" style="display:flex; justify-content:space-between; padding:0px 5px;">
                <div style="width:50%">
                    <p style="border-bottom:1px solid;display:inline-block;">Declaration</p><br>
                    <strong style="color:#444;">ALL HIKVISON PRODUCTS HAVE A SERVICE
                        CENTER WARRENTY ONLY AND IT TOOKS A
                        PERIOD OF 15 TO 20 DAYS MINIMUM</strong>
                </div>
                <div class="bank-details"
                    style="border-top:1px solid; border-left:solid 1px; width:50%; text-align:right; display:grid;align-items: flex-end;">
                    <strong>for Om Vision
                    </strong>
                    <p style="paddign-top: 100px; margin-bottom:0px">Authorised Signatory</p>
                </div>
            </div>
        </div>

        <!-- Print Button -->

    </div>
    <p style="text-align: center; padding: 20px 0;">This is a computer generated invoice.</p>


    <h2 style="text-align:center;padding:10px 0px">Tax Invoice</h2>
    <p style="text-align:center;padding:0px 0px 10px 0px">(Tax Analysis)</p>
    <div class="invoice" style="border: 1px solid #000; border-radius: 5px; width: 600px; margin: auto;">
        <!-- Company Profile and Quotation Details -->
        <div class="invoice-header" style="border-bottom: 1px solid #000; text-align:center">
            <table width="100%" style="border-collapse: collapse;">

                <tr>
                    <td>
                        <div style="display:flex; justify-content:space-between; align-items:center; padding:10px">
                            <strong>Date <?php echo htmlspecialchars($quotation['created_at']); ?></strong>
                            <strong>Invoice NO <?php echo htmlspecialchars($quotation_id); ?></strong>
                        </div>
                        <div style="padding:5px 0px 5px 5px;">
                            <h3><?php echo htmlspecialchars($company_profile['company_name']); ?></h3>
                            <p><?php echo htmlspecialchars($company_profile['address']); ?></p>
                            <p><strong>CON. NO : </strong> <?php echo htmlspecialchars($company_profile['phone']); ?>
                            </p>
                            <p><strong>E-Mail : </strong> <?php echo htmlspecialchars($company_profile['email']); ?></p>

                            <p><strong>GSTIN/UIN:</strong> 27GUSPD4817C1ZI</p>
                            <p><strong>State Name:</strong> Maharashtra, Code: 27</p>
                        </div>
                        <div style="padding:5px 0px 5px 5px;">
                            <p><strong>Party : </strong><?php echo htmlspecialchars($client['client_name']); ?></p>
                            <p><?php echo htmlspecialchars($client['address']); ?></p>
                            <p><strong>E-Mail : </strong> <?php echo htmlspecialchars($client['email']); ?></p>
                            <p><strong>MO NO : </strong> <?php echo htmlspecialchars($client['phone']); ?></p>
                            <p>State Name : Maharashtra, Code : 27</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Product Details -->
        <table width="100%" style="border-collapse: collapse; text-align: left;">
            <thead style="background: #f4f4f4; border: 1px solid;">
                <tr>
                    <th rowspan="2" style="border-bottom: 1px solid #000; padding: 5px;">Sr. No</th>
                    <th rowspan="2" style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">
                        HSN/SAC</th>
                    <th rowspan="2" style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">
                        Taxable Amount</th>
                    <th rowspan="2" style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">
                        Price</th>
                    <th colspan="2"
                        style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; text-align: center;">
                        Central Tax</th>
                    <th colspan="2"
                        style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; text-align: center;">
                        State Tax</th>

                    <th rowspan="2" style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">
                        Total Tax Amount</th>
                </tr>
                <tr>

                    <th style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">Rate</th>
                    <th style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">Amount</th>
                    <th style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">Rate</th>
                    <th style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($product_details as $index => $item): ?>
                <tr style="text-align: center;">
                    <td style="border-right: 1px solid #000; border-left: 1px solid #000; padding: 5px; text-align: center;">
                        <?php echo $index + 1; ?>
                    </td>
                    <td style="border-right: 1px solid #000; padding: 5px; text-align: center;">
                        <?php echo htmlspecialchars($item['hsn_sac_no']); ?>
                    </td>
                    <td style="border-right: 1px solid #000; padding: 5px; text-align: center;">
                        <?php echo htmlspecialchars($item['product_name']); ?>
                    </td>
                    <td style="border-right: 1px solid #000; padding: 5px; text-align: center;">
                        <?php echo number_format($item['price'], 2); ?>
                    </td>
                    <td style="border-right: 1px solid #000; padding: 5px; text-align: center;">
                        <?php echo number_format($item['price'], 2); ?>
                    </td>
                    <td style="border-right: 1px solid #000; padding: 5px; text-align: center;">
                        <?php echo number_format($item['price'], 2); ?>
                    </td>
                    <td style="border-right: 1px solid #000; padding: 5px; text-align: center;">
                        <?php echo htmlspecialchars($item['quantity']); ?>
                    </td>
                    <td style="border-right: 1px solid #000; padding: 5px; text-align: center;">
                        <?php echo number_format($item['discount'], 2); ?>
                    </td>
                    <td style="border-right: 1px solid #000; padding: 5px; text-align: center;">
                        <?php echo number_format($item['total'], 2); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <thead style="background: #f4f4f4; border: 1px solid;">
                <tr>
                    <th rowspan="2" style="border-bottom: 1px solid #000; padding: 5px;"></th>
                    <th rowspan="2" style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">
                        Total</th>
                    <th rowspan="2" style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">
                        Taxable Amount</th>
                    <th rowspan="2" style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">
                        Price</th>
                    <th colspan="2"
                        style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; text-align: center;">
                        Central Tax</th>
                    <th colspan="2"
                        style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px; text-align: center;">
                        State Tax</th>

                    <th rowspan="2" style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 5px;">
                        Total Tax Amount</th>
                </tr>

            </thead>
        </table>
        <div class="footer" style="margin-top:200px;">
            <div class="footer-p1" style="padding:20px 5px;">
                <div>
                    <p>Amount Chargeable (in words)</p>
                    <strong>INR Twenty Eight Thousand Six Hundred Forty
                        Six Only</strong>
                </div>
            </div>
            <div class="footer-p2" style="padding:0px 5px;">


            </div>
            <div style="padding: 0px 10px ;flex-direction: column; display:flex; justify-content:right; text-align:right; ">
                <strong>for Om Vision
                </strong>
                <br>
                <br>
                <p style="paddign-top: 100px; margin-bottom:0px">Authorised Signatory</p>
            </div>

        </div>
        

        <!-- Print Button -->

    </div>
    <!-- Print Button -->
    <p style="text-align: center; padding: 20px 0;">This is a computer generated invoice.</p>
    <div style="text-align: center; margin-top: 20px;">
        <button onclick="this.style.display='none'; window.print(); this.style.display='block';" class="no-print"
            style="padding: 10px 20px; font-size: 14px; cursor: pointer;">
            Print Invoice
        </button>
    </div>
</body>

</html>