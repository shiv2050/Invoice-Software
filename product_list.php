<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>OM Software - Product List</title>
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
                                <h4 class="mb-0 font-size-18">Product List</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                        <li class="breadcrumb-item active">Product List</li>
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
                                    <table id="basic-datatable" class="table dt-responsive nowrap">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Product Name</th>
                                                <th>HSN/SAC No</th>
                                                <th>Price</th>
                                                <th>Discount</th>
                                                <th>Unit</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    
                                        <tbody>
                                            <?php

                                                include('./includes/db_connection.php');
                                                
                                                // Check connection
                                                if ($conn->connect_error) {
                                                    die("Connection failed: " . $conn->connect_error);
                                                }
                                                
                                                $user_id = $_SESSION['userid'];
                                                // Fetch data for the logged-in user
                                                $sql = "SELECT * FROM products WHERE user_id = ?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->bind_param("i", $user_id);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                           

                                            if ($result->num_rows > 0) {
                                                // Output data of each row
                                                while($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td><img src='" . htmlspecialchars($row["product_image"]) . "' alt='Image' width='50'></td>";
                                                    echo "<td>" . htmlspecialchars($row["product_name"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["hsn_sac_no"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["price"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["discount"]) . "%</td>";
                                                    echo "<td>" . htmlspecialchars($row["p_unit"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                                                    echo "<td><a href='update_product.php?id=" . htmlspecialchars($row["product_id"]) . "' class='btn btn-success btn-sm waves-effect waves-light'>Update</a> 
                                                          <a href='delete_product.php?id=" . htmlspecialchars($row["product_id"]) . "' class='btn btn-danger btn-sm waves-effect waves-light'>Delete</a></td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='8'>No records found</td></tr>";
                                            }

                                            // Close connection
                                            $conn->close();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row-->
                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            <?php include('./includes/footer.php');?>
        </div>
        <!-- End Page -->
    </div>
