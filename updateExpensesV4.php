<?php

session_start();
$host = "oceanus.cse.buffalo.edu";
$user = "accartwr";
$password = "50432097";
$database = "cse442_2023_spring_team_ae_db";
$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$current_user = $_SESSION['username'];
$groupName = $_SESSION['groupName'];

$expense_name = $_POST['expense-name'];
$action = $_POST['action'];

if ($action == 'add') {
    $due_date = $_POST['due-date'];
    $amounts = array();
    for ($i = 1; $i <= 4; $i++) {
        $amounts[$i] = isset($_POST["amount{$i}"]) ? $_POST["amount{$i}"] : 0;
    }
    $total_amount = array_sum($amounts);

    $users = array($current_user);
    $members = isset($_POST['members']) ? $_POST['members'] : array();
    foreach ($members as $member) {
        if ($member !== 'NULL') {
            $users[] = $member;
        }
    }

    $sql_add = "INSERT INTO allExpensesV2 (expenseName, dueDate, totalAmt, username, user1amt, user2, user2amt, user3, user3amt, user4, user4amt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_add = $conn->prepare($sql_add);
    $stmt_add->bind_param("ssdsdsdsdsd", $expense_name, $due_date, $total_amount, $users[0], $amounts[1], $users[1], $amounts[2], $users[2], $amounts[3], $users[3], $amounts[4]);
    $stmt_add->execute();

    if ($stmt_add->error) {
        echo "Error: " . $stmt_add->error;
    } else {
        echo "Expense added successfully";
    }

    $stmt_add->close();
    header("Location: expensesV4.php");
    exit;
} elseif ($action == 'delete') {
    $sql_delete = "DELETE FROM allExpensesV2 WHERE expenseName = ? AND (username = ? OR user2 = ? OR user3 = ? OR user4 = ?)";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("sssss", $expense_name, $current_user, $current_user, $current_user, $current_user);
    $stmt_delete->execute();
    $stmt_delete->close();
    header("Location: expensesV4.php");
    exit;
}

?>