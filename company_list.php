<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>OM Software - Company List</title>
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
                                <h4 class="mb-0 font-size-18">Company List</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                        <li class="breadcrumb-item active">Company List</li>
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
                                            <th>Logo</th>
                                            <th>Company Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>GST Number</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                       
                                        // Check if user is logged in
                                        if (!isset($_SESSION['userid'])) {
                                            die("You need to be logged in to view this page.");
                                        }

                                        $user_id = $_SESSION['userid'];

                                        include('./includes/db_connection.php');
                                        
                                        // Check connection
                                        if ($conn->connect_error) {
                                            die("Connection failed: " . $conn->connect_error);
                                        }

                                        // Fetch data for the logged-in user
                                        $sql = "SELECT * FROM company_profile WHERE user_id = ?";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("i", $user_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result->num_rows > 0) {
                                            // Output data of each row
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td><img src='" . htmlspecialchars($row["company_logo"]) . "' alt='Logo' width='30' ></td>";
                                                echo "<td>" . htmlspecialchars($row["company_name"]) . "</td>";
                                                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                                echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
                                                echo "<td>" . htmlspecialchars($row["address"]) . "</td>";
                                                echo "<td>" . htmlspecialchars($row["gst_number"]) . "</td>";
                                                echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                                                echo "<td><a href='update_company.php?id=" . htmlspecialchars($row["id"]) . "' class='btn btn-success btn-sm waves-effect waves-light'>Update</a> 
                                                      <a href='delete_company.php?id=" . htmlspecialchars($row["id"]) . "' class='btn btn-danger btn-sm waves-effect waves-light'>Delete</a></td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='8'>No records found</td></tr>";
                                        }

                                        // Close statement and connection
                                        $stmt->close();
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
