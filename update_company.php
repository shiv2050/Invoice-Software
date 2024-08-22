<?php
include('./includes/db_connection.php');

// Check if form is submitted
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['c_name'];
    $email = $_POST['c_email'];
    $phone = $_POST['c_phone'];
    $address = $_POST['c_address'];
    $gst = $_POST['c_gst'];
    $logo = $_FILES['c_logo']['name'];
    $logoTemp = $_FILES['c_logo']['tmp_name'];
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($logo);

    if (!empty($logo)) {
        // Check if file upload is successful
        if (move_uploaded_file($logoTemp, $uploadFile)) {
            // Update record with the new logo path
            $sql = "UPDATE company_profile SET company_name=?, email=?, phone=?, address=?, gst_number=?, company_logo=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $name, $email, $phone, $address, $gst, $uploadFile, $id);
        } else {
            echo "Error uploading file.";
            exit();
        }
    } else {
        // If no new logo is uploaded, keep the old logo
        $sql = "SELECT company_logo FROM company_profile WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $logo = $row['company_logo'];
        
        // Update record without changing the logo
        $sql = "UPDATE company_profile SET company_name=?, email=?, phone=?, address=?, gst_number=?, company_logo=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $name, $email, $phone, $address, $gst, $logo, $id);
    }

    // Execute the update query
    if ($stmt->execute()) {
        header("Location: company_list.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
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
   
    <?php
        $id = $_GET['id'];
        $sql = "SELECT * FROM company_profile WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $company = $result->fetch_assoc();
        ?>
       
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
                                <h4 class="mb-0 font-size-18">Update Company List</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                        <li class="breadcrumb-item active">Update Company List</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-6 mx-auto">
                             <div class="card card-animate">
                                <div class="card-body">
                                <form action="update_company.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $company['id']; ?>">
            <div class="form-group">
                <label for="c_name">Company Name</label>
                <input type="text" id="c_name" name="c_name" class="form-control" value="<?php echo htmlspecialchars($company['company_name']); ?>">
            </div>
            <div class="form-group">
                <label for="c_email">Email</label>
                <input type="email" id="c_email" name="c_email" class="form-control" value="<?php echo htmlspecialchars($company['email']); ?>">
            </div>
            <div class="form-group">
                <label for="c_phone">Phone</label>
                <input type="text" id="c_phone" name="c_phone" class="form-control" value="<?php echo htmlspecialchars($company['phone']); ?>">
            </div>
            <div class="form-group">
                <label for="c_address">Address</label>
                <textarea id="c_address" name="c_address" class="form-control"><?php echo htmlspecialchars($company['address']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="c_gst">GST Number</label>
                <input type="text" id="c_gst" name="c_gst" class="form-control" value="<?php echo htmlspecialchars($company['gst_number']); ?>">
            </div>
            <div class="form-group">
                <label for="c_logo">Company Logo</label>
                <input type="file" id="c_logo" name="c_logo" class="form-control">
                <img src="<?php echo htmlspecialchars($company['company_logo']); ?>" width="100">
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
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