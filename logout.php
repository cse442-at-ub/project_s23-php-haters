<?php
include_once 'login.php';
//checkLogin($conn);
session_start();
if (isset($_SESSION['username'])) {
    // if its currently set---> need to UNSET
    // also gotta destroy session vars!!!!!
    session_destroy();
    unset($_SESSION['username']); // UNSET session vars
    header('Location: login.php'); //sending to login.php since no landing page
    exit();
} else {
    // else SESSIONs username not set + user not logged in (WONT ever reach this code, but sending to login page just in case)
    header('Location: login.php');
    exit();
}
?>
