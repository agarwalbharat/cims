<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 6/7/2018
 * Time: 10:34 PM
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
    <div align="center">
        <table>
            <form method="post" action="marks.php">
                <tr> <td><h4>Enter Exam Name</h4></td>
                    <?php
                        $sql_search = "SELECT * FROM exams WHERE batch = '$batch' AND center='$center' AND course = '$course'";
                        $sql_search_check = mysqli_query($conn,$sql_search);
                        $sql_search_check_res = mysqli_num_rows($sql_search_check);
                        if($sql_search_check_res>0){?>
                    <td><select name="examname">
                            <option>Select Exam</option>
                            <?php while($rei = mysqli_fetch_assoc($sql_search_check)){
                    ?>
                       <option value="<?php echo $rei['examname']?>"><?php echo $rei['examname']?></option>
                        <?php } ?>
                    <!--<td><input type="text" name="examname" placeholder="Exam Name"/></td> -->
                    </select></td>
                    <?php } ?>
                    <td>&nbsp;<h2>Or</h2>&nbsp;</td>
                    <td><h4>Enter Exam Date</h4></td>
                    <td><input type="date" name="date" /></td><br>
                </tr>
                <tr>
                    <td colspan="5" align="center" rowspan="5" style="width: 500px;"><input type="submit" name="submit" value="submit" /></td>
                </tr>
            </form>
        </table>
    </div>
    <div style="padding-left:20px; float: left;border-left: 6px solid red;background-color: lightgrey;width: 100%">
        <h1 align="center">Marks - <span style="color: blue"><?php echo $fname.' '.$lname; ?></span></h1>


        <?php
            if(isset($_POST['submit'])){
                if(isset($_POST['date'])){
                    $date = $_POST['date'];
                 }
                 if(isset($_POST['examname'])){
                    $examname = $_POST['examname'];
                 }
            }
        ?>
        <table border="2" align="center" cellpadding="5px">
            <h4 align="center">Showing Result For
                <?php
                    if(isset($_POST['examname']) OR isset($_POST['date'])) {
                        if (isset($_POST['examname'])) {
                            echo  $_POST['examname'].' Exam ';
                        }
                        if (isset($_POST['date'])) {
                            echo $_POST['date'];
                        }
                    }else{
                        echo 'All Exam and Dates';
                    }
                ?>

            </h4>
            <tr>
                <th>S.NO.</th>
                <th>Subject</th>
                <th>Exam</th>
                <th>Date OF Exam</th>
                <th>Marks Obtain</th>
                <th>Total Marks</th>
                <th>Precentage</th>
                <th>Teacher</th>
                <th>Teacher ID (EID)</th>
            </tr>
            <?php
            if(isset($_POST['examname']) OR isset($_POST['date'])) {
                $sqli = "SELECT * FROM marks WHERE sid = '$sid' AND course = '$course' AND center = '$center' AND batch = '$batch' AND (examname = '$examname' OR dateofexam = '$date')";
            }else{
                  $sqli = "SELECT * FROM marks WHERE sid = '$sid' AND course = '$course' AND center = '$center' AND batch = '$batch'";
            }
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
                $sql_teacher = "SELECT * FROM teachers WHERE eid = '$eid'";
                $sql_result = mysqli_query($conn, $sql_teacher);
                $sql_result_teacher = mysqli_num_rows($sql_result);
                while ($rowsn = mysqli_fetch_assoc($sql_result)) {
                    $teacherfname = $rowsn['fname'];
                    $teacherlname = $rowsn['lname'];

                }

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
        input[type=date],select{
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

    </style>

    </body>
    </html>
    <?php
}else{
    header("Location: ../../index.php");
}
?>
