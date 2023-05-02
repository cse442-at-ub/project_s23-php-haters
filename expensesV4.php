<?php
include 'util.php';
//include 'header.php';


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

if (!$current_user) { ?>
    <h2 style="font-family: 'monospace';">YOU MUST SIGN IN FIRST!!! <?php
        header('Location: login.php'); ?></h2>
<?php }

$sql = "SELECT groupName FROM groupTestV2 WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $current_user);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($groupName);
$result_group  = $stmt->fetch();

$_SESSION['groupName'] = $groupName;
//try to get group members to displays
if (!$result_group) { ?>
    <h2 style="font-family: cursive;">Hello <?php echo $_SESSION['username'] ;?>, You must join a group to view expenses!</h2>
<?php } else { ?>
    <h2 style="font-family: cursive;">Hello <?php echo $_SESSION['username'] ;?>, Your viewing <?php echo $groupName;?>'s expenses:</h2>
<?php }

//modifying bensons util funct
function getGroupMembersEXPENSE($groupName, $mysqli){ # given the group name, get the group members in form of a dropdown
    # and a connection (use connect())
    $stmt = $mysqli->prepare("SELECT users.usersUsername
        FROM groupTestV2
        JOIN users ON groupTestV2.username = users.usersUsername
        WHERE groupTestV2.groupName = ?;");
    $stmt->bind_param("s", $groupName);
    $stmt->execute();
    $result = $stmt->get_result();

//    $dropdown = "<select name='members' class='" . 'assignMember' . "'>";
    $dropdown = "<select name='members'>";
    while ($row = $result->fetch_assoc()) {
        $dropdown .= "<option value='" . $row['usersUsername'] . "'>" . $row['usersUsername'] . "</option>";
    }
    $dropdown .= "</select>";

    $assignedTo = "<span>Assigned to: </span>";
    $output = "<div class='taskDueDate'>" . $assignedTo . $dropdown . "</div>";


    $stmt->close();
    $mysqli->close();

    return $output;
}
?>





?>
<!DOCTYPE html>
<html>
<head>
    <title>Expenses</title>
    <link rel="stylesheet" href="expensesV4.css">
</head>

<body>
<header>
    <div>
        <img class='icon' src="Saturn.png" alt="RoomAid">
        <span class="h3"> RoomAid </span>
        <nav>
            <ul>
                <li><a href="home.php" class="nav-button">Home</a></li>
                <li><a href="task-schedule.php" class="nav-button">Schedule</a></li>
                <li><a href="group.php" class="nav-button">Group</a></li>
                <li><a href="inventory.php" class="nav-button">Inventory</a></li>
                <li><a href="expensesV4.php" class="nav-button">Expenses</a></li>
                <li><a href="ppage.php"><img id='icon-pfp' src="profile.png" alt="Profile"></a></li>
            </ul>
        </nav>
    </div>
</header>

<div>
    <button id="add-expense-btn" class="btn" style="background-color: #EEE3A4; color: #000000;" onclick="showAddExpenseBox()">Add Expense</button>
</div>

<div id="add-expense-modal" style="display: none;">
    <form id="add-expense-form" method="post" action="updateExpensesV4.php">
        <label for="expense-name">Name:</label>
        <input type="text" id="expense-name" name="expense-name" required>
        <br>
        <label for="due-date">Due Date:</label>
        <input type="date" id="due-date" name="due-date" required>
        <br>

        <label for="amount1">Your Share:</label>
        <input type="number" name="amount1" step="0.01" required>

        <?php
        $dropdown = getGroupMembersEXPENSE($groupName, connect());
        for ($i = 2; $i <= 4; $i++) {
            echo "<div>";
            echo "<label for='user{$i}'>User{$i}:</label>";
            echo $dropdown;
            echo "<label for='amount{$i}'>Amount:</label>";
            echo "<input type='number' name='amount{$i}' step='0.01'>";
            echo "</div>";
        }
        ?>

        <br><br>
        <input type="hidden" name="action" value="add">
        <button class="btn" type="submit" onclick="submitAddExpenseForm(event)">Add</button>
        <button class="btn" type="button" onclick="hideAddExpenseBox()">Close</button>
    </form>
</div>
</div>





<div class="scroll-box">
    <?php
    if (!$result_group) {
        echo "$current_user must join a group to view expenses!";
    } else {
        $sql_expenses = "SELECT * FROM allExpensesV2 WHERE (username = '$current_user' OR user2 = '$current_user' OR user3 = '$current_user' OR user4 = '$current_user')";
        $result_expenses = mysqli_query($conn, $sql_expenses);
        $expenses = mysqli_fetch_all($result_expenses, MYSQLI_ASSOC);

        // Display expenses
        foreach ($expenses as $expense) {
            echo "<div class='expense'>";
            echo "<div class='expense-content'>";
            echo "<span>" . $expense['expenseName'] . "</span>";
            echo "<span> Due Date: " . $expense['dueDate'] . "</span>";
            echo "<span> Total Amount: $" . $expense['totalAmt'] . "</span>";
            echo "</div>";
            echo "</div>";
        }

    }
    $stmt->close();
    ?>
</div>
<script>
    const modal = document.getElementById("add-expense-modal");
    const form = modal.querySelector("form");

    function showAddExpenseBox() {
        const modal = document.getElementById("add-expense-modal");
        modal.style.display = "block";
    }

    function hideAddExpenseBox() {
        const modal = document.getElementById("add-expense-modal");
        modal.style.display = "none";
    }

    function submitAddExpenseForm(e) {
        e.preventDefault();
        const form = document.getElementById("add-expense-form");
        form.submit();
    }

</script>
</body>
</html>
