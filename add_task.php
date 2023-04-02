<?php

$host = "oceanus.cse.buffalo.edu";              // The hostname of the database server
$user = "bensonca";                             // The MySQL user
$password = "50355548";                         // The MySQL user's password
$database = "cse442_2023_spring_team_ae_db";    // The name of the database to connect to

// Create a new mysqli object to establish a database connection
$mysqli = mysqli_connect($host, $user, $password, $database);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_name = $_POST["task-name"];
    $importance = $_POST["priority"];
    $due_date = date('Y-m-d', strtotime($_POST["due-date"]));
}

$email = "sampleuser1@gmail.com";

//$sql = "INSERT INTO tasks (task, importance, due_date) VALUES ('$task_name', '$importance', '$due_date')";
// Prepare a SQL statement with placeholders for the user input
$stmt = $mysqli->prepare("INSERT INTO tasks (task, importance, due_date, email) VALUES (?, ?, ?, ?)");

// Bind the user input values to the prepared statement (strin, int, string, string)
$stmt->bind_param("siss", $task_name, $importance, $due_date, $email);

// Execute the prepared statement
$stmt->execute();

//$sql = "INSERT INTO tasks VALUES ('$task_name', '$importance', '$due_date', '$email')";
//mysqli_query($mysqli, $sql);

$stmt->close();
$mysqli->close();


exit();