<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 6/5/2018
 * Time: 7:46 PM
 */

session_start();
if(isset($_SESSION['id']) && isset($_SESSION['username'])){
include("../../config/database.php");
$id = $_SESSION['id'];
$sid = $_SESSION['username'];
$sql = "SELECT * FROM students WHERE sid = '$sid'";
$result = mysqli_query($conn, $sql);
$resultcheck = mysqli_num_rows($result);
if($row = mysqli_fetch_assoc($result)){
	$fname= ucfirst($row['fname']);
	$lname = ucfirst($row['lname']);
	$center = $row['center'];
	$course = $row['course'];
	$batch = $row['batch'];
	$status = $row['status'];
}
if($status == 'yes' || $status == 'Yes') {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Students-CIMS</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
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
    <div style="padding-left:20px; float: left;border-left: 6px solid red;background-color: lightgrey;width: 50%">
        <h1 align="center">Time Table</h1>
        <p align="center"><?php echo date("d-m-Y") . '<br>(' . date("l") . ')' ?></p>
        <table border="2" align="center" cellpadding="5px">
            <tr>
                <th>S.No</th>
                <th>Timing</th>
                <th>Subject name</th>
                <th>Subject Teacher</th>
                <th>Teacher ID(EID)</th>
            </tr>

            <?php
            $day = date("l");
            $sql_time = "SELECT * FROM timetable WHERE center = '$center' AND batch = '$batch' AND course = '$course' AND day ='$day'";
            $sql_time_result = mysqli_query($conn, $sql_time);
            $sql_time_result_check = mysqli_num_rows($sql_time_result);
            $j = 0;
            while ($rown = mysqli_fetch_assoc($sql_time_result)){
            $j++;
            $time = $rown['timing'];
            $subject = $rown['subject'];
            $eid = $rown['eid'];

            $sql_teacher1 = "SELECT * FROM teachers WHERE eid = '$eid'";
            $sql_result1 = mysqli_query($conn, $sql_teacher1);
            $sql_result_teacher1 = mysqli_num_rows($sql_result1);
            while ($rowsn1 = mysqli_fetch_assoc($sql_result1)) {
                $teacherfname1 = $rowsn1['fname'];
                $teacherlname1 = $rowsn1['lname'];

            }

            ?>
            <tr>
                <td><?php echo $j; ?></td>
                <td><?php echo $time; ?></td>
                <td><?php echo $subject ?></td>
                <td><?php echo $teacherfname1 . ' ' . $teacherlname1 ?></td>
                <td><?php echo $eid ?></td>

                <?php } ?>

        </table>
    </div>
    <div style="padding-left:20px; float: left;border-left: 6px solid red;background-color: lightgrey;width: 50%">
        <h1 align="center">Attendance</h1>
        <p align="center">Yesterday's Attendane<br>(<?php $ydate = date('Y-m-d', strtotime("-1 days"));
            echo date('d-m-Y', strtotime("-1 days")); ?>) </p>

        <table border="2" align="center" cellpadding="5px">
            <tr>
                <th>S.NO.</th>
                <th>Subject</th>
                <th>Timing</th>
                <th>Status</th>
                <th>Teacher</th>
                <th>Teacher ID (EID)</th>
            </tr>
            <?php
            $sqli = "SELECT * FROM attendance WHERE sid = '$sid' AND course = '$course' AND center = '$center' AND date = '$ydate'";
            $resulti = mysqli_query($conn, $sqli);
            $resultchecki = mysqli_num_rows($resulti);
            $i = 0;
            while ($rows = mysqli_fetch_assoc($resulti)) {
                $i++;
                $subject = $rows['subject'];
                $timing = $rows['timing'];
                $status = $rows['status'];
                $eid = $rows['eid'];
                if ($status == 'p' OR $status == 'P') {
                    $status = "Present";
                    $color = "#d3d3d3";
                } else if ($status == 'a' OR $status == 'A') {
                    $status = "Absent";
                    $color = "red";
                }
                $sql_teacher = "SELECT * FROM teachers WHERE eid = '$eid'";
                $sql_result = mysqli_query($conn, $sql_teacher);
                $sql_result_teacher = mysqli_num_rows($sql_result);
                while ($rowsn = mysqli_fetch_assoc($sql_result)) {
                    $teacherfname = $rowsn['fname'];
                    $teacherlname = $rowsn['lname'];

                }

                ?>
                <tr style="background-color:<?php echo $color; ?>;">
                    <td><?php echo $i; ?></td>
                    <td><?php echo ucfirst($subject); ?></td>
                    <td><?php echo $timing; ?></td>
                    <td><?php echo $status; ?></td>
                    <td><?php echo $teacherfname . ' ' . $teacherlname ?></td>
                    <td><?php echo ucfirst($eid); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }
    </script>
    </body>
    </html>
    <?php
}else{
    ?>
    <h1>Your account is deactivated by admin due to some reasons. kindly contact Admin for further.</h1>
<?php
}
}else{
    header("Location: ../../index.php");
}

?>