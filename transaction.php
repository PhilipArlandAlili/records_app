<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="assets/img/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Light Bootstrap Dashboard - Free Bootstrap 4 Admin Dashboard by Creative Tim</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <!-- CSS Files -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/light-bootstrap-dashboard.css?v=2.0.0 " rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="assets/css/demo.css" rel="stylesheet" />
</head>

<body>
    <?php
    require_once('config/config.php');
    require_once('config/db.php');

    // require 'vendor/autoload.php';
    
    // $faker = Faker\Factory::create();
    
    // if (!$conn) {
    //     die("Connection failed: " . mysqli_connect_error());
    // }
    
    // for ($i = 0; $i < 200; $i++) {
    
    //     $datelog = mysqli_real_escape_string($conn, $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'));
    //     $documentcode = mysqli_real_escape_string($conn, $faker->numberBetween(100, 210));
    //     $action = mysqli_real_escape_string($conn, $faker->randomElement(['IN', 'OUT', 'COMPLETE']));
    //     $office = mysqli_real_escape_string($conn, $faker->numberBetween(1, 100));
    //     $employee = mysqli_real_escape_string($conn, $faker->numberBetween(1, 100));
    //     $remarks = mysqli_real_escape_string($conn, $faker->randomElement(['SIGNED', 'FOR APPROVAL']));
    
    //     $query = "INSERT INTO transaction(datelog, documentcode, action, office_id, employee_id, remarks) 
    //             VALUES ('$datelog', '$documentcode', '$action','$office', '$employee', '$remarks')";
    
    //     $result = mysqli_query($conn, $query);
    
    //     if (!$result) {
    //         die("Error: " . mysqli_error($conn));
    //     }
    // }
    
    //gets the value sent over search form
    $search = isset($_GET['search']) ? $_GET['search'] : '';


    //define total number of  results you want per page
    $results_per_page = 10;

    //find the total number of results/rows stored in the database
    $query = "SELECT * FROM transaction";
    $result = mysqli_query($conn, $query);
    $number_of_result = mysqli_num_rows($result);

    //determine the total number of pages available
    $number_of_page = ceil($number_of_result / $results_per_page);

    // determing which page number visitor is currently on
    if (!isset($_GET['page'])) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }

    // determing the sql LIMIT starting number for the results on the display page
    $page_first_result = ($page - 1) * $results_per_page;


    //Create query
    if (strlen($search) > 0) {
        $query = 'SELECT transaction.datelog, transaction.documentcode, transaction.action, office.name as office_name, CONCAT(employee.lastname, ", ", employee.firstname) as employee_fullname, transaction.remarks FROM employee, office, transaction WHERE transaction.employee_id = employee.id AND transaction.office_id = office.id AND transaction.documentcode = ' . $search . ' ORDER BY transaction.documentcode, transaction.datelog LIMIT ' . $page_first_result . ',' . $results_per_page;
    } else {
        $query = 'SELECT transaction.datelog, transaction.documentcode, transaction.action, office.name as office_name, CONCAT(employee.lastname, ", ", employee.firstname) as employee_fullname, transaction.remarks FROM employee, office, transaction WHERE transaction.employee_id = employee.id AND transaction.office_id = office.id ORDER BY transaction.documentcode, transaction.datelog LIMIT ' . $page_first_result . ',' . $results_per_page;
    }
    //Get result
    $result = mysqli_query($conn, $query);
    //Fetch the data
    $transactions = mysqli_fetch_all($result, MYSQLI_ASSOC);
    //Free reulst
    mysqli_free_result($result);
    //Close the connection
    mysqli_close($conn);

    ?>

    <div class="wrapper">
        <div class="sidebar" data-image="../assets/img/sidebar-5.jpg" data-color="blue">
            <div class="sidebar-wrapper">
                <?php include('includes/sidebar.php'); ?>
            </div>
        </div>
        <div class="main-panel">
            <?php include('includes/navbar.php'); ?>
            <div class="content">
                <div class="container-fluid">
                    <div class="section">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card strpied-tabled-with-hover" style="border-radius: 50px; padding-bottom: 20px;">
                                <div class="col-md-12" style="text-align: end;">
                                    <form action="transaction.php" method="GET">
                                        <input type="text" name="search" style="margin-top: 30px;">
                                        <i class="fa fa-search"></i>
                                        <input type="submit" value="Search" class="btn btn-info btn-fill" style="cursor: pointer;">
                                    </form>
                                </div>
                                <div class="col-md-12" style="text-align: end;">
                                    <a href="/transaction-add.php">
                                        
                                        <button type="submit" class="btn btn-info btn-fill" style="margin-top: 15px; cursor: pointer;">Add New
                                            Transaction <i class="nc-icon nc-icon nc-delivery-fast"></i></button>
                                    </a>
                                </div>
                                <div class="card-header ">
                                    <h4 class="card-title">Transactions</h4>
                                    <p class="card-category">By: Philip Arland Alili</p>
                                </div>
                                <div class="card-body table-full-width table-responsive" style="padding: 20px;">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <th>Datelog</th>
                                            <th>Document Code</th>
                                            <th>Action</th>
                                            <th>Office</th>
                                            <th>Employee</th>
                                            <th>Remarks</th>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($transactions as $transaction): ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $transaction['datelog']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $transaction['documentcode']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $transaction['action']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $transaction['office_name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $transaction['employee_fullname']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $transaction['remarks']; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    for ($page = 1; $page <= $number_of_page; $page++) {
                        echo '<a href="transaction.php?page=' . $page . '" style="color: white;">' . " " . $page . '</a>';
                    }
                    ?>
                </div>
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <nav>
                        <ul class="footer-menu">
                            <li>
                                <a href="#">
                                    Home
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Company
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Portfolio
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Blog
                                </a>
                            </li>
                        </ul>
                        <p class="copyright text-center">
                            ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script>
                            <a href="http://www.creative-tim.com">Creative Tim</a>, made with love for a better web
                        </p>
                    </nav>
                </div>
            </footer>
        </div>
    </div>
</body>
<!--   Core JS Files   -->
<script src="assets/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
<script src="assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="assets/js/core/bootstrap.min.js" type="text/javascript"></script>
<!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
<script src="assets/js/plugins/bootstrap-switch.js"></script>
<!--  Google Maps Plugin    -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
<!--  Chartist Plugin  -->
<script src="assets/js/plugins/chartist.min.js"></script>
<!--  Notifications Plugin    -->
<script src="assets/js/plugins/bootstrap-notify.js"></script>
<!-- Control Center for Light Bootstrap Dashboard: scripts for the example pages etc -->
<script src="assets/js/light-bootstrap-dashboard.js?v=2.0.0 " type="text/javascript"></script>
<!-- Light Bootstrap Dashboard DEMO methods, don't include it in your project! -->
<script src="assets/js/demo.js"></script>

</html>