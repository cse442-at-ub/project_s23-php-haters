<?php
//add-bill.php


if ($_SERVER["REQUEST_METHOD"] == "POST") {     //Check it is coming from a form
    $u_bill = $_POST["bill"];                   //set PHP variables like this so we can use them anywhere in code below
    $u_date = $_POST["date"];
    $u_date = date('Y-m-d', strtotime($u_date));
//    print($u_date);
    $u_owed1 = $_POST["user1"];
    $u_owed2 = $_POST["user2"];
    $u_owed3 = $_POST["user3"];
    $u_owed4 = $_POST["user4"];
}


$host = "oceanus.cse.buffalo.edu";              // The hostname of the database server
$user = "bensonca";                             // The MySQL user
$password = "50355548";                         // The MySQL user's password
$database = "cse442_2023_spring_team_ae_db";    // The name of the database to connect to
//php 7.3.33
// Create a new mysqli object to establish a database connection
$mysqli = mysqli_connect($host, $user, $password, $database);


// Check for any errors when connecting to the database
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "INSERT INTO Bills_Test_Table VALUES ('$u_bill', '$u_date', '$u_owed1', '$u_owed2', '$u_owed3', '$u_owed4')";
//print('entered');
//print($sql);
mysqli_query($mysqli, $sql);
print("Record inserted successfully");


$mysqli->close();