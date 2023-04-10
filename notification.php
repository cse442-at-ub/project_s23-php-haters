<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connect to the database
$host = "oceanus.cse.buffalo.edu";
$dbuser = "riadmukh";
$password = "50356618";
$database = "cse442_2023_spring_team_ae_db";
$conn = new mysqli($host, $dbuser, $password, $database);

// Retrieve the tasks due within the next three days
$now = time();
$three_days = 3 * 24 * 60 * 60;
$twenty_four_hours = 60 * 60 * 24;

$reminder_due_date_1 = date('Y-m-d H:i:s', $now + $three_days);
$reminder_due_date_2 = date('Y-m-d H:i:s', $now + $twenty_four_hours);
$reminder_due_date_3 = date('Y-m-d H:i:s', $now + $twenty_four_hours + $twenty_four_hours);

// Tasks due in less than 3 days
$sql = "SELECT task, due_date, email FROM tasks WHERE (due_date BETWEEN '$reminder_due_date_1' AND '$reminder_due_date_2')";
$result = mysqli_query($conn, $sql);

// Loop through the tasks and send reminder emails
while ($row = mysqli_fetch_assoc($result)) {
    $task_title = $row['task'];$task_due_date = $row['due_date'];
    $user_email = $row['email'];

    // send reminder email
    $to = $user_email;
    $subject = 'RoomAid Task Reminder';
    $message = "This is a reminder that you have a task, $task_title, due on $task_due_date within the next 3 days.";
    // sending the email
    mail($to, $subject, $message);
}

// Tasks due in less than 24 hours
$sql = "SELECT task, due_date, email FROM tasks WHERE (due_date BETWEEN '$reminder_due_date_2' AND '$reminder_due_date_3')";
$result = mysqli_query($conn, $sql);

// Loop through the tasks and send reminder emails
while ($row = mysqli_fetch_assoc($result)) {
    $task_title = $row['task'];
    $task_due_date = $row['due_date'];
    $user_email = $row['email'];
    
    // send reminder email
    $to = $user_email;
    $subject = 'RoomAid Task Reminder';
    $message = "This is a reminder that you have a task, $task_title, due on $task_due_date within the next 24 hours.";
    // sending the email
    mail($to, $subject, $message);
}
// Close the database connection
mysqli_close($conn);
