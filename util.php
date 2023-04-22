<?php

$host = "oceanus.cse.buffalo.edu";              // The hostname of the database server
$user = "bensonca";                             // The MySQL user
$password = "50355548";                         // The MySQL user's password
$database = "cse442_2023_spring_team_ae_db";    // The name of the database to connect to

$mysqli = mysqli_connect($host, $user, $password, $database);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

function connect(){
    $host = "oceanus.cse.buffalo.edu";              // The hostname of the database server
    $user = "bensonca";                             // The MySQL user
    $password = "50355548";                         // The MySQL user's password
    $database = "cse442_2023_spring_team_ae_db";    // The name of the database to connect to

    return mysqli_connect($host, $user, $password, $database);
}

function getGroupName($username, $mysqli){  # gets the group name given the username and a connection (use connect())
    $name = $username;
    $stmt = $mysqli->prepare("SELECT groupName FROM `groupTestV2` WHERE username = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();               // Find the group the user is in
    $result = $stmt->get_result();
    $stmt->close();
    $row = $result->fetch_assoc();
    return $row["groupName"];
}
//$email = getEmail($name, $mysqli);

function getTasks($groupName, $mysqli){  # gets the tasks given the group's name and a connection (use connect())
    $stmt = $mysqli->prepare("SELECT * FROM tasks WHERE groupName = ?");
    $stmt->bind_param("s", $groupName);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $mysqli->close();

    return $result;
}

function getGroupMembers($groupName, $mysqli){ # given the group name, get the group members in form of a dropdown
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


function getEmail($member, $mysqli){ # get the email of a user given their username a connection (use connect())
    $stmt = $mysqli->prepare("SELECT usersEmail FROM users WHERE usersUsername = ?");
    $stmt->bind_param("s", $member);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $row = $result->fetch_assoc();
    return $row["usersEmail"];
}



function removeOverdue(){  # runs when page is loaded and gets rid of overdue tasks
    $mysqli = connect();
    $current_datetime = date('Y-m-d H:i:s');
    $stmt = $mysqli->prepare("DELETE FROM tasks WHERE due_date <  ? ");
    $stmt-> bind_param("d", $current_datetime);
    $stmt-> execute();
}
//function uploadedImage($user_id)
//{
//    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
//            // get the file details
//            $file_name = $_FILES['image']['name'];
//            $file_size = $_FILES['image']['size'];
//            $file_tmp = $_FILES['image']['tmp_name'];
//            $file_type = $_FILES['image']['type'];
////            $user_id = $user;
//            // Delete all files in the directory
//            $target_dir = 'uploads/' . $user_id . '/';
//            $files = glob($target_dir . '*');
//            foreach ($files as $file) {
//                if (is_file($file)) { // Check if it's a file and not a directory
//                    unlink($file); // Delete the file
//                }
//            }
//            if (!file_exists($target_dir)) {
//                mkdir($target_dir, 0777, true); // create the directory if it doesn't exist
//            }
//
//            $target_file = $target_dir . $user_id . '_' . basename($_FILES["image"]["name"]); // get the full path of the uploaded file with the username preceeding the image name
//            $target_file = $target_dir . basename($_FILES["image"]["name"]); // get the full path of the uploaded file
//            move_uploaded_file($file_tmp, $target_file);
//            echo "<img src='$target_file' class='profile-image' alt='Uploaded image' class='profile-image'>";
//
//        }
////        else {
//////            echo "Image file or size invalid. Select a different or smaller image";
////        }
//    }
//}
