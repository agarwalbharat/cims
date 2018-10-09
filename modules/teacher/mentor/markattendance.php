<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 6/30/2018
 * Time: 11:41 AM
 */

session_start();
if(isset($_SESSION['id']) && isset($_SESSION['username'])){
    include("../../../config/database.php");
    $id = $_SESSION['id'];
    $eid = $_SESSION['username'];
    $sql = "SELECT * FROM teachers WHERE eid = '$eid'";
    $result = mysqli_query($conn, $sql);
    $resultcheck = mysqli_num_rows($result);
    if($row = mysqli_fetch_assoc($result)){
        $fname= ucfirst($row['fname']);
        $lname = ucfirst($row['lname']);
        $center = $row['center'];
        $course = $row['course'];
        $status = $row['status'];
        $subject = $row['subject'];
        $batchmentor = $row['batchmentor'];
    }
    if($status == 'yes' || $status == 'Yes') {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Mark Attendance-Mentor-CIMS</title>
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
        <div align="center" style="background-color: lightgray;padding: 10px;border-left: 6px solid red; ">
            <h3>Mark Students Attendance</h3>
        </div>
        <br>
        <br>


        <!--get batch information in which today teacher has class -->
        <div align="center">
            <form method="get">
        <?php
        $get_day = date("l");
        $date = date('Y-m-d');
            $sql_get = "SELECT DISTINCT batch FROM timetable WHERE eid = '$eid' AND course = '$course' AND subject = '$subject' AND center = '$center' AND day='$get_day'";
            $sql_get_result = mysqli_query($conn,$sql_get);
            $sql_get_result_check = mysqli_num_rows($sql_get_result);
            if($sql_get_result_check>0)
            {

                ?>
                <b>Batch: </b><select name="batch" required>
                <option value="none">Select One</option>
               <?php  while($num_rows = mysqli_fetch_assoc($sql_get_result)){ ?>
                    <option value="<?php echo $num_rows['batch']?>"><?php echo $num_rows['batch']?></option>
               <?php } ?>
                </select>
                <?php
                $sql_get1 = "SELECT DISTINCT timing FROM timetable WHERE eid = '$eid' AND course = '$course' AND subject = '$subject' AND center = '$center' AND day='$get_day'";
                $sql_get_result1 = mysqli_query($conn,$sql_get1);
                ?>
               &nbsp;&nbsp; <b>Timings: </b><select name="timings" required>
                    <option value="none">Select One</option>
                <?php  while($num_rows1 = mysqli_fetch_assoc($sql_get_result1)){ ?>
                        <option value="<?php echo $num_rows1['timing']; ?>"><?php echo $num_rows1['timing']; ?></option>
                <?php } ?>
                </select>
                &nbsp;&nbsp;<b>Date: </b><input type="date" name="date" value="<?php echo $date?>" disabled>
                &nbsp;&nbsp;<b>Day: </b><input type="text" name="day" value="<?php echo $get_day ?>" disabled>

                <input type="submit">
            <?php }else{
                echo 'Nothing available for You. Kindly Contact Branch Admin';
            } ?>

            </form>
        </div>
<!--mark attendance-->
<?php if(isset($_GET['batch']) AND isset($_GET['timings'])){ ?>
        <div align="center" style="background-color: lightgray;padding: 10px;border-left: 6px solid red; ">
<h3>Mark attendance</h3>
            <?php
            $batch_get =$_GET['batch'];
            $timings_get =$_GET['timings'];
            $date_get = $date;

            $sql_get_students = "SELECT * FROM students WHERE batch = '$batch_get' AND course = '$course' AND center = '$center'";
            $sql_get_students_result = mysqli_query($conn,$sql_get_students);
            $sql_get_students_result_check =mysqli_num_rows($sql_get_students_result);
            if($sql_get_students_result_check>0){
                ?>
                <form method="post">
                    <table border="2px" cellpadding="7px">
                        <tr>
                            <th>SID</th>
                            <th>Name</th>
                            <th>Status</th>
                        </tr>
                    <?php while ($rows = mysqli_fetch_assoc($sql_get_students_result)){
                        $sid_get = $rows['sid'];
                        $name_get = $rows['fname'].' '.$rows['lname'];
                        ?>
                        <tr>
                        <td><input type="text" class="no" name="sid" value="<?php echo $sid_get;  ?>" disabled></td>
                        <td><input type="text" class="no" name="name" value="<?php echo $name_get; ?>" disabled></td>
                        <td><input type="radio" name="status[<?php echo $sid_get?>]" value="p" required>P
                        <input type="radio" name="status[<?php echo $sid_get?>]" value="a" required>A</td>
                        </tr>

                    <?php } ?>
                    </table>
                    <td><input type="submit" name="insert"></td>
                </form>

        </div>
                <?php
                if(isset($_POST['insert'])) {
                    foreach ($_POST['status'] as $sid_get => $status_insert) {
                        $eid_insert = $eid;
                        $date_insert = $date_get;
                        $batch_insert = $batch_get;
                        $timings_insert = $timings_get;
                        $center_insert = $center;
                        $subject_insert = $subject;
                        $course_insert = $course;
                        $sql_check_att = "SELECT * FROM attendance WHERE sid ='$sid_get' AND date = '$date_insert' AND subject='$subject' AND timing = '$timings_insert' AND center='$center_insert' ";
                        $sql_check_att_query = mysqli_query($conn,$sql_check_att);
                        $check = mysqli_num_rows($sql_check_att_query);
                        if($check >0)
                        {
                            echo '<script>alert("Attendance Already Marked");</script>';
                        }else {
                            $sql_insert = "INSERT INTO attendance (sid, date,timing, eid, batch, status, center, course, subject) VALUES ('$sid_get', '$date_insert', '$timings_insert', '$eid_insert', '$batch_insert', '$status_insert', '$center_insert','$course_insert','$subject_insert')";
                            $sql_insert_query = mysqli_query($conn, $sql_insert);
                            echo '<script>alert("Attendance Marked Successfully");</script>';
                        }
                    }
                }
            } ?>
<?php } ?>

        <script>
            function openNav() {
                document.getElementById("mySidenav").style.width = "250px";
            }

            function closeNav() {
                document.getElementById("mySidenav").style.width = "0";
            }
        </script>
        <style>
            input[type=date],input[type=text],select{
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
            .no{
            width: 100% !important;
            }
            </style>

        </body>
        </html>
        <?php
    }else{
        ?>
        <h1>Your account is deactivated by admin due to some reasons. kindly contact Admin for further.</h1>
        <?php
    }
}else{
    header("Location: ../../../index.php");
}

?>