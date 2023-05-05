<?php
include 'util.php';

session_start();
$host = "oceanus.cse.buffalo.edu";
$user = "bensonca";
$password = "50355548";
$database = "cse442_2023_spring_team_ae_db";

$user_id = $_SESSION['username'];
$max_size = 8 * 1024 * 1024; // 8MB byte max size automatically set my php

$image_path = "uploads/$user_id/";

$mysqli = mysqli_connect($host, $user, $password, $database);
if (!$mysqli) {
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
$username = $_SESSION["username"];

if(isset($_SESSION['username'])){
    $email = getEmail($username,$mysqli);
    $name = getName($username,$mysqli);
    $username1 = getUsername($username,$mysqli);
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Check if an image has been uploaded
            if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
                // Get the file details
                $file_name = $_FILES['image']['name'];
                $file_size = $_FILES['image']['size'];
                $file_tmp = $_FILES['image']['tmp_name'];
                $file_type = $_FILES['image']['type'];

                if (!validImage(substr($file_type, 6))) {
                    header("location: ppage.php");
                } else {

                    $image_data = file_get_contents($_FILES['image']['tmp_name']);
                    if ($image_data === false) {
                        echo 'Error reading file';
                        exit;
                    }
                    $image_base64 = base64_encode($image_data);


                    // Delete the previous image from the database, if it exists
                    $sql = "DELETE FROM homies WHERE user = ?";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("s", $username);
                    $stmt->execute();

                    // Insert the new image into the database
                    $stmt = $mysqli->prepare("INSERT INTO homies (user, imager) VALUES (?, ?)");
                    $stmt->bind_param("ss", $username, $image_base64);
                    $stmt->execute();
//                    $stmt->error();
//                    $mysqli->error;

                }
            }
        }

        // Retrieve the user's profile picture from the database
        $sql = "SELECT imager FROM homies WHERE user = '$username'";
        $result = mysqli_query($mysqli, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $image_dat = $row['imager'];
            $image_data = base64_decode($image_dat);

            // Display the image using a data URL
            $data_url = 'data:image/jpeg;base64,' . base64_encode($image_data);
            echo '<a href="#" id="pfpLink"><img src="' . $data_url . '" class="pfp" alt="Profile Picture"></a>';
        } else {
            echo '<a href="#" id="pfpLink"><img src="profile.png" class="pfp" alt="Profile Picture"></a>';
        }

        // Close the database connection
        mysqli_close($mysqli);
        ?>

        <form action="" class='pfp' method="POST" enctype="multipart/form-data" name="aForm" id="aForm" style="display:none">
            <label for="image"></label>
            <input type="file" id="image" name="image" style="align-content: center" accept="image/jpeg, image/jpg, image/png">
            <br><br>
            <input type="submit" value="Upload" id="submitButton" accept="image/jpeg, image/jpg, image/png" style="display:none;">
        </form>
        <script>
            const form = document.getElementById('aForm');
            const image = document.getElementById('image');
            const pfpLink = document.getElementById('pfpLink');
            pfpLink.addEventListener('click', function() {
                image.click();
            });
            image.addEventListener('change', function() {
                form.submit();
                form.style.display = 'none';
            });
        </script>
    </div>


<!--        <form action="" class='pfp' method="POST" enctype="multipart/form-data" name="aForm" id="aForm">-->
<!--            <label for="image"></label>-->
<!--            <input type="file" id="image" name="image" style="align-content: center" accept="image/jpeg, image/jpg, image/png">-->
<!--            <br><br>-->
<!--            <input type="submit" value="Upload" id="submitButton" accept="image/jpeg, image/jpg, image/png" style="display:none;">-->
<!--        </form>-->
<!--        <script>-->
<!--            const form = document.getElementById('aForm');-->
<!--            const image = document.getElementById('image');-->
<!--            image.addEventListener('change', function() {-->
<!--                form.submit();-->
<!--                form.style.display = 'none';-->
<!--            });-->
<!--        </script>-->
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