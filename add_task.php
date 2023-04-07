<?php
include 'util.php';

session_start();
//$name = $_SESSION["username"];
//$name = "Ben";
//$name = "asfd";
$name = 'hGilmore909';

$host = "oceanus.cse.buffalo.edu";              // The hostname of the database server
$user = "bensonca";                             // The MySQL user
$password = "50355548";                         // The MySQL user's password
$database = "cse442_2023_spring_team_ae_db";    // The name of the database to connect to


// Create a new mysqli object to establish a database connection
$mysqli = mysqli_connect($host, $user, $password, $database);
if ($mysqli->connect_error) {
    echo($mysqli->connect_error);
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_name = $_POST["task-name"];
    $importance = $_POST["priority"];
    echo $importance;
    $due_date = date('Y-m-d', strtotime($_POST["due-date"]));
}

$email = "sampleuser1@gmail.com";
$group_name = getGroupName($name, $mysqli);
//$group_name = $group_name->fetch_assoc();
//$group_name = $group_name["groupName"];

//$sql = "INSERT INTO tasks (task, importance, due_date) VALUES ('$task_name', '$importance', '$due_date')";
// Bind the user input values to the prepared statement (strin, int, string, string)
// Execute the prepared statement

$stmt = $mysqli->prepare("INSERT INTO tasks (task, importance, due_date, email, groupName) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sisss", $task_name, $importance, $due_date, $email, $group_name);
$stmt->execute();

$stmt->close();
$mysqli->close();

header('Location: task-schedule.php');
exit();