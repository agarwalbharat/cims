<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 7/3/2018
 * Time: 7:54 PM
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
    $today_date = date("Y-m-d");
if (isset($_GET['complaintid'])) {
$get_complaintid = (int)$_GET['complaintid'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo 'Complaint Id-'.$get_complaintid; ?>-reply-CIMS</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        .new{
            color: blue;
            font-size: 20px;
        }
        textarea {
            width: 50%;
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
            width: 50%;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }
        hr{
            width: 50%;
        }

    </style>
</head>
<body>
<h2 align="center" style="color: blue"><?php echo ucfirst($center) . ' (' . strtoupper($course) . ')' ?></h2>
<div class="header">

    <a href="incomingcomplaint.php?ret=cancel"><span style="font-size:30px;cursor:pointer" class="logo">Go Back </span></a>

    <div class="header-right">
        <a href="profile.php">
            <?php echo $fname . " " . $lname . " (" . strtoupper($eid) . ")" ?></a>
    </div>
</div>


<div align="center">
<h4>Reply To Complaint Id <?php echo $get_complaintid; ?></h4><hr>
    <form method="post">
    <?php
    $sql = "SELECT * FROM complaint WHERE id='$get_complaintid' AND course='$course' AND eid='$eid' AND center='$center'";
    $sql_query = mysqli_query($conn,$sql);
    $sql_query_check = mysqli_num_rows($sql_query);
    if($sql_query_check>0)
    {
        $roee = mysqli_fetch_assoc($sql_query); ?>
    <h4><b class="new">Username:</b> <?php echo $roee['username']; ?></h4>
        <h4><b class="new">Batch:</b> <?php echo $roee['batch']; ?></h4>
        <h4><b class="new">Subject:</b> <?php echo ucfirst($roee['subject']); ?></h4>
        <h4><b class="new">Complaint:</b> <br><?php echo ucfirst($roee['complaint']); ?></h4>
        <h4><b class="new">Date Of Complaint:</b><?php echo $roee['dateofcomp']; ?></h4><hr>
        <h4><b class="new">Date of reply:</b><?php echo $today_date; ?> </h4>
        <h4><b class="new">Reply:</b></h4>
        <?php
        $sql_check = "SELECT * From complaint WHERE id='$get_complaintid' AND center='$center' AND course='$course'";
        $sql_check_reslut = mysqli_query($conn, $sql_check);
        $sql_check_reslut_check = mysqli_num_rows($sql_check_reslut);
        $nw = mysqli_fetch_assoc($sql_check_reslut);
        if ($nw['replyed']=='1') {
        echo ucfirst($nw['reply'])."<hr>";
         }else{?>
            <textarea name="complaintreply" placeholder="Write something.." style="height:200px" required></textarea>
            <input type="submit" value="submit" name="submit"><br><br>
            <a href="incomingcomplaint.php?ret=cancel">Cancel</a>
            <hr>
        <?php }?>


    <?php }else{
        echo 'No Result Found <br>Some Thing Went Wrong. <a href="incomingcomplaint.php">Go Back</a> ';
    }
    ?>
    </form>
</div>
<?php
    if(isset($_POST['submit'])){
        $complaint_reply = $_POST['complaintreply'];

            $sql_update = "UPDATE complaint SET reply='$complaint_reply',dateofreply='$today_date',replyed='1' WHERE id='$get_complaintid' AND center='$center' AND eid='$eid'";
            $sql_update_query = mysqli_query($conn, $sql_update);
            if ($sql_update_query) {
                header("Location: incomingcomplaint.php?ret=success");
            } else {
                echo '<script>alert("Some Thing Went Wrong.");</script>';
            }
    }
?>
</body>
    </html>
    <?php
}else{
    echo '<h1 align="center">Do Not Try To make us Fool</h1><a href="incomingcomplaint.php">Go Back</a> ';
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