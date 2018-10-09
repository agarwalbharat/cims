<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 6/8/2018
 * Time: 6:35 AM
 */

session_start();
include ("../../config/database.php");
if(isset($_SESSION['id']) && isset($_SESSION['username'])){
$id = $_SESSION['id'];
$sid = $_SESSION['username'];
$sql_profile = "SELECT * FROM students WHERE sid = '$sid'";
$sql_profile_check = mysqli_query($conn, $sql_profile);
$sql_profile_check_result = mysqli_num_rows($sql_profile_check);
while($rows = mysqli_fetch_assoc($sql_profile_check)){
    $fname = $rows['fname'];
    $lname = $rows['lname'];
    $email = $rows['email'];
    $mobile = $rows['phone'];
    $address = $rows['address'];
    $city = $rows['district'];
    $state = $rows['state'];
    $postal_code = $rows['postalcode'];
    $fees = $rows['fee'];
    $scholarship = $rows['scholarship'];
    $paid_fees = $rows['paidfee'];
    $pid = $rows['pid'];
    $status = $rows['status'];
    $center = $rows['center'];
    $course = $rows['course'];
    $batch = $rows['batch'];
    $class = $rows['class'];
    $fathername = $rows['fathername'];
    $fathermob = $rows['fathermob'];
    $fatheroccu = $rows['fatheroccu'];
    $mothername = $rows['mothername'];
    $mothermob = $rows['mothermob'];
    $motheroccu = $rows['motheroccu'];
    if(isset($rows['10mark'])){
    $mark10 = $rows['10mark'];
    }
    if(isset($rows['12mark'])){
    $mark12 = $rows['12mark'];
    }
    $pre_exam = $rows['preexam'];
    $pre_exam_marks = $rows['preexammarks'];
    $mentor = $rows['mentor'];
    $timing = $rows['timing'];
    $date_of_joinig = $rows['dateofreg'];
    $pre_exam_year = $rows['preexamyear'];
}

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $fname.' '.$lname ?>-Students-CIMS</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <style>
        a{
            text-decoration: none;
        }
        a:hover{
            text-decoration: none;
        }
    </style>
</head>
<body>
<h2 align="center" style="color: blue"><?php echo ucfirst($center) . ' (' . strtoupper($course) . ')' ?></h2>
<div class="header">

    <span style="font-size:30px;cursor:pointer" class="logo" onclick="openNav()">&#9776; open </span>

    <div class="header-right">
        <a href="profile.php">
            <?php echo $fname . " " . $lname . " (" . strtoupper($sid) . ")" ?></a>
    </div>
</div>
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="index.php" class="logo"><span style="color:red;font-size:70px">CIMS</span></a>
    <a href="profile.php"><?php echo $fname . " " . $lname . " (" . strtoupper($sid) . ")" ?></a>
    <a href="index.php">Home</a>
    <a href="attendance.php">Attendance</a>
    <a href="timetable.php">TimeTable</a>
    <a href="marks.php">Marks</a>
    <a href="fees.php">Fees</a>
    <a href="complaint.php">Complaint</a>
    <a href="password_update.php">Update Password</a>
    <a href="../../logout.php">Logout</a>
</div>
<div class="container">
    <div class="jumbotron">
        <div class="row">
            <div class="col-md-4 col-xs-12 col-sm-6 col-lg-4">
                <img src="images/default_pic.png" alt="stack photo" class="img">
            </div>
            <div class="col-md-8 col-xs-12 col-sm-6 col-lg-8">
                <div class="container" style="border-bottom:1px solid black">
                    <h2><?php echo $fname.' '.$lname.' ( Mentor:'.$mentor.')'; ?></h2>
                </div>
                <hr>
                <ul class="container details">
                    <li><p><span class="glyphicon glyphicon-ok-sign" style="width:50px;"></span><?php echo $sid.' ( PID:'.$pid.')'; ?></p></li>
                    <li><p><span class="glyphicon glyphicon-earphone one" style="width:50px;"></span><?php echo '+91 '.$mobile; ?></p></li>
                    <li><p><span class="glyphicon glyphicon-envelope one" style="width:50px;"></span><?php echo $email; ?></p></li>
                    <li><p><span class="glyphicon glyphicon-map-marker one" style="width:50px;"></span><?php echo ucfirst($center).'('.strtoupper($course).')' ?></p></li>
                    <li><p><span class="glyphicon glyphicon-tower" style="width:50px;"></span><?php echo $batch.' ('.strtoupper($timing).')'; ?></p></li>
                </ul>
            </div>
        </div>
    </div>
    <div align="center">
        <p><b><i>Address:</i></b><?php echo $address.', '. $city.', '.$state.', '.$postal_code ?></p>
        <p><b><i>Total Fee:</i></b><?php echo $fees; ?> &nbsp; &nbsp; <b><i>Scholarship:</i></b><?php echo $scholarship.'%' ?> &nbsp;&nbsp;<b><i>Total Fee To Pay:</i></b><?php $newfee = $fees-($fees*$scholarship)/100; echo $newfee ?> &nbsp; &nbsp; <b><i>Total Paid Fees:</i></b><?php echo $paid_fees; ?> &nbsp;&nbsp; <b><i>Fees To Pay:</i></b><?php echo $newfee-$paid_fees;  ?> &nbsp; &nbsp;&nbsp;<a href="fees.php"><button>Pay</button></a></p>
        <p><b><i>Class: </i></b><?php echo $class; ?> &nbsp; &nbsp; <?php if(isset($mark10)){echo '<b><i>Class 10 Marks: </i></b>'.$mark10; } ?> &nbsp;&nbsp; <?php if(isset($mark12)){echo '<b><i>Class 12 Marks:</i></b>'.$mark12; } ?>&nbsp</p>
        <p><b><i>Previous Exam Attempted: </i></b><?php echo $pre_exam.' ( '.$pre_exam_year.')'; ?> &nbsp;&nbsp; <b><i>Previous Exam Marks: </i></b><?php echo $pre_exam_marks; ?></p>
        <p><b><i>Father's Name: </i></b><?php echo ucfirst($fathername); ?> &nbsp;&nbsp; <b><i>Father's Occupation: </i></b><?php echo ucfirst($fatheroccu); ?> &nbsp;&nbsp; <b><i>Father's Mobile:</i></b> <?php echo '+91 '.$fathermob; ?></p>
        <p><b><i>Mother's Name: </i></b><?php echo ucfirst($mothername); ?> &nbsp;&nbsp; <b><i>Mother's Occupation: </i></b><?php echo ucfirst($motheroccu); ?> &nbsp;&nbsp; <b><i>Mother's Mobile:</i></b> <?php echo '+91 '.$mothermob; ?></p>
        <p><button onclick="showsome()">Update Details</button></p>
    </div>
    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }

        function showsome() {
            alert("To Update Details Kindly Contact Your Class Mentor.");
        }
    </script>
</body>
</html>
<?php } ?>