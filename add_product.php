<?php
ob_start();
include('./includes/header.php');
$user_id = $_SESSION['userid'];
include('./includes/db_connection.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$messages = [];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = trim($_POST['p_name']);
    $hsn_sac_no = trim($_POST['p_hsn']);
    $price = trim($_POST['p_price']);
    $discount = trim($_POST['p_discount']);
    $p_unit = trim($_POST['p_unit']);
    $created_at = date('Y-m-d H:i:s');

    // Handle file upload
    $product_image = null;
    if (!empty($_FILES['p_image']['name'])) {
        $target_dir = "products/";
        $product_image = $target_dir . basename($_FILES["p_image"]["name"]);
        if (move_uploaded_file($_FILES["p_image"]["tmp_name"], $product_image)) {
            $messages[] = "File uploaded successfully.";
        } else {
            $messages[] = "File upload failed.";
        }
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO products (product_name, product_image, hsn_sac_no, price, discount, p_unit, created_at, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        $messages[] = "Error preparing statement: " . $conn->error;
    } else {
        $stmt->bind_param("sssssssi", $product_name, $product_image, $hsn_sac_no, $price, $discount, $p_unit, $created_at, $user_id);

        if ($stmt->execute()) {
            $messages[] = "Product added successfully.";
            header("Location: product_list.php");
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
    <title>OM Software - Company List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="MyraStudio" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
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
                            <h4 class="mb-0 font-size-18">Add Product</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                    <li class="breadcrumb-item active">Add Product</li>
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
                                <form action="add_product.php" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="simpleinput">Product Name</label>
                                                <input type="text" id="simpleinput" name="p_name" class="form-control" placeholder="Enter product name" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="exampleFormControlInput1">HSN/SAC No.</label>
                                                <input type="text" class="form-control" name="p_hsn" id="exampleFormControlInput1" placeholder="Enter HSN/SAC No." required>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="simpleinput">Price</label>
                                                <input type="text" id="simpleinput" name="p_price" class="form-control" placeholder="Enter price" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label>Discount</label>
                                                <select name="p_discount" class="form-control" required>
                                                    <option value="0">None</option>
                                                    <option value="5">5%</option>
                                                    <option value="10">10%</option>
                                                    <option value="15">15%</option>
                                                    <option value="20">20%</option>
                                                    <option value="25">25%</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label>Unit</label>
                                                <select name="p_unit" class="form-control" required>
                                                    <option value="meter">Meter</option>
                                                    <option value="kg">Kg</option>
                                                    <option value="piece">Piece</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label>Product Image</label>
                                                <div class="custom-file">
                                                    <input type="file" name="p_image" class="custom-file-input" id="customFile" required>
                                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3" style="margin-top:27px">
                                            <button class="btn btn-success waves-effect waves-light" type="submit">Add</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
    </div>
    <!-- End Page-content -->
    <?php include('./includes/footer.php')?>