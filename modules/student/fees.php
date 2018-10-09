<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 6/8/2018
 * Time: 6:27 AM
 */
session_start();
if(isset($_SESSION['id']) && isset($_SESSION['username'])){
    include("../../config/database.php");
    $id = $_SESSION['id'];
    $sid = $_SESSION['username'];
    $sql = "SELECT * FROM students WHERE sid = '$sid'";
    $result = mysqli_query($conn, $sql);
    $resultcheck = mysqli_num_rows($result);
    if ($row = mysqli_fetch_assoc($result)) {
        $fname = ucfirst($row['fname']);
        $lname = ucfirst($row['lname']);
        $center = $row['center'];
        $course = $row['course'];
        $batch = $row['batch'];
    }
    $ydate = date('Y-m-d');
    $day = date("l");
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Marks-Students-CIMS</title>
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
    <div style="padding-left:20px; float: left;border-left: 6px solid red;background-color: lightgrey;width: 100%;">
        <h1 align="center">Fees - <span style="color: blue"><?php echo $fname.' '.$lname; ?></span></h1>
        <table border="2" align="center" cellpadding="5px">
            <tr>
                <th>SID</th>
                <th>Center</th>
                <th>Course</th>
                <th>Batch</th>
                <th>Total Fees</th>
                <th>Scholarship</th>
                <th>Total Fee To Pay</th>
                <th>Total Paid Fees</th>
                <th>Fees To Pay</th>
            </tr>
            <?php
                $sqli = "SELECT * FROM students WHERE sid = '$sid' AND course = '$course' AND center = '$center' AND batch = '$batch'";
            $resulti = mysqli_query($conn, $sqli);
            $resultchecki = mysqli_num_rows($resulti);
            while ($rows = mysqli_fetch_assoc($resulti)) {
                $center = $rows['center'];
                $course = $rows['course'];
                $batch = $rows['batch'];
                $fees = $rows['fee'];
                $scholarship = $rows['scholarship'];
                $paid_fees = $rows['paidfee'];
                $newfee = $fees-($fees*$scholarship)/100;

                ?>
                <tr align="center">
                    <td><?php echo strtoupper($sid); ?></td>
                    <td><?php echo ucfirst($center); ?></td>
                    <td><?php echo strtoupper($course); ?></td>
                    <td><?php echo ucfirst($batch); ?></td>
                    <td><?php echo $fees; ?></td>
                    <td><?php echo $scholarship .'%'; ?></td>
                    <td><?php echo $newfee; ?></td>
                    <td><?php echo $paid_fees ?></td>
                    <td><?php echo $newfee-$paid_fees; ?></td>
                </tr>
                <tr>
                    <td colspan="9" align="center"><button class="feepay">Pay Fees</button></td>
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
    <style>
        .feepay{
            width: 200px;
            font-size: 20px;
            color: red;
            border-radius: 10px;
            border-color: green;
        }
        .feepay:hover{
            background-color: green;
            color: white;
        }
    </style>
    </body>
    </html>
    <?php
}else{
    header("Location: ../../index.php");
}
?>
