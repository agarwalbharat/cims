<?php 
session_start();
if(isset($_SESSION['id']) AND isset($_SESSION['username'])) {
    include("../../config/database.php");
    $id = $_SESSION['id'];
    $eid = $_SESSION['username'];
    $sql = "SELECT position FROM teachers WHERE eid = '$eid'";
    $sql_result = mysqli_query($conn,$sql);
    $sql_result_check = mysqli_num_rows($sql_result);
    if($rows=mysqli_fetch_assoc($sql_result)){
        $position = $rows['position'];
    }else{
        echo 'Something Went Wrong. Please Contact your branch Admin';
    }
    if($position == "mentor"){
        header("Location: mentor/");
        exit();
    }
    if($position == "hod"){
        header("Location: hod/");
        exit();
    }
    if($position == "teacher"){
        header("Location: teacher/");
        exit();
    }

}else{
    header("Location: ../../");
    exit();
}
?>