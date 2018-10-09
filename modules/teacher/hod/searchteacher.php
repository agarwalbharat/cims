<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 7/4/2018
 * Time: 4:55 AM
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
    }
    if($status == 'yes' || $status == 'Yes') {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Search-HOD's-CIMS</title>
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
            <a href="search.php">Search teacher Information</a>
            <a href="searchteacher.php">Search Teacher Information</a>
            <a href="markattendance.php">Mark Attendance</a>
            <a href="markmarks.php">Mark Marks</a>
            <a href="timetable.php">TimeTable</a>
            <a href="complaint.php">Complaint</a>
            <a href="update_password.php">Update Password</a>
            <a href="../../../logout.php">Logout</a>
        </div>
        <?php if(!isset($_GET['teacherid'])){ ?>
        <div align="center">
            <h4>Teachers of <span style="color: blue; "><?php echo ucfirst($subject); ?></span></h4>
            <table border="2px" align="center" cellpadding="10px">
            <tr>
                <th>EID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Position</th>
                <th>Date Of joining</th>
                <th>Full Information</th>
            </tr>
                <?php
                $sql_get_teachers = "SELECT * FROM teachers WHERE subject='$subject' AND center='$center' AND course='$course' AND NOT eid='$eid'";
                $sql_get_teachers_check = mysqli_query($conn,$sql_get_teachers);
                $sql_get_teachers_check_result = mysqli_num_rows($sql_get_teachers_check);
                if($sql_get_teachers_check_result>0)
                {
                    while ($rows = mysqli_fetch_assoc($sql_get_teachers_check))
                    {

                        $id_teacher = $rows['eid'];
                ?>
                        <tr>
                            <td><?php echo $rows['eid']?></td>
                            <td><?php echo $rows['fname'].' '.$rows['lname']?></td>
                            <td><?php echo $rows['email']?></td>
                            <td><?php echo $rows['mobile']?></td>
                            <td><?php echo ucfirst($rows['position'])?></td>
                            <td><?php echo $rows['dateofjoining']?></td>
                            <td><a href="searchteacher.php?teacherid=<?php echo $id_teacher?>">Click To See</a> </td>
                        </tr>

                        <?php
                    }
                }
                ?>
        </div>
<?php }else{
            $id_get = mysqli_real_escape_string($conn,$_GET['teacherid']);
            $sql_get_complete_information = "SELECT * FROM teachers WHERE eid='$id_get' AND center='$center' AND course='$course'";
            $sql_get_complete_information_check = mysqli_query($conn,$sql_get_complete_information);
            $sql_get_complete_information_check_result = mysqli_num_rows($sql_get_complete_information_check);
            if($sql_get_complete_information_check_result>0){
                $get =  mysqli_fetch_assoc($sql_get_complete_information_check);?>
                <div align="center">
                    <h4>Information of <span style="color: blue"><?php echo $id_get?></span></h4>
                    <table align="center">
                        <tr align="center">
                            <td colspan="2"><b>EID:</b><?php echo $get['eid']; ?></td>
                        </tr>
                        <tr align="center">
                            <td colspan="2"><b>Name:</b><?php echo $get['fname'].' '.$get['lname']; ?></td>
                        </tr>
                        <tr align="center">
                            <td><b>Position:</b><?php echo $get['position'];?></td>
                            <td><b>Mentor Batch:</b><?php $get['batchmentor'] ?></td>
                        </tr>
                        <tr align="center">
                            <td><b>Email:</b><?php echo $get['email']?></td>
                            <td><b>Mobile:</b><?php echo $get['mobile']?></td>
                        </tr>
                        <tr align="center">
                            <td colspan="2"><b>Address:</b><?php echo $get['address'].', '.$get['city'].', '.$get['state'].', '.$get['postalcode'];?></td>
                        </tr>
                        <tr align="center">
                            <td colspan="2"><b>Salary:</b><?php echo 'Rs'.$get['salary']?></td>
                        </tr>
                        <tr align="center">
                            <td><b>Date Of Joining:</b><?php echo $get['dateofjoining']?></td>
                            <td><b>Experience:</b><?php echo $get['experience']?></td>
                        </tr>
                        <tr align="center">
                            <td><b>Qualification:</b><?php echo $get['highestqualification'];?>
                            <td><b>Marks:</b><?php echo $get['highestqualificationmarks']?></td>
                        </tr>
                        <tr align="center">
                            <td colspan="2"><a href="searchteacher.php">Go Back</a> </td>
                        </tr>
                    </table>
                </div>
            <?php }
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
    header("Location: ../../../index.php");
}

?>