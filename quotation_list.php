<?php
include('./includes/db_connection.php');
?>

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
                                <h4 class="mb-0 font-size-18">Quotation List</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                        <li class="breadcrumb-item active">Quotation List</li>
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
                                                <th>Client ID</th>
                                                <th>Quantities</th>
                                                <th>Taxable</th>
                                                <th>Tax Rate</th>
                                                <th>Total Amount</th>
                                                <th>Tax Amount</th>
                                                <th>Grand Total</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    
                                        <tbody>
                                            <?php
                                            // Check connection
                                            if ($conn->connect_error) {
                                                die("Connection failed: " . $conn->connect_error);
                                            }

                                            // Fetch data
                                            $sql = "SELECT * FROM quotation";
                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                // Output data of each row
                                                while($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($row["client_id"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["quantities"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["taxable"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["tax_rate"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["total_amount"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["tax_amount"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["grand_total"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["updated_at"]) . "</td>";
                                                    echo "<td>
                                                            <a href='view_quotation.php?id=" . htmlspecialchars($row["id"]) . "' class='btn btn-info btn-sm waves-effect waves-light'>View</a> 
                                                            <a href='view_invoice.php?id=" . htmlspecialchars($row["id"]) . "' class='btn btn-info btn-sm waves-effect waves-light'>View Invoice</a> 
                                                            <a href='update_quotation.php?id=" . htmlspecialchars($row["id"]) . "' class='btn btn-success btn-sm waves-effect waves-light'>Update</a> 
                                                            <a href='delete_quotation.php?id=" . htmlspecialchars($row["id"]) . "' class='btn btn-danger btn-sm waves-effect waves-light' onclick='return confirm(\"Are you sure you want to delete this quotation?\");'>Delete</a>
                                                          </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='11'>No records found</td></tr>";
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
                <?php include('./includes/footer.php')?>
    </div>
