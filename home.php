
<?php
session_start();
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
            <ul>
                <li><a href="home.php " class="nav-button">Home</a></li>
                <li><a href="task-schedule.php" class="nav-button">Schedule</a></li>
                <li><a href="group.php" class="nav-button">Group</a></li>
                <li><a href="inventory.php" class="nav-button">Inventory</a></li>
                <li><a href="Shared_Expenses.php" class="nav-button">Expenses</a></li>
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
        <p class="inside-rectangles"> Hertz: </p>
        <p class="inside-rectangles"> Hunt: </p>
        <p class="inside-rectangles"> Hartloff: </p>
    </div>

    <div class="todo">
        <h2> Tasks To Do: </h2>
        <p class="inside-rectangles"> Dishes: </p>
        <p class="inside-rectangles"> Cleaning: </p>
        <p class="inside-rectangles"> Trash: </p>
    </div>

    <div class="empty">
        <h2> Inventory Empty: </h2>
        <p class="inside-rectangles"> Watermelons: </p>
        <p class="inside-rectangles"> Honeydew melon: </p>
        <p class="inside-rectangles"> Muskmelon: </p>
    </div>
</div>


</body>
</html>
