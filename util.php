<?php

$host = "oceanus.cse.buffalo.edu";              // The hostname of the database server
$user = "bensonca";                             // The MySQL user
$password = "50355548";                         // The MySQL user's password
$database = "cse442_2023_spring_team_ae_db";    // The name of the database to connect to

$mysqli = mysqli_connect($host, $user, $password, $database);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

function connect(){
    $host = "oceanus.cse.buffalo.edu";              // The hostname of the database server
    $user = "bensonca";                             // The MySQL user
    $password = "50355548";                         // The MySQL user's password
    $database = "cse442_2023_spring_team_ae_db";    // The name of the database to connect to

    return mysqli_connect($host, $user, $password, $database);
}

function getGroupName($username, $mysqli){
    $name = $username;
    $stmt = $mysqli->prepare("SELECT groupName FROM `groupTest` WHERE username = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();               // Find the group the user is in
    $result = $stmt->get_result();
    $stmt->close();
    $row = $result->fetch_assoc();
    return $row["groupName"];
}

function getTasks($groupName, $mysqli){
    $stmt = $mysqli->prepare("SELECT * FROM tasks WHERE groupName = ?");
    $stmt->bind_param("s", $groupName);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $mysqli->close();

    return $result;
}

function removeOverdue(){
    $mysqli = connect();
    $current_datetime = date('Y-m-d H:i:s');
    $stmt = $mysqli->prepare("DELETE FROM tasks WHERE due_date <  ? ");
    $stmt-> bind_param("d", $current_datetime);
    $stmt-> execute();
}