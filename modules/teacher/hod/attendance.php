<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 7/4/2018
 * Time: 4:21 AM
 */

session_start();
if(isset($_SESSION['id']) && isset($_SESSION['username'])){
    include("../../../config/database.php");
    $id = $_SESSION['id'];
    $eid = $_SESSION['username'];
    $sql = "SELECT * FROM teachers WHERE eid = '$eid'";
    $result = mysqli_query($conn, $sql);
    $resultcheck = mysqli_num_rows($result);
    if ($row = mysqli_fetch_assoc($result)) {
        $fname = ucfirst($row['fname']);
        $lname = ucfirst($row['lname']);
        $center = $row['center'];
        $course = $row['course'];
    }
    $ydate = date('Y-m-d');
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Attendance-HOD's-CIMS</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
    <h2 align="center" style="color: blue"><?php echo ucfirst($center) . ' (' . strtoupper($course) . ')' ?></h2>
    <div class="header">

        <span style="font-size:30px;cursor:pointer" class="logo" onclick="openNav()">&#9776; open </span>

        <div class="header-right">
            <a href="profile.php">
                <?php echo $fname . " " . $lname . " (" . strtoupper($eid) . ")" ?></a>
        </div>
    </div>
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="index.php" class="logo"><span style="color:red;font-size:70px">CIMS</span></a>
        <a href="profile.php"><?php echo $fname . " " . $lname . " (" . strtoupper($eid) . ")" ?></a>
        <a href="index.php">Home</a>
        <a href="attendance.php">Attendance</a>
        <a href="search.php">Search Student Information</a>
        <a href="searchteacher.php">Search Teacher Information</a>
        <a href="markattendance.php">Mark Attendance</a>
        <a href="markmarks.php">Mark Marks</a>
        <a href="timetable.php">TimeTable</a>
        <a href="complaint.php">Complaint</a>
        <a href="update_password.php">Update Password</a>
        <a href="../../../logout.php">Logout</a>
    </div>
    <div align="center" style="padding: 8px">

        <?php
        if (isset($_POST['submit'])) {
            $ydate = $_POST['date'];

        }
        $timestamp = strtotime($ydate);

        $day = date('l', $timestamp);
        ?>
        <form action="attendance.php" method="post">
            <h3>Choose date (mm/dd/yyyy)</h3>
            <input type="date" name="date" value="<?php echo $ydate; ?>">
            <input type="submit" name="submit" value="submit">
        </form>
    </div>
    <div style="padding-left:20px; float: left;border-left: 6px solid red;background-color: lightgrey;width: 100%">
        <h1 align="center">Attendance - <span style="color: blue"><?php echo $fname.' '.$lname; ?></span></h1>
        <p align="center"><?php echo $ydate; ?> (<?php echo $day; ?>)</p>

        <table border="2" align="center" cellpadding="5px">
            <tr>
                <th>S.NO.</th>
                <th>Time To Come</th>
                <th>Time To Go</th>
                <th>Status</th>
                <th>By</th>
                <th>By (EID)</th>
            </tr>
            <?php
            $sqli = "SELECT * FROM tea_attendance WHERE eid = '$eid' AND course = '$course' AND center = '$center' AND date = '$ydate'";
            $resulti = mysqli_query($conn, $sqli);
            $resultchecki = mysqli_num_rows($resulti);
            $i = 0;
            while ($rows = mysqli_fetch_assoc($resulti)) {
                $i++;
                $timetocome = $rows['timetocome'];
                $timetogo = $rows['timetogo'];
                $status = $rows['status'];
                $bid = $rows['bywhom'];
                if ($status == 'p' OR $status == 'P') {
                    $status = "Present";
                    $color = "#d3d3d3";
                    $textcolor = "green";
                } else if ($status == 'a' OR $status == 'A') {
                    $status = "Absent";
                    $color = "red";
                    $textcolor = "white";
                }
                $sql_teacher = "SELECT * FROM teachers WHERE eid = '$bid'";
                $sql_result = mysqli_query($conn, $sql_teacher);
                $sql_result_teacher = mysqli_num_rows($sql_result);
                while ($rowsn = mysqli_fetch_assoc($sql_result)) {
                    $teacherfname = $rowsn['fname'];
                    $teacherlname = $rowsn['lname'];

                }

                ?>
                <tr style="background-color:<?php echo $color; ?>;color: <?php echo $textcolor; ?>">
                    <td><?php echo $i; ?></td>
                    <td><?php echo $timetocome; ?></td>
                    <td><?php echo $timetogo; ?></td>
                    <td><?php echo $status; ?></td>
                    <td><?php echo $teacherfname . ' ' . $teacherlname ?></td>
                    <td><?php echo ucfirst($bid); ?></td>
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
        input[type=date]{
            width: 15%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-top: 6px;
            margin-bottom: 16px;
            resize: vertical;
        }

        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }
    </style>
    </body>
    </html>
    <?php
}else{
    header("Location: ../../../index.php");
}
