<?php

$billName = $_POST['Bill_Name'];
$date = $_POST['Date'];
//Taking these users as test users will replace them as soon as we are done with group formation page;
//The user 1 or username will  be the current user that inputs the bill information
$user1 = 'hGilmore909';
$user2 = 'arpithir';
$user3 = 'Adam';
$user4 = 'Ben';
$user1Amount = $_POST['U1'];
$user2Amount = $_POST['U2'];
$user3Amount = $_POST['U3'];
$user4Amount = $_POST['U4'];
$totalAmount = $user1Amount + $user2Amount + $user3Amount + $user4Amount;

//Database Connection
$host = "oceanus.cse.buffalo.edu";
$user = "arpithir";
$password = "50340819";
$database = "cse442_2023_spring_team_ae_db";

//make sure we found oceanus
$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    $arr = $conn->prepare(" insert into allExpenses(expenseName, dueDate, totalAmt, username, user1amt, user2, user2amt, user3, user3amt, user4, user4amt) values(?,?,?,?,?,?,?,?,?,?,?)");
    $arr->bind_param("ssisisisisi", $billName, $date, $totalAmount, $user1, $user1Amount, $user2, $user2Amount, $user3, $user3Amount, $user4, $user4Amount);
    $arr->execute();
    echo "<script>window.location.href='Shared_Expenses.php';</script>";
    $arr->close();
    $conn->close();
}