<?php 

// Start output buffering
ob_start();
include('./includes/header.php');
include('./includes/db_connection.php');
// Retrieve user ID from session
$user_id = $_SESSION['userid'];
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$messages = [];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_name = trim($_POST['cl_name']);
    $email = trim($_POST['cl_email']);
    $phone = trim($_POST['cl_phone']);
    $address = trim($_POST['cl_address']);
    $gst_number = trim($_POST['cl_gst']);
    $created_at = date('Y-m-d H:i:s');

    // Check for duplicates
    $checkSql = "SELECT * FROM client WHERE email = ? OR phone = ? OR gst_number = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("sss", $email, $phone, $gst_number);

    if ($checkStmt->execute()) {
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $messages[] = "A client with the same email, phone number, or GST number already exists.";
        } else {
            // Prepare and bind for insertion
            $insertSql = "INSERT INTO client (client_name, email, phone, address, gst_number, created_at, user_id) VALUES (?, ?, ?, ?, ?, ?,?)";
            $insertStmt = $conn->prepare($insertSql);

            if ($insertStmt === false) {
                $messages[] = "Error preparing statement: " . $conn->error;
            } else {
                $insertStmt->bind_param("ssssssi", $client_name, $email, $phone, $address, $gst_number, $created_at, $user_id);
                
                if ($insertStmt->execute()) {
                    $messages[] = "New Client added successfully.";
                    header("Location: client_list.php");
                    exit(); // Ensure the script stops after redirection
                } else {
                    $messages[] = "Error executing statement: " . $insertStmt->error;
                }

                // Close statement
                $insertStmt->close();
            }
        }

        // Close the check statement
        $checkStmt->close();
    } else {
        $messages[] = "Error executing check query: " . $checkStmt->error;
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
    <title>OM Software - Client List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="MyraStudio" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->

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

                                <h4 class="mb-0 font-size-18">Add Client List</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                        <li class="breadcrumb-item active">Add Client List</li>
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
                                    <form action="add_client.php" method="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="simpleinput">Client Name</label>
                                                    <input type="text" id="simpleinput" name="cl_name"
                                                        class="form-control" placeholder="Enter your client name"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="exampleFormControlInput1">Email address</label>
                                                    <input type="email" class="form-control" name="cl_email"
                                                        id="exampleFormControlInput1" placeholder="name@example.com"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="simpleinput">Client Gst No.</label>
                                                    <input type="text" id="simpleinput" name="cl_gst"
                                                        class="form-control" placeholder="Enter your client gst no."
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="simpleinput">Client Gst No.</label>
                                                    <input type="text" id="simpleinput" name="cl_gst"
                                                        class="form-control" placeholder="Enter your client gst no."
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label>Phone</label>
                                                    <input type="text" name="cl_phone" class="form-control"
                                                        data-toggle="input-mask" data-mask-format="0000-0000"
                                                        placeholder="Enter your client phone no." required>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="exampleFormControlTextarea1">Client Address</label>
                                                    <textarea class="form-control" name="cl_address"
                                                        id="exampleFormControlTextarea1" rows="1" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-3" style="margin-top:27px">
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