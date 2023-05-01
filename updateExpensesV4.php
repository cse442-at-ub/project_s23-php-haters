<?php

session_start();
$current_user = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {     //Check it is coming from a form
    $u_bill = $_POST["expense-name"];                   //set PHP variables like this so we can use them anywhere in code below
    $u_date = $_POST["due-date"];
    $u_date = date('Y-m-d', strtotime($u_date));

    $User1 = $current_user;
    $u_owed1 = $_POST["amount1"];

    $User2 = $_POST["user2"];
    $u_owed2 = $_POST["amount2"];

    $User3 = $_POST["user3"];
    $u_owed3 = $_POST["amount3"];

    $User4 = $_POST["user4"];
    $u_owed4 = $_POST["amount4"];
}

$host = "oceanus.cse.buffalo.edu";
$user = "accartwr";
$password = "50432097";
$database = "cse442_2023_spring_team_ae_db";
//php 7.3.33
// Create a new mysqli object to establish a database connection
$mysqli = mysqli_connect($host, $user, $password, $database);

// Check for any errors when connecting to the database
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$owed1 = ($u_owed1 == '') ? 0.00 : $u_owed1;
$owed2 = ($u_owed2 == '') ? 0.00 : $u_owed2;
$owed3 = ($u_owed3 == '') ? 0.00 : $u_owed3;
$owed4 = ($u_owed4 == '') ? 0.00 : $u_owed4;

$totalAmt = $owed4 + $owed3 + $owed2 + $owed1;

$sql = "INSERT INTO allExpensesV2 VALUES ('$u_bill', '$u_date', '$totalAmt', 
                                '$User1', '$owed1', 
                                '$User2','$owed2', 
                                '$User3','$owed3', 
                                '$User4','$owed4')";
//print('entered');
//print($sql);
mysqli_query($mysqli, $sql);

$mysqli->close();

header('Location: expensesV4.php');
exit();
