<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 7/1/2018
 * Time: 3:07 AM
 */
session_start();
if(isset($_SESSION['id']) && isset($_SESSION['username'])){
    include("../../../config/database.php");
    $idn = $_SESSION['id'];
    $eid = $_SESSION['username'];
    $sql = "SELECT * FROM teachers WHERE eid = '$eid'";
    $result = mysqli_query($conn, $sql);
    $resultcheck = mysqli_num_rows($result);
    if ($row = mysqli_fetch_assoc($result)) {
        $fname = ucfirst($row['fname']);
        $lname = ucfirst($row['lname']);
        $center = $row['center'];
        $course = $row['course'];
        $batchmentor = $row['batchmentor'];
    }
    $ydate = date('Y-m-d');
$day = date("l");
$find_admin_sql = "SELECT * FROM teachers WHERE position = 'admin' AND center = '$center' AND course = '$course'";
$find_admin_result = mysqli_query($conn,$find_admin_sql);
$find_admin_result_check = mysqli_num_rows($find_admin_result);
if($findrows = mysqli_fetch_assoc($find_admin_result)){
    $admin_eid = $findrows['eid'];
}else{
    $admin_eid = "Admin Does not Exist";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Complaint-Mentor-CIMS</title>
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
    <a href="batchmentorstudents.php">Students of <?php echo $batchmentor?></a>
    <a href="markattendance.php">Mark Attendance</a>
    <a href="markmarks.php">Mark Marks</a>
    <a href="timetable.php">TimeTable</a>
    <a href="complaint.php">Complaint</a>
    <a href="incomingcomplaint.php">Incoming Complaint</a>
    <a href="../../../logout.php">Logout</a>
</div>
<br>
<div class="container" style="float:left;border-left: 6px solid red">
    <form method="post">
        <h3 align="center">Complaint Form</h3>
        <label for="teacher">To Whom</label>
        <select name="teacher">
            <option value="none">Select One</option>
            <option value="admin">Admin</option>
        </select>
        <label for="subject">Subject</label>
        <input type="text" name="subject" placeholder="Type Subject..">
        <label for="complaint">Complaint</label>
        <textarea name="complaint" placeholder="Write something.." style="height:200px"></textarea>

        <input type="submit" value="submit" name="submit">
    </form>
</div>
<div style="float: right;border-left: 6px solid red;" class="container" align="center">
    <h3 align="center">Complaint History</h3>
    <table border="2">
        <tr>
            <th>S.No.</th>
            <th>To Whom</th>
            <th>EID</th>
            <th>Subject</th>
            <th>Date Of Complaint</th>
            <th>See Reply</th>
        </tr>
        <?php
        $complaint_sql = "SELECT * FROM complaint WHERE username = '$eid' AND center = '$center' AND course = '$course'";
        $complaint_sql_result = mysqli_query($conn,$complaint_sql);
        $complaint_sql_result_check = mysqli_num_rows($complaint_sql_result);
        $i=0;
        while($complaint_rows = mysqli_fetch_assoc($complaint_sql_result)){
            $i++;
            $id = $complaint_rows['id'];
            $to_whom = $complaint_rows['teacher_type'];
            $eid_show = $complaint_rows['eid'];
            $subject_show = $complaint_rows['subject'];
            $date_of_complaint_show = $complaint_rows['dateofcomp'];
            ?>
            <tr>
                <th><?php echo $i;?></th>
                <th><?php echo $to_whom; ?></th>
                <th><?php echo $eid_show; ?></th>
                <th><?php echo $subject_show; ?></th>
                <th><?php echo $date_of_complaint_show; ?></th>
                <th><a href="complaint_seereply.php?id=<?php echo $id; ?>">See Reply</a> </th>
            </tr>
            <?php
        }
        ?>
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
    input[type=text], select, textarea {
        width: 100%;
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

    .container {
        width: 50%;
        border-radius: 5px;
        background-color: #f2f2f2;
        padding: 20px;
    }
</style>
</body>

    </html>
    <?php

    if(isset($_POST['submit'])){
        $teacher = $_POST['teacher'];
        if($teacher=='admin'){
            $eid_type = $admin_eid;
        }
        $subject = $_POST['subject'];
        $compl = $_POST['complaint'];
        $date_of_complaint = date("Y-m-d");
        $sql_comp = "INSERT INTO complaint (eid,teacher_type,username,center,course,subject,complaint,dateofcomp) VALUES ('$eid_type','$teacher','$eid','$center','$course','$subject','$compl','$date_of_complaint')";
        $sql_comp_result = mysqli_query($conn,$sql_comp);
        if($sql_comp_result){
            echo '<script>alert("Successful")</script>';
        }else{
            echo '<script>alert("Contact Admin")</script>';
        }
    }


}else{
    header("Location: ../../../index.php");
}
?>

