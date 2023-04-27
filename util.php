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

function GetProfileImage($username){
    $image_path = "uploads/$username/";
    $files = glob($image_path . '*.{jpg,jpeg,png,gif}', GLOB_BRACE); // check if an image is already uploaded
        if (count($files) > 0 && !isset($_FILES["image"])) { // only display the image if a new photo has not been uploaded
            $image_filename = basename($files[0]);
            return $image_path.$image_filename;
        }
        return false;
}