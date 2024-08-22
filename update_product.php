<?php
include('./includes/db_connection.php');

// Check if form is submitted
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['p_name'];
    $hsn_sac_no = $_POST['p_hsn_sac_no'];
    $price = $_POST['p_price'];
    $discount = $_POST['p_discount'];
    $p_unit = $_POST['p_unit'];
    $image = $_FILES['p_image']['name'];
    $imageTemp = $_FILES['p_image']['tmp_name'];
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($image);

    if (!empty($image)) {
        // Check if file upload is successful
        if (move_uploaded_file($imageTemp, $uploadFile)) {
            $sql = "UPDATE products SET product_name=?, hsn_sac_no=?, price=?, discount=?, p_unit=?, product_image=? WHERE product_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdsdsi", $name, $hsn_sac_no, $price, $discount, $p_unit, $uploadFile, $id);
        } else {
            echo "Error uploading file.";
            exit();
        }
    } else {
        // If no new image is uploaded, keep the old image
        $sql = "SELECT product_image FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $image = $row['product_image'];

        $sql = "UPDATE products SET product_name=?, hsn_sac_no=?, price=?, discount=?, p_unit=?, product_image=? WHERE product_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $name, $hsn_sac_no, $price, $discount, $p_unit, $image, $id);
        
    }

    if ($stmt->execute()) {
        header("Location: product_list.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
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

    <?php
    include('./includes/db_connection.php');
    $id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    ?>
    <?php include('./includes/header.php'); ?>

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
                            <h4 class="mb-0 font-size-18">Update Product</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                    <li class="breadcrumb-item active">Update Product</li>
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
                                <form action="update_product.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                                    <div class="form-group">
                                        <label for="p_name">Product Name</label>
                                        <input type="text" id="p_name" name="p_name" class="form-control" value="<?php echo htmlspecialchars($product['product_name']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="p_hsn_sac_no">HSN/SAC No</label>
                                        <input type="text" id="p_hsn_sac_no" name="p_hsn_sac_no" class="form-control" value="<?php echo htmlspecialchars($product['hsn_sac_no']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="p_price">Price</label>
                                        <input type="text" id="p_price" name="p_price" class="form-control" value="<?php echo htmlspecialchars($product['price']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="p_discount">Discount (%)</label>
                                        <select id="p_discount" name="p_discount" class="form-control">
                                            <option value="0" <?php echo ($product['discount'] == '0') ? 'selected' : ''; ?>>0%</option>
                                            <option value="5" <?php echo ($product['discount'] == '5') ? 'selected' : ''; ?>>5%</option>
                                            <option value="10" <?php echo ($product['discount'] == '10') ? 'selected' : ''; ?>>10%</option>
                                            <option value="15" <?php echo ($product['discount'] == '15') ? 'selected' : ''; ?>>15%</option>
                                            <option value="20" <?php echo ($product['discount'] == '20') ? 'selected' : ''; ?>>20%</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="p_unit">Unit</label>
                                        <select id="p_unit" name="p_unit" class="form-control">
                                            <option value="kg" <?php echo ($product['p_unit'] == 'kg') ? 'selected' : ''; ?>>Kg</option>
                                            <option value="liter" <?php echo ($product['p_unit'] == 'liter') ? 'selected' : ''; ?>>Liter</option>
                                            <option value="piece" <?php echo ($product['p_unit'] == 'piece') ? 'selected' : ''; ?>>Piece</option>
                                            <option value="meter" <?php echo ($product['p_unit'] == 'meter') ? 'selected' : ''; ?>>meter</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="p_image">Product Image</label>
                                        <input type="file" id="p_image" name="p_image" class="form-control">
                                        <?php if (!empty($product['product_image'])): ?>
                                            <img src="<?php echo htmlspecialchars($product['product_image']); ?>" width="100">
                                        <?php endif; ?>
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

        <?php include('./includes/footer.php') ?>