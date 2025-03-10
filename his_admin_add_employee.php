<!--Server side code to handle  Patient Registration-->
<?php
session_start();
include('assets/inc/config.php');

if (isset($_POST['add_doc'])) {
    // Check if all required fields are filled
    if (
        empty($_POST['doc_fname']) || 
        empty($_POST['doc_lname']) || 
        empty($_POST['doc_number']) || 
        empty($_POST['doc_email']) || 
        empty($_POST['doc_pwd'])
    ) {
        die("Error: All fields are required. Please fill in all details.");
    }

    $doc_fname = $_POST['doc_fname'];
    $doc_lname = $_POST['doc_lname'];
    $doc_number = $_POST['doc_number'];
    $doc_email = $_POST['doc_email'];
    $doc_pwd = sha1($_POST['doc_pwd']); // Encrypt password

    // Check if the employee already exists in the database
    $check_query = "SELECT * FROM his_docs WHERE doc_number = ? OR doc_email = ?";
    $check_stmt = $mysqli->prepare($check_query);

    if (!$check_stmt) {
        die("Prepare failed: " . $mysqli->error); // Debugging for prepare failure
    }

    $check_stmt->bind_param("ss", $doc_number, $doc_email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Employee already exists
        $err = "Employee with this number or email already exists.";
    } else {
        // Proceed to insert the employee
        $insert_query = "INSERT INTO his_docs (doc_fname, doc_lname, doc_number, doc_email, doc_pwd) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $mysqli->prepare($insert_query);

        if (!$insert_stmt) {
            die("Prepare failed: " . $mysqli->error);
        }

        $insert_stmt->bind_param("sssss", $doc_fname, $doc_lname, $doc_number, $doc_email, $doc_pwd);
        if ($insert_stmt->execute()) {
            echo "Employee added successfully.";
        } else {
            die("Error: Could not execute query. " . $insert_stmt->error);
        }
    }
}
?>

<!--End Server Side-->
<!--End Patient Registration-->
<!DOCTYPE html>
<html lang="en">
    
    <!--Head-->
    <?php include('assets/inc/head.php');?>
    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
            <?php include("assets/inc/nav.php");?>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
            <?php include("assets/inc/sidebar.php");?>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="his_admin_dashboard.php">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Employee</a></li>
                                            <li class="breadcrumb-item active">Add Employee</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Add Employee Details</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 
                        <!-- Form row -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Fill all fields</h4>
                                        <!--Add Patient Form-->
                                        <form method="post">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputEmail4" class="col-form-label">First Name</label>
                                                    <input type="text" required="required" name="doc_fname" class="form-control" id="inputEmail4" >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="inputPassword4" class="col-form-label">Last Name</label>
                                                    <input required="required" type="text" name="doc_lname" class="form-control"  id="inputPassword4">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-2" style="display:none">
                                                    <?php 
                                                        $length = 5;    
                                                        $patient_number =  substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
                                                    ?>
                                                    <label for="inputZip" class="col-form-label">Doctor Number</label>
                                                    <input type="text" name="doc_number" value="<?php echo $patient_number;?>" class="form-control" id="inputZip">
                                                </div>

                                            <div class="form-group">
                                                <label for="inputAddress" class="col-form-label">Email</label>
                                                <input required="required" type="email" class="form-control" name="doc_email" id="inputAddress">
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputCity" class="col-form-label">Password</label>
                                                    <input required="required" type="password" name="doc_pwd" class="form-control" id="inputCity">
                                                </div>
                                                
                                            </div>

                                            <button type="submit" name="add_doc" class="ladda-button btn btn-success" data-style="expand-right">Add Employee</button>

                                        </form>
                                        <!--End Patient Form-->
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                    </div> <!-- container -->

                </div> <!-- content -->

                <!-- Footer Start -->
                <?php include('assets/inc/footer.php');?>
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

       
        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- App js-->
        <script src="assets/js/app.min.js"></script>

        <!-- Loading buttons js -->
        <script src="assets/libs/ladda/spin.js"></script>
        <script src="assets/libs/ladda/ladda.js"></script>

        <!-- Buttons init js-->
        <script src="assets/js/pages/loading-btn.init.js"></script>
        
    </body>

</html>