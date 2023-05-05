<?php
    session_start();

    //Database Connection
    $host = "oceanus.cse.buffalo.edu";
    $user = "arpithir";
    $pass = "50340819";
    $database = "cse442_2023_spring_team_ae_db";

    //make sure we found oceanus
    $conn = mysqli_connect($host, $user, $pass, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $current_user = $_SESSION['username'];

    if (!$current_user) { ?>
        <h2 style="font-family: 'monospace';">YOU MUST SIGN IN FIRST!!! <?php
            header('Location: login.php'); ?></h2>
    <?php }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
    <title>Find Group</title>
    <link rel="stylesheet" href="find_group.css">

<!--The Script for opening and closing of create group form-->
    <script>
        function openGroupForm() {
            document.getElementById("groupForm").style.display = "block";
            document.getElementById("search").style.display = "none";
            document.getElementById("search_button").style.display = "none";
            document.getElementById("groupbtn").style.display = "none";
        }

        function closeGroupForm() {
            document.getElementById("groupForm").style.display = "none";
            document.getElementById("search").style.display = "block";
            document.getElementById("search_button").style.display = "block";
            document.getElementById("groupbtn").style.display = "block";
        }
    </script>
</head>
<body>

<div class="search">
    <form class="search" method="post" action="find_group_be.php">
        <input id="search" type="text" placeholder="Search Group" name="search" required>
        <button class="search" id="search_button" type="submit" name="submit">Search</button>
    </form>
</div>


<button class="open-button" id="groupbtn" onclick="openGroupForm()">Create A Group</button>
<div class="form-popup" id="groupForm">
    <form action="find_group_be.php" method="post" name="createGRPForm" class="form-container">
        <h2 id="creategrp">Create A Group</h2>

        <input id="grpname" type="text" placeholder="Enter Group Name" name="group" required>

        <button type="submit" class="btn">Create</button>
        <button type="button" class="btn cancel" onclick="closeGroupForm()">Cancel</button>
    </form>
</div>

</body>
</html>
