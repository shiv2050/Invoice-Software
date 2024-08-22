<?php
include('./includes/db_connection.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$messages = [];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = trim($_POST['head']);
    $created_at = date('Y-m-d H:i:s');

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO ex_head (name, created_at) VALUES (?, ?)");
    
    if ($stmt === false) {
        $messages[] = "Error preparing statement: " . $conn->error;
    } else {
        $stmt->bind_param("ss", $name, $created_at);
        
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
                                    <form action="add_head.php" method="POST">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="head">Head</label>
                                                    <input type="text" id="head" name="head" class="form-control"
                                                        placeholder="Enter the head" required>
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
            <!-- End Page-content -->
            <?php include('./includes/footer.php')?>