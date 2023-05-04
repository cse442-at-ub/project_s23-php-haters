<?php
session_start();
$host = "oceanus.cse.buffalo.edu";
$user = "riadmukh";
$password = "50356618";
$database = "cse442_2023_spring_team_ae_db";

$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['username'])) {
    // Redirect to the login page
    header('Location: login.php');
    exit;
}

function getEmail($member, $mysqli){
    $stmt = $mysqli->prepare("SELECT usersEmail FROM users WHERE usersUsername = ?");
    $stmt->bind_param("s", $member);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $row = $result->fetch_assoc();
    return $row["usersEmail"];
}

function getName($member, $mysqli){
    $stmt = $mysqli->prepare("SELECT usersName FROM users WHERE usersUsername = ?");
    $stmt->bind_param("s", $member);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $row = $result->fetch_assoc();
    return $row["usersName"];
}

function getUsername($member, $mysqli){
    $stmt = $mysqli->prepare("SELECT usersUsername FROM users WHERE usersUsername = ?");
    $stmt->bind_param("s", $member);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $row = $result->fetch_assoc();
    return $row["usersUsername"];
}

$username1 = "";
$name = "";
$email = "";

if(isset($_SESSION['username'])){
    $username = $_SESSION["username"];
    $email = getEmail($username,$conn);
    $name = getName($username,$conn);
    $username1 = getUsername($username,$conn);
}

?>
<!DOCTYPE html>

<html lang="en">
<head>
    <link rel="stylesheet" href="ppage.css">
</head>

<title>Roomaid Register</title>

<script>
    function deleteConfirm() {
        document.getElementById("form-container").style.display = "block";
    }

    function closeDeleteConfirm() {
        document.getElementById("form-container").style.display = "none";
    }

</script>

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
<div class="form-container" id="form-container">
    <form action="delAcc.php" method="post" name="deleteAcc"> <!--change the form action-->

        <h2>Careful! By clicking 'Delete' you are permanently deleting your account and data from RoomAid.</h2>

        <button type="button" id="cancelDel" onclick="closeDeleteConfirm()">Cancel</button>
        <button type="submit" id="deleteAcc">Delete</button>
    </form>
</div>
<br>
    <div class="pfpContainer">
        <img src="https://www.buffalo.edu/content/www/brand/resources-tools/style-guides/student-association/_jcr_content/par/image_2051576783.img.original.jpg/1516727792017.jpg" alt="Profile Picture" class="pfp">
    </div>
    <div class="profileInfo">
        <!--    you need to change these variable according to the database and stuff-->
        <div class="profile">
            <h2>
                <?php
                echo $username1;
                ?>
            </h2>
        </div>
        <div class="profile">
            <h2>
                <?php
                echo $name;
                ?>
            </h2>
        </div>
        <div class="profile">
            <h2>
                <?php
                echo $email;
                ?>
            </h2>
        </div>
    </div>
    <div class="logoutSection">
        <button type="submit" id="logout" onclick="window.location.href='logout.php';">Logout</button>  <!-- link this button to something -->
    </div>


    <div class="deleteSection">
        <h2>Deleting your account is a permanent action. You cannot get any data back. </h2>
        <button type="submit" id="delete" onclick="deleteConfirm()">Delete Account</button>  <!-- link this button to something -->
    </div>



</body>
</html>