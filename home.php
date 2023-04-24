
<?php
include 'util.php';
session_start();
$host = "oceanus.cse.buffalo.edu";
$user = "venkatay";
$password = "50337119";
$database = "cse442_2023_spring_team_ae_db";

$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$name = $_SESSION["username"];
$useremail = getEmail($name,$conn);
$groupname = getGroupName($name,$conn);

$expenses_sql = "SELECT * FROM allExpensesV2 WHERE ((username = '$name' and user1amt != '0.00') OR (user2 = '$name' and user2amt != '0.00') OR (user3 = '$name' and user3amt != '0.00') OR (user4 = '$name' and user4amt != '0.00'))";
$expenses_res = mysqli_query($conn, $expenses_sql);
$expenses = mysqli_fetch_all($expenses_res, MYSQLI_ASSOC);

$task_sql = "SELECT * FROM tasks WHERE email = '$useremail' ORDER BY due_date";
$task_res = mysqli_query($conn, $task_sql);
$tasks = mysqli_fetch_all($task_res, MYSQLI_ASSOC);

$inventory_sql = "SELECT * FROM groupInventory WHERE (groupName = '$groupname') ORDER BY quantity";
$inventory_res = mysqli_query($conn, $inventory_sql);
$items = mysqli_fetch_all($inventory_res, MYSQLI_ASSOC);
?>
 
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" type="text/css" href="home.css">

<body>
<header>
    <div>
        <img class='icon' src="Saturn.png" alt="RoomAid">
        <span class="h3"> RoomAid </span>
        <nav>
            <li>
                <li><a href="home.php " class="nav-button">Home</a></li>
                <li><a href="task-schedule.php" class="nav-button">Schedule</a></li>
                <li><a href="group.php" class="nav-button">Group</a></li>
                <li><a href="inventory.php" class="nav-button">Inventory</a></li>
                <li><a href="Shared_Expenses.php" class="nav-button">Expenses</a></li>
                <li><a href="#"><img id='icon-pfp' src="profile.png" alt="Profile"></a></li>
            </ul>
        </nav>
        <a href="#"><img id='icon-pfp' src="profile.png" alt="Profile"></a>
    </div>
</header>

<div class="greeting">
    <h2 class="h2 nav-button">Welcome to RoomAid! Your personal roommate solution.</h2>
    <h4 class="below-h2">Say hello to the fair way of dividing tasks and managing your apartment.</h4>
</div>
<div class="container">
    <div class="money">
        <h2> Money Owed: </h2>
        <?php if(count($expenses) >= 5){ ?>
             <?php for ($i = 0; $i < 5; $i++) {  ?>
                    <?php if($expenses[$i]['username'] === $name){?>
                         <p class="inside-rectangles">
                        <?php echo $expenses[$i]['expenseName']. ' $' . $expenses[$i]['user1amt']; ?>
                        </p>
                    <?php } ?>
                    <?php if($expenses[$i]['user2'] === $name){?>
                        <p class="inside-rectangles">
                            <?php echo $expenses[$i]['expenseName']. ' $' . $expenses[$i]['user2amt']; ?>
                        </p>
                    <?php } ?>
                    <?php if($expenses[$i]['user3'] === $name){?>
                    <p class="inside-rectangles">
                        <?php echo $expenses[$i]['expenseName']. ' $' . $expenses[$i]['user3amt']; ?>
                    </p>
                    <?php } ?>
                    <?php if($expenses[$i]['user4'] === $name){?>
                        <p class="inside-rectangles">
                            <?php echo $expenses[$i]['expenseName']. ' $' . $expenses[$i]['user4amt']; ?>
                        </p>
                    <?php } ?>
        <?php } } ?>
    </div>

    <div class="todo">
        <h2> Tasks To Do: </h2>
        <?php if(count($tasks) >= 5){ ?>
            <?php for ($i = 0; $i < 5; $i++) {  ?>
                <p class="inside-rectangles">
                    <?php echo $tasks[$i]['task']. ' Due: ' . $tasks[$i]['due_date']; ?>
                </p>
            <?php } } ?>
    </div>

    <div class="empty">

        <?php $lowitems_sql = "SELECT * FROM groupInventory WHERE ((groupName = '$groupname') AND (quantity < 3)) ORDER BY quantity";
        $lowitems_res = mysqli_query($conn, $lowitems_sql);
        $lowitems = mysqli_fetch_all($lowitems_res, MYSQLI_ASSOC);

        if(count($lowitems) > 0){ ?>
            <h2> Low Stock Items: </h2>
           <?php for ($i = 0; $i < count($lowitems); $i++) { ?>
            <p class="inside-rectangles">
                <?php echo $lowitems[$i]['itemName']. ' Quantity: ' . $lowitems[$i]['quantity']; ?>
            </p>
        <?php } } ?>
        <?php if(count($lowitems) == 0){ ?>
            <h2> Inventory: </h2>
            <?php for ($i = 0; ($i < count($items) AND ($i < 5)); $i++) { ?>
            <p class="inside-rectangles">
                <?php echo $items[$i]['itemName']. ' Quantity: ' . $items[$i]['quantity']; ?>
            </p>
        <?php }}?>


    </div>
</div>


</body>
</html>
