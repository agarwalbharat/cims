<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 6/6/2018
 * Time: 7:34 PM
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
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Attendance-Students-CIMS</title>
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
                <th>Subject</th>
                <th>Timing</th>
                <th>Status</th>
                <th>Teacher</th>
                <th>Teacher ID (EID)</th>
            </tr>
            <?php
            $sqli = "SELECT * FROM attendance WHERE sid = '$sid' AND course = '$course' AND center = '$center' AND batch = '$batch' AND date = '$ydate'";
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
                    $textcolor = "green";
                } else if ($status == 'a' OR $status == 'A') {
                    $status = "Absent";
                    $color = "red";
                    $textcolor = "white";
                }
                $sql_teacher = "SELECT * FROM teachers WHERE eid = '$eid'";
                $sql_result = mysqli_query($conn, $sql_teacher);
                $sql_result_teacher = mysqli_num_rows($sql_result);
                while ($rowsn = mysqli_fetch_assoc($sql_result)) {
                    $teacherfname = $rowsn['fname'];
                    $teacherlname = $rowsn['lname'];

                }

                ?>
                <tr style="background-color:<?php echo $color; ?>;color: <?php echo $textcolor; ?>">
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
    header("Location: ../../index.php");
}
?>