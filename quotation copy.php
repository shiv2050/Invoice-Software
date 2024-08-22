<?php
include('./includes/db_connection.php');
$current_date = date('Y-m-d');
// Fetch clients
$client_sql = "SELECT * FROM client";
$client_result = $conn->query($client_sql);

// Fetch products
$product_sql = "SELECT * FROM products";
$product_result = $conn->query($product_sql);

// Fetch all products for JavaScript
$products_array = [];
while ($row = $product_result->fetch_assoc()) {
    $products_array[] = $row;
}

// Fetch company profile data
$company_sql = "SELECT * FROM company_profile";
$company_result = $conn->query($company_sql);
$company_profiles = [];
while ($row = $company_result->fetch_assoc()) {
    $company_profiles[] = $row;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_id = $_POST['client_id'];
    $product_ids = $_POST['product_ids'];
    $quantities = $_POST['quantities'];
    $adv_payment = isset($_POST['adv_payment']) ? $_POST['adv_payment'] : 0.00;
    $prices = $_POST['price']; // Get prices from the form
    $taxable = $_POST['taxable'];
    $tax_rate = ($taxable == 'yes') ? $_POST['tax_rate'] : 0.00;
    $company_profile_id = $_POST['company_profile_id']; // New field
    $created_at = $_POST['created_at'];

    // Validate inputs
    if (empty($client_id) || empty($product_ids) || empty($quantities) || empty($company_profile_id) || empty($created_at) && preg_match('/\d{4}-\d{2}-\d{2}/', $created_at)) {
        echo "All fields are required.";
        exit();
    }
     // Validate the new field (if needed)
     if (!is_numeric($adv_payment) || $adv_payment < 0) {
        echo "Invalid advance payment amount.";
        exit();
    }
    // Calculate total amount
    $total_amount = 0;
    foreach ($product_ids as $index => $product_id) {
        $price = $prices[$index];
        $quantity = $quantities[$index];
        $total_amount += $price * $quantity;
    }

    // Apply tax
    $discount_percentage = 0; // Default to 0% if no discount
    $discount_amount = ($total_amount * $discount_percentage) / 100;
    $total_amount_after_discount = $total_amount - $discount_amount;
    $tax_amount = $total_amount_after_discount * ($tax_rate / 100);
    $grand_total = $total_amount_after_discount + $tax_amount;

    // Save quotation to database
    $stmt = $conn->prepare("INSERT INTO quotation (client_id, product_ids, quantities, prices, taxable, tax_rate, total_amount, tax_amount, grand_total, company_profile_id, created_at, adv_payment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
        // Encode arrays as JSON
        $product_ids_json = json_encode($product_ids);
    $quantities_json = json_encode($quantities);
    $prices_json = json_encode($prices);

        // Bind parameters
        if ($stmt) {
            $stmt->bind_param(
                'issssdddssss',
                $client_id,
                $product_ids_json,
                $quantities_json,
                $prices_json,
                $taxable,
                $tax_rate,
                $total_amount,
                $tax_amount,
                $grand_total,
                $company_profile_id,
                $created_at,
                $adv_payment // Bind the new parameter
            );

        // Execute the statement
        if ($stmt->execute()) {
            $quotation_id = $stmt->insert_id; // Get the last inserted ID
            // Redirect to a confirmation page
            header("Location: quotation_confirmation.php?id=" . $quotation_id);
            exit();
        } else {
            // Handle error
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        // Handle prepare statement error
        echo "Prepare failed: " . $conn->error;
    }
}
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
    <?php include('./includes/header.php')?>
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 font-size-18">Create Quotation</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <form action="quotation.php" method="POST">
                                        <div class="row">
                                            <!-- Existing fields -->
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="company_profile_id">Select Company Profile</label>
                                                    <select id="company_profile_id" name="company_profile_id" class="form-control" required>
                                                        <option value="">Choose Company Profile</option>
                                                        <?php foreach ($company_profiles as $company): ?>
                                                        <option value="<?php echo $company['id']; ?>">
                                                            <?php echo $company['company_name']; ?>
                                                        </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="client_id">Select Client</label>
                                                    <select id="client_id" name="client_id" class="form-control" required>
                                                        <option value="">Choose Client</option>
                                                        <?php while ($client = $client_result->fetch_assoc()): ?>
                                                        <option value="<?php echo $client['client_id']; ?>">
                                                            <?php echo $client['client_name']; ?>
                                                        </option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
    <div class="form-group">
        <label for="adv_payment">Advance Payment</label>
        <input type="number" name="adv_payment" class="form-control" step="0.01" required>
    </div>
</div>
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label>Is Taxable?</label>
                                                    <select name="taxable" class="form-control" onchange="toggleTaxRate()" required>
                                                        <option value="yes">Yes</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div id="tax-rate-field" class="form-group" style="display: none;">
                                                    <label for="tax_rate">Select Tax Rate</label>
                                                    <select name="tax_rate" class="form-control">
                                                        <option value="0">0%</option>
                                                        <option value="18">18%</option>
                                                        <option value="28">28%</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="product-section">
                                            <div class="row product-item">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="product_ids">Select Product</label>
                                                        <select name="product_ids[]" class="form-control product-select" required>
                                                            <option value="">Choose Product</option>
                                                            <?php foreach ($products_array as $product): ?>
                                                            <option value="<?php echo $product['product_id']; ?>" data-price="<?php echo $product['price']; ?>">
                                                                <?php echo $product['product_name']; ?> - ₹<?php echo $product['price']; ?>
                                                            </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="quantities">Product Quantity</label>
                                                        <input type="number" name="quantities[]" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="price">Product Price</label>
                                                        <input type="number" name="price[]" class="form-control">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="date">Date</label>
                                                        <input type="date" name="created_at" class="form-control" value="<?php echo htmlspecialchars($current_date); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" id="add-product" class="btn btn-success">Add Another Product</button>
                                        <button type="submit" class="btn btn-primary">Create Quotation</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- container-fluid -->
            </div> <!-- End Page-content -->
        </div><!-- main-content -->
    </div>
    <?php include('./includes/footer.php')?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to toggle the tax rate field
        function toggleTaxRate() {
            const taxableSelect = document.querySelector('select[name="taxable"]');
            const taxRateField = document.getElementById('tax-rate-field');
            taxRateField.style.display = taxableSelect.value === 'yes' ? 'block' : 'none';
        }

        // Initial toggle based on the default value
        toggleTaxRate();

        // Add event listener to the taxable select element
        document.querySelector('select[name="taxable"]').addEventListener('change', toggleTaxRate);

        // Function to add another product field
        function addProductField() {
            const productSection = document.getElementById('product-section');
            const newProductRow = document.createElement('div');
            newProductRow.classList.add('row', 'product-item');
            newProductRow.innerHTML = `
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="product_ids">Select Another Product</label>
                        <select name="product_ids[]" class="form-control product-select" required>
                            <option value="">Choose Another Product</option>
                            <?php foreach ($products_array as $product): ?>
                            <option value="<?php echo $product['product_id']; ?>" data-price="<?php echo $product['price']; ?>">
                                <?php echo $product['product_name']; ?> - ₹<?php echo $product['price']; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="quantities">Product Quantity</label>
                        <input type="number" name="quantities[]" class="form-control" required>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="price">Product Price</label>
                        <input type="number" name="price[]" class="form-control" value="" required>
                    </div>
                </div>
            `;
            productSection.appendChild(newProductRow);
            // Add event listener to the new select element
            newProductRow.querySelector('select[name="product_ids[]"]').addEventListener('change', updateProductPrice);
        }

        // Add event listener to the Add Another Product button
        document.getElementById('add-product').addEventListener('click', addProductField);

        // Function to update price based on selected product
        function updateProductPrice(event) {
            const selectedOption = event.target.selectedOptions[0];
            const priceInput = event.target.closest('.product-item').querySelector('input[name="price[]"]');
            if (selectedOption) {
                priceInput.value = selectedOption.getAttribute('data-price');
            }
        }

        // Add event listener to all existing product select elements
        document.querySelectorAll('select[name="product_ids[]"]').forEach(select => {
            select.addEventListener('change', updateProductPrice);
        });
    });
    </script>

