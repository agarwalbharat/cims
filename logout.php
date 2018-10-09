<?php
/**
 * Created by PhpStorm.
 * User: Bharat
 * Date: 6/7/2018
 * Time: 10:56 PM
 */

    session_start();
    session_unset();
    session_destroy();
    header("Location: index.php");
?>