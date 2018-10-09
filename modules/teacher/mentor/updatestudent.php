<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 7/2/2018
 * Time: 2:01 PM
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
    $batchmentor = $row['batchmentor'];
}
if($status == 'yes' || $status == 'Yes') {
    if (isset($_GET['studentid'])) {
        $get_studentid = mysqli_real_escape_string($conn, $_GET['studentid']);
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Mentor-CIMS</title>
            <link rel="stylesheet" type="text/css" href="css/style.css">
            <style>
                input,button{
                    padding: 5px;
                    border: 2px solid blue;
                    border-radius: 10px;
                }
                input[type=submit],button{
                    width: 200px;
                }
                input:hover{
                    background-color: lightblue;
                }
                button:hover{
                    background-color: green;
                }

            </style>
        </head>
        <body>
        <h2 align="center" style="color: blue"><?php echo ucfirst($center) . ' (' . strtoupper($course) . ')' ?></h2>
        <div class="header">

            <a href="batchmentorstudents.php?ret=cancel&studentid=<?php echo $get_studentid?>"> <span style="font-size:30px;cursor:pointer" class="logo">Go Back </span></a>

            <div class="header-right">
                <a href="profile.php">
                    <?php echo $fname . " " . $lname . " (" . strtoupper($eid) . ")" ?></a>
            </div>
        </div>
        <?php
            $sql_query_search = "SELECT * FROM students WHERE sid='$get_studentid' AND mentor='$eid' AND center='$center' AND batch = '$batchmentor'";
            $sql_query_search_result = mysqli_query($conn,$sql_query_search);
            $sql_query_search_result_check = mysqli_num_rows($sql_query_search_result);
            if($sql_query_search_result_check>0)
            {
                $rowss = mysqli_fetch_assoc($sql_query_search_result);

        ?>
        <div align="center">
<h3>Update Details - <span style="color: blue"><?php echo $get_studentid?></span></h3>
<form method="post">
            <b>SID:</b> <input type="text" name="sid" value="<?php echo $rowss['sid'];?>" disabled>
    &nbsp;&nbsp;<b>PID:</b> <input type="text" name="pid" value="<?php echo $rowss['pid']; ?>" disabled><br>
             <b>Fname:</b> <input type="text" name="fname" value="<?php echo $rowss['fname'];?>">
              &nbsp;&nbsp;<b>Lname:</b> <input type="text" name="lname" value="<?php echo $rowss['lname']; ?>">
              <br><b>Email:</b> <input type="email" name="email" value="<?php echo $rowss['email']; ?>">
               &nbsp;&nbsp;<b>Mobile:</b> <input type="text" name="mobile" value="<?php echo $rowss['phone']; ?>"><br>
               <b>Address:</b> <input type="text" name="address" value="<?php echo $rowss['address']; ?>">
                &nbsp;&nbsp;<b>City:</b> <input type="text" name="city" value="<?php echo $rowss['district']; ?>">
                <br><b>State:</b> <input type="text" name="state" value="<?php echo $rowss['state']?>">
                 &nbsp;&nbsp;<b>Pin code:</b> <input type="text" name="postalcode" value="<?php echo $rowss['postalcode']; ?>">
                <br><b>Fees:</b> <input type="text" name="fees" value="<?php echo $rowss['fee']; ?>" disabled>
    &nbsp;&nbsp;<b>Scholarship:</b> <input type="text" name="scholarship" value="<?php echo $rowss['scholarship'];?>" disabled>
    &nbsp;&nbsp;<b>Paid Fees:</b> <input type="text" name="paidfee" value="<?php echo $rowss['paidfee']?>" disabled>
    <br><b>Course:</b> <input type="text" name="course" value="<?php echo $rowss['course']?>" disabled>
    &nbsp;&nbsp;<b>Batch:</b> <input type="text" name="batch" value="<?php echo $rowss['batch']?>" disabled>
    &nbsp;&nbsp;<b>Center:</b> <input type="text" name="center" value="<?php echo $rowss['center'];?>" disabled>
    <br><b>Class:</b> <input type="text" name="class" value="<?php echo $rowss['class']?>">
    &nbsp;&nbsp;<b>10 Marks:</b> <input type="text" name="marks10" value="<?php echo $rowss['10mark'];?>">
    &nbsp;&nbsp;<b>12 Marks:</b> <input type="text" name="marks12" value="<?php echo $rowss['12mark']; ?>">
    <br><b>Pre Exam:</b> <input type="text" name="preexam" value="<?php echo $rowss['preexam']; ?>">
    &nbsp;&nbsp;<b>Pre Exam Year:</b> <input type="text" name="preexamyear" value="<?php echo $rowss['preexamyear']; ?>">
    &nbsp;&nbsp;<b>Pre Exam Marks:</b> <input type="text" name="preexammarks" value="<?php echo $rowss['preexammarks'];?>">
    <br><b>Father's Name:</b> <input type="text" name="fathername" value="<?php echo $rowss['fathername']?>">
    &nbsp;&nbsp;<b>Occupation: </b><input type="text" name="fatheroccu" value="<?php echo $rowss['fatheroccu']?>">
    &nbsp;&nbsp;<b>Mobile Number:</b> <input type="text" name="fathermob" value="<?php echo $rowss['fathermob']?>">
    <br><b>Mother's Name:</b> <input type="text" name="mothername" value="<?php echo $rowss['mothername']?>">
    &nbsp;&nbsp;<b>Occupation: </b><input type="text" name="motheroccu" value="<?php echo $rowss['motheroccu']?>">
    &nbsp;&nbsp;<b>Mobile Number:</b> <input type="text" name="mothermob" value="<?php echo $rowss['mothermob']?>">
<br><br><input type="submit" name="update">
</form>
<br><a href="batchmentorstudents.php?ret=cancel&studentid=<?php echo $get_studentid; ?>"><button>Cancel</button></a>

        </div>

                <?php
                if(isset($_POST['update'])){
                    $st_fname = $_POST['fname'];
                    $st_lname = $_POST['lname'];
                    $st_email = $_POST['email'];
                    $st_mobile = $_POST['mobile'];
                    $st_address = $_POST['address'];
                    $st_city = $_POST['city'];
                    $st_state = $_POST['state'];
                    $st_pin = $_POST['postalcode'];
                    $st_class = $_POST['class'];
                    $st_10mark = $_POST['marks10'];
                    $st_12mark = $_POST['marks12'];
                    $st_preexam = $_POST['preexam'];
                    $st_preexamyear = $_POST['preexamyear'];
                    $st_preexammarks = $_POST['preexammarks'];
                    $st_fathername = $_POST['fathername'];
                    $st_fatheroccu = $_POST['fatheroccu'];
                    $st_fathermob = $_POST['fathermob'];
                    $st_mothername = $_POST['mothername'];
                    $st_motheroccu = $_POST['motheroccu'];
                    $st_mothermob = $_POST['mothermob'];

                    $sql_q_update = "UPDATE students SET fname='$st_fname',lname='$st_lname',email='$st_email',phone='$st_mobile',address='$st_address',district='$st_city',state='$st_state',postalcode='$st_pin',class='$st_class',`10mark`='$st_10mark',`12mark`='$st_12mark',preexam='$st_preexam',preexamyear='$st_preexamyear',preexammarks='$st_preexammarks',fathername='$st_fathername',fathermob='$st_fathermob',fatheroccu='$st_fatheroccu',mothername='$st_mothername',mothermob='$st_mothermob',motheroccu='$st_motheroccu' WHERE sid='$get_studentid' AND center='$center' AND batch='$batchmentor' AND mentor='$eid'";
                    $sql_q_update_query = mysqli_query($conn, $sql_q_update);
                    if($sql_q_update_query){
                        header("Location: batchmentorstudents.php?ret=success&studentid=$get_studentid");
                    }else{
                        echo '<script>alert("Error updating record: ' . mysqli_error($conn).')</script>';
                    }

                }

                 } ?>
        </body>
        </html>
        <?php
    }else{
        echo '<h1 align="center">Do Not Try To make us Fool</h1><a href="batchmentorstudents.php">Go Back</a> ';
    }
}else {
    ?>
    <h1>Your account is deactivated by admin due to some reasons. kindly contact Admin for further.</h1>
    <?php
}
}else{
    header("Location: ../../index.php");
}

?>