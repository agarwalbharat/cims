<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 7/1/2018
 * Time: 1:58 PM
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
            <title>Mark Marks-Mentor-CIMS</title>
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
            <h3>Mark Students Marks</h3>
        </div>
        <br>
        <br>
        <div align="center">
            <form method="get">
            <?php
            $sql_get_batches = "SELECT DISTINCT batch FROM tea_batche WHERE eid ='$eid' AND subject = '$subject' AND center = '$center' AND course = '$course'";
            $sql_check_batches = mysqli_query($conn,$sql_get_batches);
            $sql_check_result_batch = mysqli_num_rows($sql_check_batches);
            if($sql_check_result_batch>0)
            {
                echo '<b>Batch: </b><select name="batch">
                        <option>Select Batch</option>';
                while ($batches_rows = mysqli_fetch_assoc($sql_check_batches)){ ?>
                    <option value="<?php echo $batches_rows['batch']?>"><?php echo $batches_rows['batch']?></option>
                <?php  }
                echo '</select>';
            }
            $sql_get_batches1 = "SELECT DISTINCT examname FROM exams WHERE center = '$center' AND course = '$course'";
            $sql_check_batches1 = mysqli_query($conn,$sql_get_batches1);
            $sql_check_result_batch1 = mysqli_num_rows($sql_check_batches1);
            if($sql_check_result_batch1>0)
            {
                echo '&nbsp;&nbsp;<b>ExamName: </b><select name="examname">
                        <option>Select Exam</option>';
                while ($batches_rows1 = mysqli_fetch_assoc($sql_check_batches1)){ ?>
                    <option value="<?php echo $batches_rows1['examname']?>"><?php echo $batches_rows1['examname']?></option>
                <?php  }
                echo '</select>';
            }
            $sql_get_batches1 = "SELECT DISTINCT dateofexam FROM exams WHERE center = '$center' AND course = '$course'";
            $sql_check_batches1 = mysqli_query($conn,$sql_get_batches1);
            $sql_check_result_batch1 = mysqli_num_rows($sql_check_batches1);
            if($sql_check_result_batch1>0)
            {
                echo '&nbsp;&nbsp;<b>DateofExam: </b><select name="examdate">
                        <option>Select Exam</option>';
                while ($batches_rows1 = mysqli_fetch_assoc($sql_check_batches1)){ ?>
                    <option value="<?php echo $batches_rows1['dateofexam']?>"><?php echo $batches_rows1['dateofexam']?></option>
                <?php  }
                echo '</select>';
            }
            ?>
                &nbsp;&nbsp;<b>Subject: </b><input type="text" name="subject" value="<?php echo $subject; ?>" disabled>
                &nbsp;&nbsp;<b>Marks: </b><input type="text" name="marks" placeholder="Enter Total Marks" required>
                <input type="submit">
            </form>
        </div>
        <div align="center" style="background-color: lightgray;padding: 10px;border-left: 6px solid red; ">

<?php
    if(isset($_GET['batch']) AND isset($_GET['examname']) AND isset($_GET['examdate']) AND isset($_GET['marks'])){
        $batch_get = mysqli_real_escape_string($conn,$_GET['batch']);
        $examname_get = mysqli_real_escape_string($conn,$_GET['examname']);
        $examdate_get = mysqli_real_escape_string($conn,$_GET['examdate']);
        $subject_get = $subject;
        $marks_get = mysqli_real_escape_string($conn,$_GET['marks']);

        $sql_search_student = "SELECT * FROM students WHERE batch = '$batch_get' AND center = '$center' AND course = '$course'";
        $sql_search_student_check = mysqli_query($conn,$sql_search_student);
        $sql_search_student_check_result = mysqli_num_rows($sql_search_student_check);
        if($sql_search_student_check_result > 0){ ?>
            <h3>Mark Marks-<?php echo $examname_get.' ('.$examdate_get.')'?></h3>
            <p>Batch- <?php echo $batch_get?></p>
            <form method="post">
                <table border="2px" cellpadding="7px">
                    <tr>
                        <th>SID</th>
                        <th>Name</th>
                        <th>Marks Obtain</th>
                    </tr>
                    <?php while ($rows = mysqli_fetch_assoc($sql_search_student_check)){
                        $sid_get = $rows['sid'];
                        $name_get = $rows['fname'].' '.$rows['lname'];
                        ?>
                        <tr>
                            <td><input type="text" name="sid" class="no" value="<?php echo $sid_get;  ?>" disabled></td>
                            <td><input type="text" name="name" class="no" value="<?php echo $name_get; ?>" disabled></td>
                            <td><input type="text" class="no" name="marksobtain[<?php echo $sid_get?>]" placeholder="Obtained Marks"></td>
                        </tr>

                    <?php } ?>
                </table>
                <td><input type="submit" name="insert"></td>
            </form>
        </div>
       <?php }
       if(isset($_POST['insert'])){
           foreach ($_POST['marksobtain'] as $sid_get => $marksobtain_get){
               $eid_insert = $eid;
               $insert_check = "SELECT * FROM marks WHERE sid = '$sid_get' AND eid = '$eid_insert' AND subject = '$subject' AND examname = '$examname_get' AND dateofexam = '$examdate_get'";
               $insert_check_check = mysqli_query($conn,$insert_check);
               $insert_check_check_result = mysqli_num_rows($insert_check_check);
               if($insert_check_check_result > 0){
                   echo '<script>alert("Marks Already Marked");</script>';
               }else {
                   $check_query = "SELECT * FROM exams WHERE examname = '$examname_get' AND batch = '$batch_get' AND center = '$center' AND course = '$course'";
                   $check_query_result = mysqli_query($conn,$check_query);
                   $row = mysqli_fetch_assoc($check_query_result);
                   if($examdate_get == $row['dateofexam']) {
                       $insert_query = "INSERT INTO marks (sid,course,subject,examname,marksobtain,totalmarks,eid,center,batch,dateofexam) VALUES ('$sid_get','$course','$subject','$examname_get','$marksobtain_get','$marks_get','$eid_insert','$center','$batch_get','$examdate_get')";
                       $insert_query_cek = mysqli_query($conn, $insert_query);

                       echo '<script>alert("Marks Marked Successfully");</script>';
                   }else{
                       echo '<script>alert("Wrong Date Choosen");</script>';
                       break;
                   }
               }
           }
       }

    }

?>
        <p align="center">To Change Marks Please Contact branch Admin</p>

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
                width: 10%;
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