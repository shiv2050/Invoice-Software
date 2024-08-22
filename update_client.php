<?php
include('./includes/db_connection.php');

// Check if form is submitted
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['cl_name'];
    $email = $_POST['cl_email'];
    $phone = $_POST['cl_phone'];
    $address = $_POST['cl_address'];
    $gst = $_POST['cl_gst'];

    if (!empty($logo)) {
        // Check if file upload is successful
        if (move_uploaded_file($logoTemp, $uploadFile)) {
            // Update record with the new logo path
            $sql = "UPDATE client SET client_name=?, email=?, phone=?, address=?, gst_number=? WHERE client_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $name, $email, $phone, $address, $gst, $id);
        } else {
            echo "Error uploading file.";
            exit();
        }
    } else {
        
        // Update record without changing the logo
        $sql = "UPDATE client SET client_name=?, email=?, phone=?, address=?, gst_number=? WHERE client_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $name, $email, $phone, $address, $gst, $id);
    }

    // Execute the update query
    if ($stmt->execute()) {
        header("Location: client_list.php");
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
        $sql = "SELECT * FROM client WHERE client_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $client = $result->fetch_assoc();
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
                                <form action="update_client.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $client['client_id']; ?>">
            <div class="form-group">
                <label for="c_name">Client Name</label>
                <input type="text" id="c_name" name="cl_name" class="form-control" value="<?php echo htmlspecialchars($client['client_name']); ?>">
            </div>
            <div class="form-group">
                <label for="c_email">Email</label>
                <input type="email" id="c_email" name="cl_email" class="form-control" value="<?php echo htmlspecialchars($client['email']); ?>">
            </div>
            <div class="form-group">
                <label for="c_phone">Phone</label>
                <input type="text" id="c_phone" name="cl_phone" class="form-control" value="<?php echo htmlspecialchars($client['phone']); ?>">
            </div>
            <div class="form-group">
                <label for="c_address">Address</label>
                <textarea id="c_address" name="cl_address" class="form-control"><?php echo htmlspecialchars($client['address']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="c_gst">GST Number</label>
                <input type="text" id="c_gst" name="cl_gst" class="form-control" value="<?php echo htmlspecialchars($client['gst_number']); ?>">
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