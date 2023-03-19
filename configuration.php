<?php
global $host; global $user; global $password; global $database;
$host = "oceanus.cse.buffalo.edu";
$user = "riadmukh";
$password = "50356618";
$database = "cse442_2023_spring_team_ae_db";

$conn = new mysqli($host, $user, $password, $database);

$_SESSION['session_var'] = $conn;


$sql = "INSERT INTO users (usersName, usersEmail, usersUsername, usersPassword) VALUES (?,?,?,?)";
mysqli_query($conn, $sql);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

