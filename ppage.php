<?php
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <link rel="stylesheet" href="ppage.css">
</head>

<title>Roomaid Register</title>

<header>
    <div>
        <img class='icon' src="Saturn.png" alt="RoomAid">
        <span class="h3">RoomAid</span>
        <nav>
            <ul>
                <li><a href="home.php" class="nav-button">Home</a></li>
                <li><a href="task-schedule.php" class="nav-button">Schedule</a></li>
                <li><a href="group.php" class="nav-button">Calendar</a></li>
                <li><a href="inventory.php" class="nav-button">Inventory</a></li>
                <li><a href="Shared_Expenses.php" class="nav-button">Expenses</a></li>
            </ul>
        </nav>
    </div>
</header>

<!--username, name, email, image, logout, delete button but make a popup that says-->
<body>
<br>
<div class="pfpContainer">
    <img src="https://www.buffalo.edu/content/www/brand/resources-tools/style-guides/student-association/_jcr_content/par/image_2051576783.img.original.jpg/1516727792017.jpg" alt="Profile Picture" class="pfp">
</div>
<div class="profileInfo">
<!--    you need to change these variable according to the database and stuff-->
    <div class="profile">
        <h2>.$username.</h2>
    </div>
    <div class="profile">
        <h2>.$name.</h2>
    </div>
    <div class="profile">
        <h2>.$email.</h2>
    </div>
</div>

<div class="logoutSection">
    <button type="submit" id="logout" onclick="window.location.href='login.php';">Logout</button>  <!-- link this button to something -->
</div>

<div class="deleteSection">
    <h2>Deleting your account is a permanent action. You cannot get any data back. </h2>
    <button type="submit" id="delete">Delete Account</button>  <!-- link this button to something -->
</div>

</body>
</html>