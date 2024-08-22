
<?php
session_start(); // Start the session

// Retrieve user ID from session
include('./includes/db_connection.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$messages = [];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['emailaddress']);
    $password = trim($_POST['password']);

    // Prepare and bind
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashedPassword);
        $stmt->fetch();
        if (password_verify($password, $hashedPassword)) {
            // Login successful
            $_SESSION['userid'] = $id;
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $name;
            // Redirect to a protected page
            header("Location: dashboard.php");
            exit();
        } else {
            $messages[] = "Invalid password.";
        }
    } else {
        $messages[] = "No user found with this email address.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">



<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
    <meta charset="utf-8" />
    <title>Log In</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="MyraStudio" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/theme.min.css" rel="stylesheet" type="text/css" />

</head>

<body class="bg-light">

    <div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex align-items-center min-vh-100">
                        <div class="w-100 d-block my-5">
                            <div class="row justify-content-center">
                                <div class="col-md-8 col-lg-5">
                                     <div class="card">
                                        <div class="card-body">
                                            <div class="text-center mb-4 mt-3">
                                                <a href='index.php'>
                                                    <span><img src="assets/images/logo-dark.png" alt="" height="60"></span>
                                                </a>
                                            </div>
                                            <?php if (!empty($messages)): ?>
            <div class="alert alert-danger">
                <?php echo implode('<br>', $messages); ?>
            </div>
        <?php endif; ?>
                                            <form action="login.php" method="POST" class="p-2">
            <div class="form-group">
                <label for="emailaddress">Email address</label>
                <input class="form-control" type="email" id="emailaddress" name="emailaddress" required="" placeholder="john@deo.com">
            </div>
            <div class="form-group">
                <a class="text-muted float-right" href="pages-recoverpw.html">Forgot your password?</a>
                <label for="password">Password</label>
                <input class="form-control" type="password" required="" id="password" name="password" placeholder="Enter your password">
            </div>
            
            <div class="mb-3 text-center">
                <button class="btn btn-primary btn-block" type="submit"> Sign In </button>
            </div>
        </form>
                                        </div>
                                        <!-- end card-body -->
                                    </div>
                                    <!-- end card -->
            
                                    <div class="row mt-4">
                                        <div class="col-sm-12 text-center">
                                            <p class="text-dark mb-0">Create an account? <a class='text-dark ml-1' href='register.php'><b>Sign Up</b></a></p>
                                        </div>
                                    </div>
            
                                </div>
                                <!-- end col -->
                            </div>
                            <!-- end row -->
                        </div> <!-- end .w-100 -->
                    </div> <!-- end .d-flex -->
                </div> <!-- end col-->
            </div> <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <!-- jQuery  -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/metismenu.min.js"></script>
    <script src="assets/js/waves.js"></script>
    <script src="assets/js/simplebar.min.js"></script>

    <!-- App js -->
    <script src="assets/js/theme.js"></script>

</body>



</html>