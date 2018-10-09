<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 6/17/2018
 * Time: 9:33 PM
 */

session_start();
if(isset($_SESSION['id']) && isset($_SESSION['username'])){
    if(isset($_GET['id'])) {
        include("../../config/database.php");
        $idn = $_SESSION['id'];
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
            $mentor = $row['mentor'];
        }
        $ydate = date('Y-m-d');

        $id = (int)$_GET['id'];
        //$id = mysqli_real_escape_string($conn,$_GET['id']);

        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>See reply-Students-CIMS</title>
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
        <h2 style="color: green; background-color: lightgray;padding: 10px" align="center">Details Of Complaint</h2>
<div style=" float: left;border: 6px solid red;width: 100%;border-radius: 20xp;" align="center">

<?php
$sql_complaint = "SELECT * FROM complaint WHERE id = '$id' AND username = '$sid'";
$sql_complaint_result = mysqli_query($conn,$sql_complaint);
$sql_complaint_result_check = mysqli_num_rows($sql_complaint_result);
if($sql_complaint_result_check>0) {
    while ($complaint_rows = mysqli_fetch_assoc($sql_complaint_result)) {
        ?>
        <h3><i class="show">Id No.-</i><?php echo $id; ?> &nbsp;&nbsp;<i class="show">SID-</i><?php echo $complaint_rows['username']; ?></h3><hr>
        <h3><i class="show">Teacher-</i><?php echo ucfirst($complaint_rows['teacher_type']); ?> &nbsp;&nbsp;<i class="show">EID-</i><?php echo $complaint_rows['eid']; ?></h3>
        <hr><h3><i class="show">Date Of Complaint-</i><?php echo $complaint_rows['dateofcomp']; ?></h3>
        <hr><h3><i class="show">Subject-</i><?php echo ucfirst($complaint_rows['subject']); ?></h3>
        <hr><h3><i class="show">Complaint</i></h3>
        <h3><?php echo ucfirst($complaint_rows['complaint']); ?></h3>
        <hr>
        <?php
        if ($complaint_rows['dateofreply'] == '0000-00-00') {
            ?>
            <h3><i class="reply"> There is no reply by <?php echo ucfirst($complaint_rows['teacher_type']); ?> </i></h3>
        <?php } else { ?>
            <h3><i class="show">Date Of Reply-</i><?php echo $complaint_rows['dateofreply']; ?></h3>
            <hr> <h3><i class="show">Reply by <?php echo ucfirst($complaint_rows['teacher_type']); ?></i></h3>
            <h3><?php echo ucfirst($complaint_rows['reply']); ?></h3><hr>
            <?php
        }
    }
}else{
    echo 'some thing went wrong contact to mentor';
}
?>
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
            .show{
                color: blue;
                font-size: 25px;
            }

            .reply{
                color: red;
                font-size: 25px;
            }

            hr{
                background-color: green;
                height: 5px;
            }
        </style>
        </body>

        </html>
        <?php
    }else{
        header("Location: complaint.php");
    }
        }else{
            header("Location: ../../index.php");
        }
?>


