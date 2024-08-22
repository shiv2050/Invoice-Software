<?php
include('./includes/header.php');
include('./includes/db_connection.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$messages = [];

// Fetch data from `ex_head` table
$heads = [];
$head_query = "SELECT id, name FROM ex_head";
$head_result = $conn->query($head_query);

if ($head_result) {
    while ($row = $head_result->fetch_assoc()) {
        $heads[] = $row;
    }
} else {
    $messages[] = "Error fetching heads: " . $conn->error;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = trim($_POST['name']);
    $head_id = trim($_POST['head']); // Changed to ID
    $amount = trim($_POST['amount']);
    $payment_mode = trim($_POST['payment_mode']);
    $payment_id = trim($_POST['pay_id']);
    $message = trim($_POST['message']);
    $created_at = date('Y-m-d H:i:s');

    // Check if user is logged in
    if (!isset($_SESSION['userid'])) {
        die("You need to be logged in to add an expense.");
    }

    $user_id = $_SESSION['userid'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO expenses (name, head, amount, payment_mode, payment_id, message, created_at, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        $messages[] = "Error preparing statement: " . $conn->error;
    } else {
        $stmt->bind_param("ssissssi", $name, $head_id, $amount, $payment_mode, $payment_id, $message, $created_at, $user_id);

        if ($stmt->execute()) {
            $messages[] = "Expense added successfully.";
            header("Location: expense_list.php");
            exit(); // Ensure the script stops after redirection
        } else {
            $messages[] = "Error executing statement: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8" />
    <title>OM Software - Expenses</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="MyraStudio" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <?php if (!empty($messages)): ?>
                            <div class="alert alert-info">
                                <?php echo implode('<br>', $messages); ?>
                            </div>
                            <?php endif; ?>
                            <div class="page-title-box d-flex align-items-center justify-content-between">

                                <h4 class="mb-0 font-size-18">Add Expense List</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                        <li class="breadcrumb-item active">Add Expense List</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <form action="add_expense.php" method="POST">
                                        <div class="row">
                                            <div class="col-lg-3">

                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text" id="name" name="name" class="form-control"
                                                        placeholder="Enter your name." required>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="head">Head</label>
                                                    <select name="head" id="head" class="form-control" required>
                                                        <option value="" disabled selected>Select Head</option>
                                                        <?php foreach ($heads as $head): ?>
                                                            <option value="<?php echo $head['id']; ?>">
                                                                <?php echo htmlspecialchars($head['name']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="amount">Amount</label>
                                                    <input type="number" class="form-control" name="amount" id="amount"
                                                        placeholder="99,99,00" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="paymentMode">Payment Mode</label>
                                                    <select name="payment_mode" id="paymentMode" class="form-control"
                                                        required>
                                                        <option value="" disabled selected>Select Payment Mode</option>
                                                        <option value="credit_card">Credit Card</option>
                                                        <option value="debit_card">Debit Card</option>
                                                        <option value="paypal">PayPal</option>
                                                        <option value="bank_transfer">Bank Transfer</option>
                                                        <option value="cash">Cash</option>
                                                        <option value="check">Check</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="pay_id">Payment Id</label>
                                                    <input type="text" name="pay_id" id="pay_id" class="form-control"
                                                        placeholder="Enter your payment id" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="message">Message</label>
                                                    <textarea class="form-control" name="message" id="message" rows="1"
                                                        required placeholder="Describe your expenses"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-3" style="margin-top:27px;">
                                                <button class="btn btn-success waves-effect waves-light"
                                                    type="submit">Add</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div> <!-- end col -->

                    </div>
                    <!-- end row-->

                </div> <!-- container-fluid -->
            </div>
            <?php include('./includes/footer.php')?>
