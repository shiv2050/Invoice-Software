<?php
// Start output buffering
ob_start();

include('./includes/header.php');

// Retrieve user ID from session
$user_id = $_SESSION['userid'];

include('./includes/db_connection.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$messages = [];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if terms and conditions are accepted
    if (!isset($_POST['terms'])) {
        $messages[] = "You must agree to the terms and conditions.";
    } else {
        $company_name = trim($_POST['c_name']);
        $email = trim($_POST['c_email']);
        $phone = trim($_POST['c_phone']);
        $address = trim($_POST['c_address']);
        $gst_number = trim($_POST['c_gst']);
        $created_at = date('Y-m-d H:i:s');
        $terms_accepted = 1; // Since the checkbox is checked

        // Check for existing record with the same company_name, email, phone, and gst_number
        $check_sql = "SELECT * FROM company_profile WHERE company_name = ? AND email = ? AND phone = ? AND gst_number = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ssss", $company_name, $email, $phone, $gst_number);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            // Record already exists
            $messages[] = "A company with the same name, email, phone, and GST number already exists.";
        } else {
            // Handle file upload
            $company_logo = null;
            if (!empty($_FILES['c_logo']['name'])) {
                $target_dir = "uploads/";
                $company_logo = $target_dir . basename($_FILES["c_logo"]["name"]);
                if (move_uploaded_file($_FILES["c_logo"]["tmp_name"], $company_logo)) {
                    $messages[] = "File uploaded successfully.";
                } else {
                    $messages[] = "File upload failed.";
                }
            }

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO company_profile (company_name, email, phone, address, gst_number, company_logo, created_at, terms_accepted, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssi", $company_name, $email, $phone, $address, $gst_number, $company_logo, $created_at, $terms_accepted, $user_id);

            if ($stmt->execute()) {
                $messages[] = "Company profile added successfully.";
                header("Location: company_list.php");
                exit(); // Ensure the script stops after redirection
            } else {
                $messages[] = "Error: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        }

        // Close check statement
        $check_stmt->close();
    }

    // Close connection
    $conn->close();
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

                                <h4 class="mb-0 font-size-18">Add Company List</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                        <li class="breadcrumb-item active">Add Company List</li>
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
                                    <form action="add_company.php" method="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="simpleinput">Company Name</label>
                                                    <input type="text" id="simpleinput" name="c_name"
                                                        class="form-control" placeholder="Enter your company name"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="exampleFormControlInput1">Email address</label>
                                                    <input type="email" class="form-control" name="c_email"
                                                        id="exampleFormControlInput1" placeholder="name@example.com"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="simpleinput">Company Gst No.</label>
                                                    <input type="text" id="simpleinput" name="c_gst"
                                                        class="form-control" placeholder="Enter your company gst no."
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label>Phone</label>
                                                    <input type="text" name="c_phone" class="form-control"
                                                        data-toggle="input-mask" data-mask-format="0000-0000"
                                                        placeholder="Enter your company phone no." required>

                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label>Company Logo</label>
                                                    <div class="custom-file">
                                                        <input type="file" name="c_logo" class="custom-file-input"
                                                            id="customFile">
                                                        <label class="custom-file-label" for="customFile">Choose
                                                            file</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="form-group">
                                                    <label for="exampleFormControlTextarea1">Company Address</label>
                                                    <textarea class="form-control" name="c_address"
                                                        id="exampleFormControlTextarea1" rows="1" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                            <label class="form-check-label" for="terms">
                                                I agree to the <a href="terms_conditions.php" target="_blank">Terms and Conditions</a>.
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                            <div class="col-lg-3">

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