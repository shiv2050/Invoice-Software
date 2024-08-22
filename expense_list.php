<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>OM Software - Expense List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="MyraStudio" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

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
                            <h4 class="mb-0 font-size-18">Expense List</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                    <li class="breadcrumb-item active">Expense List</li>
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
                                <!-- Add a button to print the entire table -->
                                <button class="btn btn-primary mb-3 no-print" onclick="printTable()">Print Table</button>
                                <table id="basic-datatable" class="table dt-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Head</th>
                                            <th>Amount</th>
                                            <th>Payment Mode</th>
                                            <th>Payment Id</th>
                                            <th>Message</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        // Check if the user is logged in
                                        if (!isset($_SESSION['userid'])) {
                                            die("You need to be logged in to view this page.");
                                        }

                                        $user_id = $_SESSION['userid']; // This will be used if needed, otherwise remove

                                        include('./includes/db_connection.php');

                                        // Check connection
                                        if ($conn->connect_error) {
                                            die("Connection failed: " . $conn->connect_error);
                                        }

                                        // Fetch data for the logged-in user
                                        $sql = "SELECT e.id, e.name, e.amount, e.payment_mode, e.payment_id, e.message, e.created_at, h.name AS head_name 
                                                FROM expenses e
                                                JOIN ex_head h ON e.head = h.id
                                                WHERE e.user_id = ?"; // Use the correct condition to filter by user_id

                                        $stmt = $conn->prepare($sql);

                                        if ($stmt === false) {
                                            die("Error preparing statement: " . $conn->error);
                                        }

                                        $stmt->bind_param("i", $user_id); // Bind the user_id parameter

                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        // Check for query execution errors
                                        if ($result === false) {
                                            echo "Error executing query: " . $stmt->error;
                                        } else {
                                            if ($result->num_rows > 0) {
                                                // Output data of each row
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["head_name"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["amount"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["payment_mode"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["payment_id"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["message"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                                                    echo "<td>
                                                        <a href='print_pdf.php?id=" . htmlspecialchars($row["id"]) . "' class='btn btn-info btn-sm waves-effect waves-light no-print'>Print PDF</a>
                                                        </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='8'>No records found</td></tr>";
                                            }
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
        <?php include('./includes/footer.php'); ?>
    </div>
    <!-- End Page -->

    <script>
    function printTable() {
        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Print Table</title>');
        printWindow.document.write('</head><body >');
        printWindow.document.write(document.getElementById('basic-datatable').outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
    }
    </script>
