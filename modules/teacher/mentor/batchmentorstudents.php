<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 7/1/2018
 * Time: 10:27 PM
 */

session_start();
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
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
        $batchmentor = $row['batchmentor'];
    }
    if(isset($_GET['ret'])) {
        if ($_GET['ret'] == 'success') {
            echo '<script>alert("Update Successful")</script>';
        }
        if ($_GET['ret'] == 'cancel') {
            echo '<script>alert("Cancel Successful")</script>';
        }
    }
    $ydate = date('Y-m-d');
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $batchmentor; ?>-Mentor-CIMS</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <style>
           tr th  a{

                text-decoration: none;
                color: green;
               padding: 2px;
                border: 2px solid blue;
            }
           tr th  a:hover{
               background-color: blue;
               color: white;
           }
        </style>
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
        <a href="batchmentorstudents.php">Students of <?php echo $batchmentor ?></a>
        <a href="markattendance.php">Mark Attendance</a>
        <a href="markmarks.php">Mark Marks</a>
        <a href="timetable.php">TimeTable</a>
        <a href="complaint.php">Complaint</a>
        <a href="incomingcomplaint.php">Incoming Complaint</a>
        <a href="../../../logout.php">Logout</a>
    </div>
<?php if(!isset($_GET['studentid'])){ ?>
    <div align="center" style="background-color:lightgray;padding: 10px;">
        <h4>All Students Information</h4>
        <table border="2px" align="center" cellpadding="10px">
            <tr>
                <th>SID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>PID</th>
                <th>Timings</th>
                <th>Fees</th>
                <th>Scholarship</th>
                <th>Full Information</th>
            </tr>
            <?php
            $sql_student_mentor = "SELECT * FROM students WHERE batch = '$batchmentor' AND mentor = '$eid' AND center = '$center' AND course = '$course'";
            $sql_student_mentor_result = mysqli_query($conn,$sql_student_mentor);
            $sql_student_mentor_result_check = mysqli_num_rows($sql_student_mentor_result);
            if($sql_student_mentor_result_check > 0)
            {
                while ($result_reow = mysqli_fetch_assoc($sql_student_mentor_result)){
                    $id_student = $result_reow['sid'];

                    ?>
                    <tr>
                    <th><?php echo $result_reow['sid']?></th>
                    <th><?php echo $result_reow['fname'].' '.$result_reow['lname']; ?></th>
                    <th><?php echo $result_reow['email']; ?></th>
                    <th><?php echo $result_reow['phone']; ?></th>
                    <th><?php echo $result_reow['pid']; ?></th>
                    <th><?php echo $result_reow['timing']; ?></th>
                    <th><?php echo $result_reow['fee']; ?></th>
                    <th><?php echo $result_reow['scholarship'].'%'; ?></th>
                    <th><a href="batchmentorstudents.php?studentid=<?php echo $id_student?>">Click To see</a> </th>
                    </tr>
               <?php }
            }else{
                echo '<tr><td colspan="9" align="center">No result Found</td></tr>';
            }
            ?>

        </table>

    </div>
<?php }else{
    $id_get = mysqli_real_escape_string($conn,$_GET['studentid']);
$sql_student_mentor = "SELECT * FROM students WHERE sid = '$id_get' AND mentor = '$eid' AND center = '$center' AND course = '$course'";
$sql_student_mentor_result = mysqli_query($conn,$sql_student_mentor);
$sql_student_mentor_result_check = mysqli_num_rows($sql_student_mentor_result);
if($sql_student_mentor_result_check > 0) {
    if ($result_reow = mysqli_fetch_assoc($sql_student_mentor_result)) {?>
        <div align="center">
            <h4>Student Information of Batch <span style="color:blue;"><?php echo $batchmentor;?></span></h4>
        <table align="center" cellpadding="2px">

            <tr>
                <th>SID:</th>
                <td><?php echo $result_reow['sid']?></td>
                <th>PID:</th>
                <td><?php echo $result_reow['pid']?></td>
            </tr>
            <tr>
                <th>Name:</th>
                <td><?php echo $result_reow['fname'].' '.$result_reow['lname']?></td>
            </tr>
            <tr>
                <th>Batch(Course):</th>
                <td><?php echo $result_reow['batch']. '('.$result_reow['course'].')'?></td>
                <th>Timings:</th>
                <td><?php echo $result_reow['timing']?></td>
            </tr>
            <tr>
                <th>Email:</th>
                <td><a href="mailto:<?php echo $result_reow['email'];?>"><?php echo $result_reow['email'];?></a></td>
                <th>Phone:</th>
                <td><?php echo '+91 '.$result_reow['phone']?></td>
            </tr>
            <tr>
                <th>Address:</th>
                <td><?php echo ucfirst($result_reow['address']).', '.ucfirst($result_reow['district']).', '.ucfirst($result_reow['state']).', '.$result_reow['postalcode']?></td>
            </tr>
            <tr>
                <th>Fees:</th>
                <td><?php echo 'Rs '.$result_reow['fee']?></td>
            </tr>
            <tr>
                <th>ScholarShip:</th>
                <td><?php echo $result_reow['scholarship'].'%'?></td>
            </tr>
            <tr>
                <th>Paid Fees:</th>
                <td><?php echo 'Rs '.$result_reow['paidfee']?></td>
            </tr>
            <tr>
                <th>Father Name:</th>
                <td><?php echo ucfirst($result_reow['fathername']); ?></td>
                <th>Mother Name:</th>
                <td><?php echo ucfirst($result_reow['mothername']); ?></td>
            </tr>
            <tr>
                <th>Father Occupation:</th>
                <td><?php echo ucfirst($result_reow['fatheroccu']); ?></td>
                <th>Mother Occupation:</th>
                <td><?php echo ucfirst($result_reow['motheroccu']); ?></td>
            </tr>
            <tr>
                <th>Father Mobile:</th>
                <td><?php echo '+91 '.$result_reow['fathermob']?></td>
                <th>Mother Mobile:</th>
                <td><?php echo '+91 '.$result_reow['mothermob']?></td>
            </tr>
            <tr>
                <th>10 Marks:</th>
                <td><?php echo $result_reow['10mark']?></td>
                <th>12 Marks:</th>
                <td><?php echo $result_reow['12mark']?></td>
            </tr>
            <tr>
                <th>Previous Exam( Year):</th>
                <td><?php echo $result_reow['preexam'].' ('.$result_reow['preexamyear'].')'?></td>
                <th>Previous Exam Marks:</th>
                <td><?php echo $result_reow['preexammarks']?></td>
            </tr>
            <tr>
                <th>Date Of Joining:</th>
                <td><?php echo $result_reow['dateofreg']?></td>
                <th>Date Of Left:</th>
                <td><?php echo $result_reow['dateofleft']; ?></td>
            </tr>
            <tr>
                <th><?php echo '<a href="batchmentorstudents.php?studentid='.$id_get.'&type=attendance">Student Attendance</a>'; ?> </th>
                <th><?php echo '<a href="batchmentorstudents.php?studentid='.$id_get.'&type=marks">Student Marks</a>'; ?></th>
                <th><?php echo '<a href="updatestudent.php?studentid='.$id_get.'">Update Details</a>'; ?></th>
                <th><?php echo '<a href="batchmentorstudents.php">Go Back</a>'; ?></th>

            </tr>
        </table>
        </div>
    <?php }
}else{
    echo "<h1 align='center' style='color:red'>No result Found</h1><br><p align='center'><a href='batchmentorstudents.php'>Go Back</a></p>";

}
} ?>

    <?php
    if(isset($_GET['type'])){
        if($_GET['type']=='attendance'){
            $sid = mysqli_real_escape_string($conn,$_GET['studentid']);
            $sqli = "SELECT count(status) as total FROM attendance WHERE sid = '$sid' ";
            $resulti = mysqli_query($conn, $sqli);
            $result = mysqli_fetch_array($resulti);
            $total_classes = $result['total'];
            $sqli = "SELECT count(status) as total FROM attendance WHERE sid = '$sid' AND (status='p' OR status='P')";
            $resulti = mysqli_query($conn, $sqli);
            $result = mysqli_fetch_array($resulti);
            $present_total = $result['total'];
            $total_pre = ($present_total/$total_classes)*100;?>
            <div align="center" style="background-color: lightgray;padding: 10px;"><?php
                echo '<p><b>Total Classes: </b>'.$total_classes.' &nbsp;&nbsp; <b>Total Present: </b>'.$present_total.'</p><p><b>Total Precentage: </b>'.$total_pre.'%</p>';
                ?></div>
            <table border="2" align="center" cellpadding="5px">
                <tr>
                    <th>S.NO.</th>
                    <th>Subject</th>
                    <th>Timing</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Teacher</th>
                    <th>Teacher ID (EID)</th>
                </tr>
                <?php
                $sid = mysqli_real_escape_string($conn,$_GET['studentid']);
                $sqli = "SELECT * FROM attendance WHERE sid = '$sid' ORDER BY date";
                $resulti = mysqli_query($conn, $sqli);
                $resultchecki = mysqli_num_rows($resulti);
                $i = 0;
                while ($rows = mysqli_fetch_assoc($resulti)) {
                    $i++;
                    $subject = $rows['subject'];
                    $timing = $rows['timing'];
                    $status = $rows['status'];
                    $eid = $rows['eid'];
                    $date = $rows['date'];
                    if ($status == 'p' OR $status == 'P') {
                        $status = "Present";
                        $color = "#d3d3d3";
                        $textcolor = "green";
                    } else if ($status == 'a' OR $status == 'A') {
                        $status = "Absent";
                        $color = "red";
                        $textcolor = "white";
                    }
                    ?>
                    <tr style="background-color:<?php echo $color; ?>;color: <?php echo $textcolor; ?>">
                        <td><?php echo $i; ?></td>
                        <td><?php echo ucfirst($subject); ?></td>
                        <td><?php echo $timing; ?></td>
                        <td><?php echo $date; ?></td>
                        <td><?php echo $status; ?></td>
                        <td><?php echo $fname . ' ' . $lname ?></td>
                        <td><?php echo ucfirst($eid); ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php }
        if($_GET['type']=='marks'){ ?>
            <table border="2" align="center" cellpadding="5px">
                <tr>
                    <th>S.NO.</th>
                    <th>Subject</th>
                    <th>Exam</th>
                    <th>Date OF Exam</th>
                    <th>Marks Obtain</th>
                    <th>Total Marks</th>
                    <th>Precentage</th>
                </tr>
                <?php
                $sid = mysqli_real_escape_string($conn,$_GET['studentid']);
                $sqli = "SELECT * FROM marks WHERE sid = '$sid'";
                $resulti = mysqli_query($conn, $sqli);
                $resultchecki = mysqli_num_rows($resulti);
                $i = 0;
                while ($rows = mysqli_fetch_assoc($resulti)) {
                    $i++;
                    $subject = $rows['subject'];
                    $examname = $rows['examname'];
                    $dateofexam = $rows['dateofexam'];
                    $marksobtain = $rows['marksobtain'];
                    $totalmarks = $rows['totalmarks'];
                    $eid = $rows['eid'];
                    $percantage = ($marksobtain/$totalmarks)*100;
                    if($percantage >=75){
                        $background_color="green";
                    }else if($percantage <=40){
                        $background_color = "red";
                    }else{
                        $background_color = "#ff7b00";
                    }


                    ?>
                    <tr style="background-color: <?php echo $background_color; ?>;color: white;">
                        <td><?php echo $i; ?></td>
                        <td><?php echo ucfirst($subject); ?></td>
                        <td><?php echo ucfirst($examname); ?></td>
                        <td><?php echo $dateofexam; ?></td>
                        <td><?php echo $marksobtain; ?></td>
                        <td><?php echo $totalmarks; ?></td>
                        <td><?php echo $percantage.'%'; ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php }
    }
    ?>
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
} else {
    header("Location: ../../../index.php");
}
