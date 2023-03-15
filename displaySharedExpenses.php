<?php
// session_start();
//$current_user = $_SESSION['username'];
//riad needs to save here ^^ once users signup/login
# phpinfo();

$host = "oceanus.cse.buffalo.edu";
$user = "accartwr";
$password = "50432097";
$database = "cse442_2023_spring_team_ae_db";

//make sure we found oceanus
$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//find all expenses with current users 'username'
//$sql = "SELECT * FROM roomAidExpenses WHERE username = '$current_user'";
$sql = "SELECT * FROM allExpenses WHERE username = 'hGilmore909'"; #getting ALL shared users too
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<tr><th>Expense Name</th><th>Due Date</th><th>Total Amount</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['expenseName'] . "</td>";
        echo "<td>" . $row['dueDate'] . "</td>";
        echo "<td>" . $row['totalAmt'] . "</td>";
        echo "</tr>";
        echo "<tr><td colspan='3'>";
        echo "<table>";
        if (!empty($row['user1'])) {
            $amt_per_user = $row['user1amt'];
            echo "<tr><td>" . $row['user1'] . "</td><td>$" . number_format($amt_per_user, 2) . "</td></tr>";
        }
        if (!empty($row['user2'])) {
            $amt_per_user = $row['user2amt'];
            echo "<tr><td>" . $row['user2'] . "</td><td>$" . number_format($amt_per_user, 2) . "</td></tr>";
        }
        if (!empty($row['user3'])) {
            $amt_per_user = $row['user3amt'];
            echo "<tr><td>" . $row['user3'] . "</td><td>$" . number_format($amt_per_user, 2) . "</td></tr>";
        }
        if (!empty($row['user4'])) {
            $amt_per_user = $row['user4amt'];
            echo "<tr><td>" . $row['user4'] . "</td><td>$" . number_format($amt_per_user, 2) . "</td></tr>";
        }
        echo "</table>";
        echo "</td></tr>";
    }
    echo "</table>";
} else {
    echo "No expenses found for the current user.";
}


mysqli_close($conn);








