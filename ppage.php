<?php
include 'util.php';

session_start();
$host = "oceanus.cse.buffalo.edu";
$user = "riadmukh";
$password = "50356618";
$database = "cse442_2023_spring_team_ae_db";

$user_id = $_SESSION['username'];
$max_size = 8 * 1024 * 1024; // 8MB byte max size automatically set my php

$image_path = "uploads/$user_id/";

$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['username'])) {
    // Redirect to the login page
    header('Location: login.php');
    exit;
}

//function getEmail($member, $mysqli){
//    $stmt = $mysqli->prepare("SELECT usersEmail FROM users WHERE usersUsername = ?");
//    $stmt->bind_param("s", $member);
//    $stmt->execute();
//    $result = $stmt->get_result();
//    $stmt->close();
//    $row = $result->fetch_assoc();
//    return $row["usersEmail"];
//}

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
        <?php
    $files = glob($image_path . '*.{jpg,jpeg,png,gif}', GLOB_BRACE); // check if an image is already uploaded
    if (count($files) > 0 && !isset($_FILES["image"])) { // only display the image if a new photo has not been uploaded
        $image_filename = basename($files[0]);
        echo "<label for='image' style>";
        echo "<img src='$image_path$image_filename' class='pfp' style='cursor: pointer' alt='Profile-Picture'>";
        echo "</label>";
        $_SESSION["image_filename"] = $image_path.$image_filename;
        echo "<form action='' class='pfp' method='POST' enctype='multipart/form-data' name='aForm' id='aForm' style='display:none;'>";
    }
//    echo "Currently No Profile Picture";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//        echo "Post method requested";
//        echo "POST REQUESTED";
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
//            // get the file details
            $file_name = $_FILES['image']['name'];
            $file_size = $_FILES['image']['size'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_type = $_FILES['image']['type'];
            echo $file_size, $file_type;
//            trigger_error($file_name, $file_size, $file_tmp, $file_type);
//
            if (!validImage(substr($file_type, 6))) {
                header("location: ppage.php");
            } else {
//                echo validImage(substr($file_type, 6));
//            // Delete all files in the directory
                $target_dir = 'uploads/' . $user_id . '/';
                $files = glob($target_dir . '*');
                foreach ($files as $file) {
                    if (is_file($file)) { // Check if it's a file and not a directory
                        unlink($file); // Delete the file
                    }
                }
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true); // create the directory if it doesn't exist
                }
//
                $target_file = $target_dir . $user_id . '_' . basename($_FILES["image"]["name"]); // get the full path of the uploaded file with the username preceeding the image name
                $target_file = $target_dir . basename($_FILES["image"]["name"]); // get the full path of the uploaded file
                $_SESSION["image_filename"] = $target_file;
//
                if ($_FILES["image"]["size"] > $max_size) {
                    trigger_error("file too large");
                    die();
                } else {
//                    echo "made it here";
                    move_uploaded_file($file_tmp, $target_file);
                    echo "<label for='image'>";
                    echo "<img src='$target_file' style='cursor: pointer' class='pfp' alt='Profile-Picture' class='profile-image'>";
                    echo "</label>";
                }
//
            }
            header('Location: ppage.php');
            exit;
        } else {
            header('Location: ppage.php');
            exit;

        }
    }
    ?>
        <form action="" class='pfp' method="POST" enctype="multipart/form-data" name="aForm" id="aForm">
            <label for="image"></label>
            <input type="file" id="image" name="image" style="align-content: center" accept="image/jpeg, image/jpg, image/png">
            <br><br>
            <input type="submit" value="Upload" id="submitButton" accept="image/jpeg, image/jpg, image/png" style="display:none;">
        </form>
        <script>
            const form = document.getElementById('aForm');
            const image = document.getElementById('image');
            image.addEventListener('change', function() {
                form.submit();
                form.style.display = 'none';
            });
        </script>
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