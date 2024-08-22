<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}
?>
<!-- Plugins css -->
<link href="assets/plugins/datatables/dataTables.bootstrap4.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables/responsive.bootstrap4.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables/buttons.bootstrap4.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables/select.bootstrap4.css" rel="stylesheet" type="text/css" />

<!-- App favicon -->
<link rel="shortcut icon" href="assets/images/favicon.png">

<!-- App css -->
<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="assets/css/theme.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">
            <div data-simplebar class="h-100">
                <div class="navbar-brand-box">
                    <a class='logo' href='index-2.html'>
                        <img src="assets/images/logo-dark.png" alt="logo-dark" />
                    </a>
                </div>

                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu list-unstyled" id="side-menu">
                        <li class="menu-title">Menu</li>
                        <li>
                            <a class='waves-effect' href='dashboard.php'><i
                                    class='bx bx-home-smile'></i><span>Dashboard</span></a>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect"><i
                                    class="bx bx-building"></i><span>Company</span></a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href='company_list.php'>All Listed Company</a></li>
                                <li><a href='add_company.php'>Add Company</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect"><i
                                    class="bx bx-user"></i><span>All
                                    Clients</span></a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href='client_list.php'>All Listed Client</a></li>
                                <li><a href='add_client.php'>Add Client</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect"><i
                                    class="bx bx-money"></i><span>Expense List</span></a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href='expense_list.php'>All Expense List</a></li>
                                <li><a href='add_head.php'>Add Head</a></li>
                                <li><a href='add_expense.php'>Add Expense List</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect"><i
                                    class="bx bx-circle"></i><span>Products List</span></a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href='product_list.php'>All Products</a></li>
                                <li><a href='add_product.php'>Add Product</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect"><i
                                    class="bx bx-file"></i><span>Cotations</span></a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href='quotation_list.php'>All Cotations List</a></li>
                                <li><a href='quotation.php'>Add Cotations List</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!--- Sidemenu -->
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex align-items-left">
                    <button type="button" class="btn btn-sm mr-2 d-lg-none px-3 font-size-16 header-item waves-effect"
                        id="vertical-menu-btn">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                </div>

                <div class="d-flex align-items-center">

                    <div class="dropdown d-none d-sm-inline-block ml-2">
                        <button type="button" class="btn header-item noti-icon waves-effect"
                            id="page-header-search-dropdown" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="mdi mdi-magnify"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0"
                            aria-labelledby="page-header-search-dropdown">

                            <form class="p-3">
                                <div class="form-group m-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search ..."
                                            aria-label="Recipient's username">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i
                                                    class="mdi mdi-magnify"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown-2"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user" src="assets/images/logo.png"
                                alt="Header Avatar">
                            <span
                                class="d-none d-sm-inline-block ml-1"><?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                            <i class="mdi mdi-chevron-down d-none d-sm-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item d-flex align-items-center justify-content-between"
                                href="javascript:void(0)">
                                <span>Profile</span>
                            </a>
                            <a class="dropdown-item d-flex align-items-center justify-content-between"
                                href="javascript:void(0)">
                                Settings
                            </a>
                            <a class="dropdown-item d-flex align-items-center justify-content-between"
                                href="logout.php">
                                <span>Log Out</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </header>