<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 7/8/2018
 * Time: 10:28 PM
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
            <title>Teacher-Admin-CIMS</title>
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
            <a href="teachers.php?addteacher=true" class="linking">Add Teacher</a>
            <a href="teachers.php?updateteacher=true" class="linking">Update Teacher</a>
        </div>

        <?php if(isset($_GET['addteacher'])) {
                    $sql = "SELECT eid FROM teachers ORDER BY eid DESC LIMIT 1";
                    $sql_q = mysqli_query($conn, $sql);
                    $ro = mysqli_fetch_assoc($sql_q);
                    $eid_get_from_sql = $ro['eid'];
                    function increment($sid)
                    {
                        return ++$sid[1];
                    }

            $eid_get_from_sql = preg_replace_callback("|(\d+)|", "increment", $eid_get_from_sql);

                    ?>
                    <div align="center">
                        <h3>Add Teacher</h3>
                        <form method="post">
                            <b>EID:</b> <input type="text" name="e  id" value="<?php echo $eid_get_from_sql; ?>" disabled>
                            <b>Fname:</b> <input type="text" name="fname" placeholder="First Name">
                            &nbsp;&nbsp;<b>Lname:</b> <input type="text" name="lname" placeholder="Last Name">
                            <br><b>Email:</b> <input type="email" name="email" placeholder="Email">
                            &nbsp;&nbsp;<b>Mobile:</b> <input type="text" name="mobile" placeholder="Mobile"><br>
                            <b>Address:</b> <input type="text" name="address" placeholder="Address">
                            &nbsp;&nbsp;<b>City:</b> <input type="text" name="city" placeholder="City">
                            <br><b>State:</b> <input type="text" name="state" placeholder="State">
                            &nbsp;&nbsp;<b>Pin code:</b> <input type="text" name="postalcode" placeholder="Pin Code"><br>
                            <b>Date Of joining:</b> <input type="date" name="dateofjoining">
                            <b>Position: </b><select name="position">
                                <option value="none">Select Position</option>
                                <option value="mentor">Mentor</option>
                                <option value="hod">HOD</option>
                                <option value="teacher">Teacher</option>
                            </select>
                            <hr width="80%">
                            <br><b>Salary:</b> <input type="text" name="salary" placeholder="Salary Per Month">
                            &nbsp;&nbsp;<b>Subject:</b> <input type="text" name="subject"
                                                                   placeholder="Subject">
                            <br><b>Course:</b> <input type="text" name="course" value="<?php echo ucfirst($course); ?>"
                                                      disabled>
                            &nbsp;&nbsp;<b>Center:</b> <input type="text" name="center" value="<?php echo ucfirst($center); ?>"
                                                              disabled>
                            <br><b>Experience:</b> <input type="text" name="experience" placeholder="Experience">
                            <br><b>Highest Qualification:</b> <input type="text" name="highestqualification" placeholder="Highest Qualification">
                            &nbsp;&nbsp;<b>Highest Qualification Marks:</b> <input type="text" name="highestqualificationmarks" placeholder="Highest Qualification Marks">
                            <br><br><input type="submit" name="add">
                        </form>
                    </div>
                    <?php
                    if (isset($_POST['add'])) {
                        $te_fname = $_POST['fname'];
                        $te_lname = $_POST['lname'];
                        $te_email = $_POST['email'];
                        $te_mobile = $_POST['mobile'];
                        $te_address = $_POST['address'];
                        $te_city = $_POST['city'];
                        $te_state = $_POST['state'];
                        $te_pin = $_POST['postalcode'];
                        $te_salary = $_POST['salary'];
                        $te_dateofjoining = $_POST['dateofjoining'];
                        $te_position = $_POST['position'];
                        $te_subject = $_POST['subject'];
                        $te_experience = $_POST['experience'];
                        $te_highestqualification = $_POST['highestqualification'];
                        $te_highestqualificationmarks = $_POST['highestqualificationmarks'];

                        $sql_get_insert = "INSERT INTO teachers (eid, fname, lname, email, mobile, address, city, state, postalcode, salary, position, subject, course, center, dateofjoining, experience, highestqualification, highestqualificationmarks, status) VALUES ('$eid_get_from_sql','$te_fname','$te_lname','$te_email','$te_mobile','$te_address','$te_city','$te_state','$te_pin','$te_salary','$te_position','$te_subject','$course','$center','$te_dateofjoining','$te_experience','$te_highestqualification','$te_highestqualificationmarks','yes')";
                        $sql_get_insert_quary = mysqli_query($conn, $sql_get_insert);
                        $insert_into_users = "INSERT INTO users (username, password, type) VALUES ('$eid_get_from_sql','$eid_get_from_sql','teacher')";
                        $insert_into_users_check = mysqli_query($conn,$insert_into_users);
                        if ($sql_get_insert_quary AND $insert_into_users_check) {
                            echo '<script>location.href="teachers.php?res=success"</script>';
                        } else {
                            echo '<script>location.href="teachers.php?res=fail"</script>';
                        }

                    }
        }
        if(isset($_GET['updateteacher']) OR isset($_GET['teacherid'])) {
            ?>
            <div align="center">
                <form method="get">
                    Teacher Id (EID): <input type="text" name="teacherid" placeholder="Enter Teacher Id">
                    <input type="submit" name="update">
                </form>
            </div>

            <?php
            if (isset($_GET['teacherid'])) {
                $get_teacherid = mysqli_real_escape_string($conn, $_GET['teacherid']);
                if ($eid != $get_teacherid) {
                    $sql_query_search = "SELECT * FROM teachers WHERE eid='$get_teacherid' AND center='$center' AND course='$course'";
                    $sql_query_search_result = mysqli_query($conn, $sql_query_search);
                    $sql_query_search_result_check = mysqli_num_rows($sql_query_search_result);
                    if ($sql_query_search_result_check > 0) {
                        $rowss = mysqli_fetch_assoc($sql_query_search_result);
                        if ($rowss['position'] != 'sadmin') {
                            ?>
                            <div align="center">
                                <h3>Update Details - <span style="color: blue"><?php echo $get_teacherid ?></span></h3>
                                <form method="post">
                                    <b>EID:</b> <input type="text" name="eid" value="<?php echo $rowss['eid']; ?>" disabled><br>
                                    <b>Fname:</b> <input type="text" name="fname"
                                                         value="<?php echo $rowss['fname']; ?>">
                                    &nbsp;&nbsp;<b>Lname:</b> <input type="text" name="lname"
                                                                     value="<?php echo $rowss['lname']; ?>">
                                    <br><b>Email:</b> <input type="email" name="email"
                                                             value="<?php echo $rowss['email']; ?>">
                                    &nbsp;&nbsp;<b>Mobile:</b> <input type="text" name="mobile"
                                                                      value="<?php echo $rowss['mobile']; ?>"><br>
                                    <b>Address:</b> <input type="text" name="address"
                                                           value="<?php echo $rowss['address']; ?>">
                                    &nbsp;&nbsp;<b>City:</b> <input type="text" name="city"
                                                                    value="<?php echo $rowss['city']; ?>">
                                    <br><b>State:</b> <input type="text" name="state"
                                                             value="<?php echo $rowss['state'] ?>">
                                    &nbsp;&nbsp;<b>Pin code:</b> <input type="text" name="postalcode"
                                                                        value="<?php echo $rowss['postalcode']; ?>">
                                    <br><b>Salary:</b> <input type="text" name="salary" value="<?php echo $rowss['salary']; ?>"
                                                            disabled>
                                    &nbsp;&nbsp;<b>Position:</b> <input type="text" name="position"
                                                                           value="<?php echo $rowss['position']; ?>"
                                                                           disabled>
                                    &nbsp;&nbsp;<b>Subject:</b> <input type="text" name="subject"
                                                                         value="<?php echo $rowss['subject'] ?>"
                                                                         disabled>
                                    <br><b>Course:</b> <input type="text" name="course"
                                                              value="<?php echo $rowss['course'] ?>" disabled>
                                    &nbsp;&nbsp;<b>Date Of Joining:</b> <input type="date" name="dateofjoining"
                                                                     value="<?php echo $rowss['dateofjoining'] ?>" disabled>
                                    &nbsp;&nbsp;<b>Center:</b> <input type="text" name="center"
                                                                      value="<?php echo $rowss['center']; ?>" disabled>
                                    <br><b>Experience:</b> <input type="text" name="experience"
                                                             value="<?php echo $rowss['experience'] ?>">
                                    &nbsp;&nbsp;<b>Highest Qualification:</b> <input type="text" name="highestqualification"
                                                                        value="<?php echo $rowss['highestqualification']; ?>">
                                    &nbsp;&nbsp;<b>Highest Qualification Marks:</b> <input type="text" name="highestqualificationmarks"
                                                                        value="<?php echo $rowss['highestqualificationmarks']; ?>">
                                    <br><br><input type="submit" name="update">
                                </form>
                                <br><a href="teachers.php?res=fail">
                                    <input type="submit" name="Cancel" value="Cancel">
                                </a>

                            </div>

                            <?php
                            if (isset($_POST['update'])) {
                                $te_fname = $_POST['fname'];
                                $te_lname = $_POST['lname'];
                                $te_email = $_POST['email'];
                                $te_mobile = $_POST['mobile'];
                                $te_address = $_POST['address'];
                                $te_city = $_POST['city'];
                                $te_state = $_POST['state'];
                                $te_pin = $_POST['postalcode'];
                                $te_experience = $_POST['experience'];
                                $te_highestqualification = $_POST['highestqualification'];
                                $te_highestqualificationmarks = $_POST['highestqualificationmarks'];

                                $sql_q_update = "UPDATE teachers SET fname='$te_fname',lname='$te_lname',email='$te_email',mobile='$te_mobile',address='$te_address',city='$te_city',state='$te_state',postalcode='$te_pin',experience='$te_experience',highestqualification='$te_highestqualification',highestqualificationmarks='$te_highestqualificationmarks' WHERE eid='$get_teacherid' AND center='$center' AND course='$course'";
                                $sql_q_update_query = mysqli_query($conn, $sql_q_update);
                                if ($sql_q_update_query) {
                                    echo '<script>location.href="teachers.php?res=success"</script>';
                                } else {
                                    echo '<script>location.href="teachers.php?res=fail"</script>';
                                }
                            }

                        } else{
                            echo '<script>alert("You Can not Update Super Admin\'s Details Enter Another")</script>';
                        }
                    }else {
                        echo '<script>alert("Wrong EID")</script>';
                    }
                }else{
                    echo '<script>alert("You Can not Update Your Details Enter Another")</script>';
                }
            }
        }?>

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