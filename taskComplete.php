<?php
include 'util.php';

// Connect to the database
$mysqli = connect();

// Get the task ID from the form submission
$task = mysqli_real_escape_string($mysqli, $_POST['check_task']);
$imp = mysqli_real_escape_string($mysqli, $_POST['check_imp']);
$date = mysqli_real_escape_string($mysqli, $_POST['check_date']);


// Delete the task from the database
$stmt = $mysqli->prepare("DELETE FROM tasks WHERE
                      task = ? AND 
                      importance = ? AND 
                      due_date = ?");
$stmt->bind_param("sis", $task, $imp, $date);
$stmt->execute();

// Redirect back to the page where the task list is displayed
header('Location: task-schedule.php');
exit();
