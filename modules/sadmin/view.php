<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 2018-07-18
 * Time: 3:59 PM
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
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SAdmin-CIMS</title>
    <link rel="stylesheet" type="text/css" href="../admin/css/style.css">
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
        th,td{
            width: 200px;
        }

        hr{
            width: 60%;
        }



    </style>
</head>
<body>
<h2 align="center" style="color: blue"><?php echo "Super Admin (Main Center)" ?></h2>
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
    <a href="add.php">Add//Update</a>
    <a href="view.php">View Details</a>
    <a href="incomingcomplaint.php">Incoming Complaint</a>
    <a href="update_password.php">Update Password</a>
    <a href="../../logout.php">Logout</a>
</div>

<div align="center" style="background-color: aquamarine;padding: 10px">
    <a href="view.php?students=true" class="linking">View Student</a>
    <a href="view.php?teachers=true" class="linking">View Teachers</a>
    <a href="view.php?batch=true" class="linking">View Batch</a>
</div>
<?php
    if(isset($_GET['students'])){ ?>
        <div align="center">
            <h4>Student Details</h4>
            <form method="post">
                <b>Student ID (SID): </b><input type="text" name="studentid" placeholder="Enter Student ID (SID)" required><br>
                <input type="submit" name="search_student" value="Submit">
            </form>
            <hr>
        </div>
    <?php
        if(isset($_POST['search_student'])){
            $sql_get_id = mysqli_real_escape_string($conn,$_POST['studentid']);
            $search_student = "SELECT * FROM students WHERE sid='$sql_get_id'";
            $search_student_q = mysqli_query($conn,$search_student);
            $search_student_q_ch = mysqli_num_rows($search_student_q);
            if ($search_student_q_ch <= 0){
                echo '<h3 align="center" style="color: red">Wrong SID</h3>';
            }else{
                $as = mysqli_fetch_assoc($search_student_q);
                ?>
                <div align="center">
                    <p><b>SID: </b><?php echo $as['sid']; ?></p>
                    <p><b>Name: </b><?php echo $as['fname'].' '.$as['lname']; ?></p>
                    <p><b>Email: </b><?php echo $as['email']; ?> &nbsp;&nbsp; <b>Mobile: </b><?php echo $as['phone']; ?> </p>
                    <p><b>Address: </b><?php echo $as['address'].', '.$as['district'],', '.$as['state'],', '.$as['postalcode']; ?></p>
                    <p><b>Total Fees: </b><?php echo $as['fee'] ;?> &nbsp;&nbsp; <b>Scholarship: </b><?php echo $as['scholarship'].'%';?></p>
                    <p><b>Center: </b><?php echo ucfirst($as['center']); ?> &nbsp;&nbsp; <b>Course: </b><?php echo $as['course'];?> </p>
                    <p><b>Mentor EID: </b><?php echo $as['mentor'];?> &nbsp;&nbsp; <b>PID: </b><?php echo $as['pid'] ;?></p>
                    <p><b>Timmings: </b><?php echo $as['timing'] ;?></p>
                    <p><b>Date Of Reg.: </b><?php echo $as['dateofreg'] ;?></p>
                </div>
            <?php }
        }
    }
    if(isset($_GET['teachers'])){
        ?>
        <div align="center">
            <h4>Teachers Details</h4>
            <form method="post">
                <b>Teachers ID (EID): </b><input type="text" name="teacherid" placeholder="Enter Teachers ID (EID)" required><br>
                <input type="submit" name="search_teachers" value="Submit">
            </form>
            <hr>
        </div>
        <?php
             if(isset($_POST['search_teachers'])){
                $get_id = mysqli_real_escape_string($conn,$_POST['teacherid']);
                $get_teachers = "SELECT * FROM teachers WHERE eid='$get_id'";
                 $get_teachers_q = mysqli_query($conn,$get_teachers);
                 $get_teachers_q_check = mysqli_num_rows($get_teachers_q);
                 if($get_teachers_q_check <= 0){
                     echo '<h3 align="center" style="color:red">Wrong EID</h3>';
                 }else{
                     $as = mysqli_fetch_assoc($get_teachers_q);
                     ?>
                     <div align="center">
                         <p><b>EID: </b><?php echo $as['eid']; ?></p>
                         <p><b>Name: </b><?php echo $as['fname'].' '.$as['lname']; ?></p>
                         <p><b>Email: </b><?php echo $as['email']; ?> &nbsp;&nbsp; <b>Mobile: </b><?php echo $as['mobile']; ?> </p>
                         <p><b>Address: </b><?php echo $as['address'].', '.$as['city'],', '.$as['state'],', '.$as['postalcode']; ?></p>
                         <p><b>Salary: </b><?php echo $as['salary'] ;?> &nbsp;&nbsp; <b>Position : </b><?php echo $as['position'];?></p>
                         <p><b>Center: </b><?php echo ucfirst($as['center']); ?> &nbsp;&nbsp; <b>Course: </b><?php echo $as['course'];?> </p>
                         <p><b>Subject : </b><?php echo $as['subject'];?> &nbsp;&nbsp; <b>Experience: </b><?php echo $as['experience'] ;?></p>
                         <p><b>Date Of joining.: </b><?php echo $as['dateofjoining'] ;?></p>
                     </div>
                     <?php
                 }
             }
    }
    if(isset($_GET['batch'])){ ?>
        <div align="center">
            <h3>Batch Details</h3>
            <form method="post">
                <b>Center ID: </b><input type="text" name="centerid" placeholder="Enter Center ID"><br>
                <input type="submit" name="search_batch" value="Search">
            </form>
        </div>
    <?php
        if(isset($_POST['search_batch'])){
            $get_center = mysqli_real_escape_string($conn,$_POST['centerid']);
            $get_batch = "SELECT * FROM batches WHERE center='$get_center' ORDER BY batch";
            $get_batch_q = mysqli_query($conn,$get_batch);
            $get_batch_q_check = mysqli_num_rows($get_batch_q);
            if($get_batch_q_check <=0){
                echo '<h3 align="center" style="color:red">Wrong Center ID</h3>';
            }else{ ?>
                <br>
                <div align="center">
                <table border="2">
                    <tr>
                        <th>Batch</th>
                        <th>Mentor</th>
                        <th>Timings</th>
                        <th>Course</th>
                    </tr>
                    <?php while ($num = mysqli_fetch_assoc($get_batch_q)){ ?>
                        <tr align="center"><td><?php echo $num['batch']; ?></td>
                        <td><?php echo $num['mentor']; ?></td>
                       <td><?php echo $num['timings']; ?></td>
                        <td><?php echo $num['course']; ?></td></tr>
                    <?php } ?>
                </table>
                </div>
            <?php }
        }
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
}else{
    ?>
    <h1>Your account is deactivated by admin due to some reasons. kindly contact Admin for further.</h1>
    <?php
}
}else{
    header("Location: ../../index.php");
}

?>