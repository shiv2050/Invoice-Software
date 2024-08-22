<?php
include('./includes/db_connection.php');

// Ensure connection is established
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $taxable = $_POST['taxable'];
    $total_amount = $_POST['total_amount'];
    $tax_amount = $_POST['tax_amount'];
    $grand_total = $_POST['grand_total'];
    
    if ($taxable === 'yes') {
        $tax_rate = $_POST['tax_rate'];
        $sql = "UPDATE quotation SET taxable=?, tax_rate=?, total_amount=?, tax_amount=?, grand_total=?, updated_at=NOW() WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sddddi", $taxable, $tax_rate, $total_amount, $tax_amount, $grand_total, $id);
    } else {
        $sql = "UPDATE quotation SET taxable=?, total_amount=?, grand_total=?, updated_at=NOW() WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sddi", $taxable, $total_amount, $grand_total, $id);
    }

    if ($stmt->execute()) {
        header("Location: quotation_list.php");
        exit(); // Ensure no further code is executed after redirect
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

// Fetch the existing data for the form
$id = $_GET['id'];
$sql = "SELECT * FROM quotation WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$quotation = $result->fetch_assoc();

if (!$quotation) {
    die("Quotation not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>OM Software - Update Product</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="MyraStudio" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <?php include('./includes/header.php');?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 font-size-18">Update Quotation</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                        <li class="breadcrumb-item active">Update Quotation</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-lg-12 mx-auto">
                             <div class="card card-animate">
                                <div class="card-body">
                                    <form action="update_quotation.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($quotation['id']); ?>">
                                        <div class="row">
                                            
                                            <div class="col-lg-2">
                                            <div class="form-group">
                                            <label for="taxable">Taxable</label>
                                            <select id="taxable" name="taxable" class="form-control">
                                                <option value="yes" <?php if ($quotation['taxable'] === 'yes') echo 'selected'; ?>>Yes</option>
                                                <option value="no" <?php if ($quotation['taxable'] === 'no') echo 'selected'; ?>>No</option>
                                            </select>
                                        </div>
                                            </div>
                                            <div class="col-lg-2">
                                            <div class="form-group" id="tax_rate_field">
                                            <label for="tax_rate">Tax Rate (%)</label>
                                            <input type="text" id="tax_rate" name="tax_rate" class="form-control" value="<?php echo htmlspecialchars($quotation['tax_rate']); ?>">
                                        </div>
                                            </div>
                                            <div class="col-lg-2">
                                            <div class="form-group">
                                            <label for="total_amount">Total Amount</label>
                                            <input type="text" id="total_amount" name="total_amount" class="form-control" value="<?php echo htmlspecialchars($quotation['total_amount']); ?>">
                                        </div>
                                            </div>
                                            <div class="col-lg-2">
                                            <div class="form-group" id="tax_amount_field">
                                            <label for="tax_amount">Tax Amount</label>
                                            <input type="text" id="tax_amount" name="tax_amount" class="form-control" value="<?php echo htmlspecialchars($quotation['tax_amount']); ?>">
                                        </div>
                                            </div>
                                            <div class="col-lg-2">
                                            <div class="form-group">
                                            <label for="grand_total">Grand Total</label>
                                            <input type="text" id="grand_total" name="grand_total" class="form-control" value="<?php echo htmlspecialchars($quotation['grand_total']); ?>">
                                        </div>
                                            </div>
                                            <div class="col-lg-2" style="margin-top:27px"> 
                                               
                                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                       
                                        
                                        
                                       
                                        
                                        
                                    </form>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row-->
                </div> <!-- container-fluid -->
                <?php include('./includes/footer.php')?>
                <script>
        document.addEventListener('DOMContentLoaded', function() {
            const taxableSelect = document.getElementById('taxable');
            const taxRateField = document.getElementById('tax_rate_field');
            const taxRateInput = document.getElementById('tax_rate');
            const totalAmountInput = document.getElementById('total_amount');
            const taxAmountInput = document.getElementById('tax_amount');
            const grandTotalInput = document.getElementById('grand_total');

            function calculateTotals() {
                let totalAmount = parseFloat(totalAmountInput.value) || 0;
                let taxRate = parseFloat(taxRateInput.value) || 0;
                let taxAmount = 0;
                let grandTotal = totalAmount;

                if (taxableSelect.value === 'yes') {
                    taxAmount = (totalAmount * taxRate) / 100;
                    grandTotal = totalAmount + taxAmount;
                }

                taxAmountInput.value = taxAmount.toFixed(2);
                grandTotalInput.value = grandTotal.toFixed(2);
            }

            function toggleTaxRateField() {
                if (taxableSelect.value === 'yes') {
                    taxRateField.style.display = 'block';
                } else {
                    taxRateField.style.display = 'none';
                    taxRateInput.value = ''; // Clear tax rate if taxable is 'no'
                    taxAmountInput.value = '';
                    grandTotalInput.value = totalAmountInput.value;
                }
            }

            taxableSelect.addEventListener('change', function() {
                toggleTaxRateField();
                calculateTotals();
            });

            totalAmountInput.addEventListener('input', calculateTotals);
            taxRateInput.addEventListener('input', calculateTotals);

            toggleTaxRateField(); // Initial check
            calculateTotals(); // Initial calculation
        });
    </script>