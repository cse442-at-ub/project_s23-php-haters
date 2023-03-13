<?php
// session_start();
//$current_user = $_SESSION['username'];
//riad needs to save here ^^ once users signup/login

$host = "oceanus.cse.buffalo.edu";
$user = "accartwr";
$password = "50432097";
$database = "cse442_2023_spring_team_ae_db";

//make sure we found oceanus
$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// $sql = "SELECT * FROM roomAidExpenses WHERE username = '$current_user'";
// $sql = "SELECT * FROM roomAidExpenses WHERE username = 'hGilmore909'"; //testing with random user
// $sql = "SELECT expenseName, dueDate, totalAmt FROM roomAidExpenses WHERE username = '$current_user'";
$sql = "SELECT expenseName, dueDate, totalAmt FROM allExpenses WHERE username = 'hGilmore909' ";
//only print basic^^^ table at first?? they needd to click on something to see users?

$result = mysqli_query($conn, $sql);

// try to display here or Arpit does? idk im just trying for now
if (mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<tr><th>Expense Name</th><th>Due Date</th><th>Total Amount</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['expenseName'] . "</td>"; //finds attrib of table :)
        echo "<td>" . $row['dueDate'] . "</td>";
        echo "<td>" . $row['totalAmt'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No expenses to display.";
}



// Close the database connection
mysqli_close($conn);