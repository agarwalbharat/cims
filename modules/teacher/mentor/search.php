<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 6/29/2018
 * Time: 12:26 AM
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
            <title>Search-Mentor-CIMS</title>
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

        <div align="center">
            <form method="get">

                <input type="text" name="search" placeholder="Enter Student id (SID)" required>
                <input type="submit">

            </form>
        </div>

        <?php
                    if(isset($_GET['search'])){
                        $searchid = mysqli_real_escape_string($conn,$_GET['search']);

                        $sql_search = "SELECT * FROM students WHERE sid = '$searchid' AND center = '$center'";
                        $sql_search_result = mysqli_query($conn,$sql_search);
                        $sql_search_result_check = mysqli_num_rows($sql_search_result);
                        if($rowss = mysqli_fetch_assoc($sql_search_result)){
                            $fname_student = $rowss['fname'];
                            $lname_student = $rowss['lname'];
                            $email_student = $rowss['email'];
                            $phone_student = $rowss['phone'];
                            $course_student = $rowss['course'];
                            $batch_student = $rowss['batch'];
                            $mentor_student = $rowss['mentor'];
                        }else{
                            $fname_student = "not found";
                            $lname_student = "not found";
                            $email_student = "not found";
                            $phone_student = "not found";
                            $course_student = "not found";
                            $batch_student = "not found";
                            $mentor_student = "not Found";
                        }
                    }

                    ?>
                    <?php if(isset($searchid)){ ?>
                        <div align="center">
                            <table>
                                <tr>
                                    <th>Sid:</th>
                                    <td><?php echo $searchid; ?></td>
                                </tr>
                                <tr>
                    <th>Name:</th>
                    <td><?php echo $fname_student.' '.$lname_student; ?></td>
                </tr>
                <tr>
                    <th>Batch(Course):</th>
                    <td><?php echo $batch_student.' ('.$course_student.')'; ?></td>
                </tr><tr>
                    <th>Mentor:</th>
                    <td><?php echo $mentor_student; ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><?php echo $email_student; ?></td>
                </tr>
                <tr>
                    <th>Phone:</th>
                    <td><?php echo $phone_student; ?></td>
                </tr>
                <tr>
                    <td><?php echo '<a href="search.php?search='.$searchid.'&type=attendance">Student Attendance</a>'; ?> </td>
                    <td><?php echo '<a href="search.php?search='.$searchid.'&type=marks">Student Marks</a>'; ?></td>
                </tr>
            </table>


        </div>
            <?php
                if(isset($_GET['type'])){
                    if($_GET['type']=='attendance'){
                        $sid = mysqli_real_escape_string($conn,$_GET['search']);
                        $sqli = "SELECT count(status) as total FROM attendance WHERE sid = '$sid' AND eid = '$eid' AND subject = '$subject'";
                        $resulti = mysqli_query($conn, $sqli);
                        $result = mysqli_fetch_array($resulti);
                        $total_classes = $result['total'];
                        $sqli = "SELECT count(status) as total FROM attendance WHERE sid = '$sid' AND eid = '$eid' AND subject = '$subject' AND (status='p' OR status='P')";
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
                            $sid = mysqli_real_escape_string($conn,$_GET['search']);
                            $sqli = "SELECT * FROM attendance WHERE sid = '$sid' AND eid = '$eid' AND subject = '$subject'";
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
                            $sid = mysqli_real_escape_string($conn,$_GET['search']);
                                $sqli = "SELECT * FROM marks WHERE sid = '$sid' AND eid = '$eid' AND subject = '$subject'";
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
            input[type=text]{
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