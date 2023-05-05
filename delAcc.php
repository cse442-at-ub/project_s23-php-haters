<?php

session_start();

$host = "oceanus.cse.buffalo.edu";
$user = "venkatay";
$password = "50337119";
$database = "cse442_2023_spring_team_ae_db";
$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function getEmail($member, $mysqli){
    $stmt = $mysqli->prepare("SELECT usersEmail FROM users WHERE usersUsername = ?");
    $stmt->bind_param("s", $member);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $row = $result->fetch_assoc();
    return $row["usersEmail"];
}

function getGroupName($username, $mysqli){
    $name = $username;
    $stmt = $mysqli->prepare("SELECT groupName FROM `groupTestV2` WHERE username = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();               // Find the group the user is in
    $result = $stmt->get_result();
    $stmt->close();
    $row = $result->fetch_assoc();
    return $row["groupName"];
}


$username = $_SESSION['username'];
$email = getEmail($username,$conn);
$groupname = getGroupName($username,$conn);

$sql = "UPDATE groupInventory SET groupName = 'Test' WHERE groupName = '$groupname'";
mysqli_query($conn, $sql);
$sql1 = "DELETE FROM groupTestV2 WHERE username = '$username'";
mysqli_query($conn, $sql1);
$sql4 = "UPDATE groupInventory SET groupName = '$groupname' WHERE groupName = 'Test'";
mysqli_query($conn, $sql4);
$sql2 = "DELETE FROM tasks WHERE email='$email'";
mysqli_query($conn, $sql2);
$sql5 = "DELETE FROM allExpensesV2 WHERE (username = '$username' AND user2amt = '0.00' AND user3amt = '0.00' AND user4amt = '0.00')";
mysqli_query($conn, $sql5);
$sql6 = "UPDATE allExpensesV2 SET username = user2, user1amt = user2amt, user2 = '', user2amt = '0.00' WHERE username = '$username' AND user2amt != '0.00'";
mysqli_query($conn, $sql6);
$sql7 = "UPDATE allExpensesV2 SET username = user3, user1amt = user3amt, user3 = '', user3amt = '0.00' WHERE username = '$username' AND user3amt != '0.00'";
mysqli_query($conn, $sql7);
$sql8 = "UPDATE allExpensesV2 SET username = user4, user1amt = user4amt, user4 = '', user4amt = '0.00' WHERE username = '$username' AND user4amt != '0.00'";
mysqli_query($conn, $sql8);
$sql9 = "UPDATE allExpensesV2 SET user2 = '', user2amt = '0.00' WHERE user2 = '$username'";
mysqli_query($conn, $sql9);
$sql10 = "UPDATE allExpensesV2 SET user3 = '', user3amt = '0.00' WHERE user3 = '$username'";
mysqli_query($conn, $sql10);
$sql11 = "UPDATE allExpensesV2 SET user4 = '', user4amt = '0.00' WHERE user4 = '$username'";
mysqli_query($conn, $sql11);
$sql3 = "DELETE FROM users WHERE usersUsername='$username'";
mysqli_query($conn, $sql3);

$conn->close();

header('Location: register.php');
exit();
