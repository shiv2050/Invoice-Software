<?php
include('./includes/db_connection.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to validate email format
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to check password length
function isValidPasswordLength($password) {
    return strlen($password) >= 8 && strlen($password) <= 10;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['username']);
    $email = trim($_POST['emailaddress']);
    $password = trim($_POST['password']);

    $messages = [];

    // Server-side validation
    if (empty($name)) {
        $messages[] = "Name is required.";
    }

    if (empty($email)) {
        $messages[] = "Email address is required.";
    } elseif (!isValidEmail($email)) {
        $messages[] = "Invalid email format.";
    }

    if (empty($password)) {
        $messages[] = "Password is required.";
    } elseif (!isValidPasswordLength($password)) {
        $messages[] = "Password must be between 8 and 10 characters.";
    }

    // Check for duplicate email or username
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR name = ?");
    $stmt->bind_param("ss", $email, $name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $messages[] = "Email or username already exists.";
        $stmt->close();
    }

    if (!empty($messages)) {
        echo '<div class="alert alert-danger">' . implode('<br>', $messages) . '</div>';
        exit;
    }

    $stmt->close();

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    // Execute the query
    if ($stmt->execute()) {
        header("Location: login.php");
    } else {
        echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8" />
    <title>Register</title>
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
    <script>
    function validateForm() {
        const username = document.getElementById("username").value;
        const email = document.getElementById("emailaddress").value;
        const password = document.getElementById("password").value;

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const passwordPattern = /.{8,10}/;

        let messages = [];

        if (username === "") {
            messages.push("Name is required.");
        }

        if (email === "") {
            messages.push("Email address is required.");
        } else if (!emailPattern.test(email)) {
            messages.push("Please enter a valid email address.");
        }

        if (password === "") {
            messages.push("Password is required.");
        } else if (!passwordPattern.test(password)) {
            messages.push("Password must be between 8 and 10 characters.");
        }

        if (messages.length > 0) {
            const alertDiv = document.getElementById("alert");
            alertDiv.innerHTML = messages.join("<br>");
            alertDiv.style.display = "block";
            return false;
        }

        return true;
    }
    </script>

<body class="bg-light">

    <div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex align-items-center min-vh-100">
                        <div class="w-100 d-block my-5">
                            <div id="alert" class="alert alert-danger" style="display: none;"></div>

                            <div class="row justify-content-center">

                                <div class="col-md-8 col-lg-5">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="text-center mb-4 mt-3">
                                                <a href='index.php'>
                                                    <span><img src="assets/images/logo-dark.png" alt=""
                                                            height="60"></span>
                                                </a>
                                            </div>
                                            <form action="register.php" method="POST" class="p-2"
                                                onsubmit="return validateForm()">
                                                <div class="form-group">
                                                    <label for="username">Name</label>
                                                    <input class="form-control" type="text" id="username"
                                                        name="username" required="" placeholder="Michael Zenaty">
                                                </div>
                                                <div class="form-group">
                                                    <label for="emailaddress">Email address</label>
                                                    <input class="form-control" type="email" id="emailaddress"
                                                        name="emailaddress" required="" placeholder="john@deo.com">
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input class="form-control" type="password" required=""
                                                        id="password" name="password" placeholder="Enter your password">
                                                </div>
                                                <div class="form-group mb-4 pb-3">
                                                    <div class="custom-control custom-checkbox checkbox-primary">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="checkbox-signin" required>
                                                        <label class="custom-control-label" for="checkbox-signin">I
                                                            accept <a href="#">Terms and Conditions</a></label>
                                                    </div>
                                                </div>
                                                <div class="mb-3 text-center">
                                                    <button class="btn btn-primary btn-block" type="submit"> Sign Up
                                                        Free </button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- end card-body -->
                                    </div>
                                    <!-- end card -->

                                    <div class="row mt-4">
                                        <div class="col-sm-12 text-center">
                                            <p class="text-drak mb-0">Already have an account? <a class='text-drak ml-1'
                                                    href='login.php'><b>Sign In</b></a></p>
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