<?php include('./includes/header.php');
// Retrieve user ID from session
$user_id = $_SESSION['userid'];

include('./includes/db_connection.php');

// Function to fetch count of records
function fetchCount($conn, $table, $user_id) {
    $sql = "SELECT COUNT(*) as count FROM $table WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    return 0;
}

// Fetch counts
$companyCount = fetchCount($conn, 'company_profile', $user_id);
$clientCount = fetchCount($conn, 'client', $user_id);
$expendlistCount = fetchCount($conn, 'expenses', $user_id);
$productCount = fetchCount($conn, 'products', $user_id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>OM Software- Admin & Dashboard</title>
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
                    <div class="col-lg-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0 font-size-18">Dashboard</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                    <li class="breadcrumb-item active">Dashboard</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <!-- Cards displaying counts -->
                    <?php
                    $cards = [
                        ['icon' => 'bx-building', 'label' => 'Company', 'count' => $companyCount],
                        ['icon' => 'bx-user', 'label' => 'Clients', 'count' => $clientCount],
                        ['icon' => 'bx-money', 'label' => 'Expendlist', 'count' => $expendlistCount],
                        ['icon' => 'bx-file', 'label' => 'Products', 'count' => $productCount],
                    ];
                    foreach ($cards as $card) {
                        echo "<div class='col-xl-3 col-md-6'>
                                <div class='card card-animate'>
                                    <div class='card-body'>
                                        <div class='avatar-sm float-right'>
                                            <span class='avatar-title bg-soft-primary rounded-circle'>
                                                <i class='bx {$card['icon']} m-0 h3 text-primary'></i>
                                            </span>
                                        </div>
                                        <h6 class='text-muted text-uppercase mt-0'>{$card['label']}</h6>
                                        <h3 class='my-3'>{$card['count']}</h3>
                                    </div>
                                </div>
                            </div>";
                    }
                    ?>
                </div>
                <!-- end row -->

                <!-- Table for Company Profiles -->
                <div class='row'>
                    <div class='col-lg-12'>
                        <div class='card card-animate'>
                            <div class='card-body'>
                                <h4 class='card-title d-inline-block'>All Companies</h4>
                                <div class='table-responsive'>
                                    <table class='table dt-responsive nowrap'>
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
                                            $stmt = $conn->prepare('SELECT * FROM company_profile WHERE user_id = ?');
                                            $stmt->bind_param("i", $user_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            if ($result && $result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td><img src='" . htmlspecialchars($row['company_logo']) . "' alt='Logo' width='30'></td>";
                                                    echo "<td>" . htmlspecialchars($row['company_name']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['gst_number']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                                    echo "<td>
                                                            <a href='update_company.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-success btn-sm waves-effect waves-light'>Update</a>
                                                            <a href='delete_company.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-danger btn-sm waves-effect waves-light'>Delete</a>
                                                          </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='8'>No records found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table for Clients -->
                <div class='row'>
                    <div class='col-lg-12'>
                        <div class='card card-animate'>
                            <div class='card-body'>
                                <h4 class='card-title d-inline-block'>All Clients</h4>
                                <div class='table-responsive'>
                                    <table class='table dt-responsive nowrap'>
                                        <thead>
                                            <tr>
                                                <th>Client Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Address</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $stmt = $conn->prepare('SELECT * FROM client WHERE user_id = ?');
                                            $stmt->bind_param("i", $user_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            if ($result && $result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($row['client_name']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                                    echo "<td>
                                                            <a href='update_client.php?id=" . htmlspecialchars($row['client_id']) . "' class='btn btn-success btn-sm waves-effect waves-light'>Update</a>
                                                            <a href='delete_client.php?id=" . htmlspecialchars($row['client_id']) . "' class='btn btn-danger btn-sm waves-effect waves-light'>Delete</a>
                                                          </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='6'>No records found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table for Expenses -->
                <div class='row'>
                    <div class='col-lg-12'>
                        <div class='card card-animate'>
                            <div class='card-body'>
                                <h4 class='card-title d-inline-block'>All Expenses</h4>
                                <button class='btn btn-primary mb-3 no-print' onclick='printTable()'>Print Table</button>
                                <div class='table-responsive'>
                                    <table class='table dt-responsive nowrap'>
                                        <thead>
                                            <tr>
                                                <!-- Add appropriate columns for expenses -->
                                                <th>Expense Name</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $stmt = $conn->prepare('SELECT * FROM expenses WHERE user_id = ?');
                                            $stmt->bind_param("i", $user_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            if ($result && $result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                                                    
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5'>No records found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table for Products -->
                <div class='row'>
                    <div class='col-lg-12'>
                        <div class='card card-animate'>
                            <div class='card-body'>
                                <h4 class='card-title d-inline-block'>All Products</h4>
                                <div class='table-responsive'>
                                    <table class='table dt-responsive nowrap'>
                                        <thead>
                                            <tr>
                                            <th>Logo</th>
                                                <th>Product Name</th>
                                                <th>Price</th>
                                                <th>Rate</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $stmt = $conn->prepare('SELECT * FROM products WHERE user_id = ?');
                                            $stmt->bind_param("i", $user_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            if ($result && $result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td><img src='" . htmlspecialchars($row["product_image"]) . "' alt='Image' width='50'></td>";
                                                    echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                                                    echo "<td>
                                                            <a href='update_product.php?id=" . htmlspecialchars($row['product_id']) . "' class='btn btn-success btn-sm waves-effect waves-light'>Update</a>
                                                            <a href='delete_product.php?id=" . htmlspecialchars($row['product_id']) . "' class='btn btn-danger btn-sm waves-effect waves-light'>Delete</a>
                                                          </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5'>No records found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- container-fluid -->
        </div> <!-- End Page-content -->
             <?php include('./includes/footer.php')?>