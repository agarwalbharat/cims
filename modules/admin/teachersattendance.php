<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 7/9/2018
 * Time: 12:57 AM
 */

session_start();
if(isset($_SESSION['id']) && isset($_SESSION['username'])){
    include("../../config/database.php");
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
    }
    if($status == 'yes' || $status == 'Yes') {
        if(isset($_GET['res'])) {
            if ($_GET['res'] == 'success') {
                echo '<script>alert("Successfully done")</script>';
            }
            if ($_GET['res'] == 'fail') {
                echo '<script>alert("Failed Try Again")</script>';
            }
        }
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Admin-CIMS</title>
            <link rel="stylesheet" type="text/css" href="css/style.css">
            <style>
                .linking{
                    background-color: #ddffff;
                    padding: 7px;
                    text-decoration: none;
                }
                .linking:hover{
                    background-color: blue;
                    color: white;
                }

                input,button,select{
                    padding: 5px;
                    border: 2px solid blue;
                    border-radius: 10px;
                    margin: 2px;
                }
                input[type=submit],button{
                    width: 200px;
                }
                input:hover{
                    background-color: lightblue;
                }
                input[type=submit]:hover{
                    background-color: green;
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
            <a href="student.php">Student</a>
            <a href="studentattendance.php">Student Attendance</a>
            <a href="teachers.php">Teachers</a>
            <a href="teachersattendance.php">Teachers Attendance</a>
            <a href="add.php">Add TimeTable/batch</a>
            <a href="complaint.php">Complaint</a>
            <a href="incomingcomplaint.php">Incoming Complaint</a>
            <a href="update_password.php">Update Password</a>
            <a href="../../logout.php">Logout</a>
        </div>
        <div align="center" style="background-color: aquamarine;padding: 10px">
            <a href="teachersattendance.php?addattendance=true" class="linking">Add Attendance</a>
            <a href="teachersattendance.php?updateattendance=true" class="linking">Update Attendance</a>
        </div>

        <?php if(isset($_GET['addattendance'])) {?>
                <div align="center">
                    <h4>Mark Teachers Attendance</h4>
                    <form method="post">
                       EID :<select name="teacher_eid">
                            <option value="none">Select EID</option>
                        <?php
                            $sql_get_teacher = "SELECT * FROM teachers WHERE center='$center' AND course='$course' AND NOT position='admin' order by eid";
                            $sql_get_teacher_q = mysqli_query($conn,$sql_get_teacher);
                            while($teachereid = mysqli_fetch_assoc($sql_get_teacher_q)){
                        ?>
                        <option value="<?php echo $teachereid['eid'];?>"><?php echo $teachereid['eid'] ?></option>
                        <?php } ?>
                        </select>
                        Time To IN:<input type="text" name="timetoin" placeholder="Time To In">
                        Time To Leave: <input type="text" name="timetoout" placeholder="Time To Leave">
                        Status<select name="status">
                            <option>Select One</option>
                            <option value="p">Present</option>
                            <option value="a">Absent</option>
                        </select>
                        <br><input type="submit" name="mark" value="Mark">
                    </form>
                </div>
        <?php
            if(isset($_POST['mark'])){
                $get_eid = $_POST['teacher_eid'];
                $get_timein = $_POST['timetoin'];
                $get_timeout = $_POST['timetoout'];
                $get_status = $_POST['status'];
                $date = date('Y-m-d');

                $sql_get_results = "SELECT * FROM tea_attendance WHERE eid='$get_eid' AND date='$date' AND center='$center' AND course='$course'";
                $sql_get_results_q = mysqli_query($conn,$sql_get_results);
                $neeh = mysqli_num_rows($sql_get_results_q);
                if($neeh>0){
                    echo '<script>alert("Attendance Already Marked")</script>';
                }else{
                    $insert_into_tea_attendance = "INSERT INTO tea_attendance(eid, date, timetocome, timetogo, bywhom, status, center, course) VALUES ('$get_eid','$date','$get_timein','$get_timeout','$eid','$get_status','$center','$course')";
                    $insert_into_tea_attendance_q = mysqli_query($conn,$insert_into_tea_attendance);
                    if($insert_into_tea_attendance_q ){
                        echo '<script>alert("Successfully done")</script>';
                        echo '<script>location.href="teachersattendance.php?addattendance=true"</script>';
                    }else{
                        echo '<script>alert("Something went wrong")</script>';
                        echo '<script>location.href="teachersattendance.php"</script>';
                    }
                }
            }

        }
        if(isset($_GET['updateattendance'])){ ?>
            <div align="center">
                <h4>Update Attendance</h4>
                <form method="post">
                EID :<select name="teacher_eid_update">
                    <option value="none">Select EID</option>
                    <?php
                    $sql_get_teacher = "SELECT * FROM teachers WHERE center='$center' AND course='$course' AND NOT position='admin' order by eid";
                    $sql_get_teacher_q = mysqli_query($conn,$sql_get_teacher);
                    while($teachereid = mysqli_fetch_assoc($sql_get_teacher_q)){
                        ?>
                        <option value="<?php echo $teachereid['eid'];?>"><?php echo $teachereid['eid'] ?></option>
                    <?php } ?>
                </select>
                    Date:<input type="date" name="dateofattendance">
                    <br><input type="submit" name="update" value="Update">
                </form>
            </div>
        <?php

        if(isset($_POST['update'])){
            $het_eid = $_POST['teacher_eid_update'];
            $dateofattrndance = $_POST['dateofattendance'];
            $get_teacher_attendance = "SELECT * FROM tea_attendance WHERE eid='$het_eid' AND date='$dateofattrndance' AND center='$center' AND course='$course'";
            $get_teacher_attendance_q = mysqli_query($conn,$get_teacher_attendance);
            $get_teacher_attendance_ch = mysqli_num_rows($get_teacher_attendance_q);
            if($get_teacher_attendance_ch >0){
                $r = mysqli_fetch_assoc($get_teacher_attendance_q);
                ?>
                <div align="center">
<br>
<h4>Update Attendance of <?php echo $het_eid?></h4>
                    <form method="post">
                        <b>EID: </b><input type="text" name="as" value="<?php echo $het_eid?>" disabled>
                        <b>Date: </b><input type="date" name="as" value="<?php echo $dateofattrndance?>" disabled><br>
                        <b>Previous Status :</b><input type="text" value="<?php echo $r['status'] ?>" disabled>&nbsp;
                        <b>Update New Status: </b><select name="update_status">
                            <option>Select new Status</option>
                            <option value="p">Present</option>
                            <option value="a">Absent</option>
                        </select><br>
                        <b>Time To in: </b><input type="text" name="timetoinn" value="<?php echo $r['timetocome']?>">
                        <b>Time To out: </b><input type="text" name="timetogoout" value="<?php echo $r['timetogo']?>">
                        <input type="submit" name="updatedetails" value="Change">
                    </form>
                </div>

            <?php

                if(isset($_POST['updatedetails'])){
                    $get_new_status = $_POST['update_status'];
                    $get_new_in = $_POST['timetoinn'];
                    $get_new_out = $_POST['timetogoout'];
                    $udaye = "UPDATE tea_attendance SET status='$get_new_status',timetocome='$get_new_in',timetogo='$get_new_out' WHERE eid='$$het_eid'AND date ='$dateofattrndance' AND center='$center' AND course='$course'";
                    $dqd = mysqli_query($conn,$udaye);
                    if($dqd){
                        echo '<script>alert("Update Successful")</script>';
                        echo '<script>location.href="teachersattendance.php?updateattendance=true"</script>';
                    }else{
                        echo '<script>alert("update failed")</script>';
                        echo '<script>location.href="teachersattendance.php?updateattendance=true"</script>';
                    }
                }
            }

        }

        } ?>

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