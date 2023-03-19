<?php
//add-bill.php
$current_user = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {     //Check it is coming from a form
    $u_bill = $_POST["Bill_Name"];                   //set PHP variables like this so we can use them anywhere in code below
    $u_date = $_POST["Date"];
    $u_date = date('Y-m-d', strtotime($u_date));
//    print($u_date);
    $u_owed1 = $_POST["U1"];
    $User2 = $_POST["username2"];
    $u_owed2 = $_POST["U2"];
    $User3 = $_POST["username3"];
    $u_owed3 = $_POST["U3"];
    $User4 = $_POST["username4"];
    $u_owed4 = $_POST["U4"];
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

$owed1 = ($u_owed1 == '') ? 0.00 : $u_owed1;
$owed2 = ($u_owed2 == '') ? 0.00 : $u_owed2;
$owed3 = ($u_owed3 == '') ? 0.00 : $u_owed3;
$owed4 = ($u_owed4 == '') ? 0.00 : $u_owed4;

$totalAmt = $owed4 + $owed3 + $owed2 + $owed1;

$sql = "INSERT INTO allExpensesV2 VALUES ('$u_bill', '$u_date', '$totalAmt', 
                                '$current_user', '$owed1', 
                                '$User2','$owed2', 
                                '$User3','$owed3', 
                                '$User4','$owed4')";
//print('entered');
//print($sql);
mysqli_query($mysqli, $sql);

$mysqli->close();

header('Location: shared-expenses.html');
exit();
