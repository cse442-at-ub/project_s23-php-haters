<?php
// session_start();
//$current_user = $_SESSION['username'];

$host = "oceanus.cse.buffalo.edu";
$user = "arpithir";
$password = "50340819";
$database = "cse442_2023_spring_team_ae_db";

//make sure we found oceanus
$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
else{
    echo "Connection success";
}
