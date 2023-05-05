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

function getGroupName($username, $mysqli){
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

function getTasks($groupName, $mysqli){
    $stmt = $mysqli->prepare("SELECT * FROM tasks WHERE groupName = ?");
    $stmt->bind_param("s", $groupName);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $mysqli->close();

    return $result;
}

function getGroupMembers($groupName, $mysqli){
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


function getEmail($member, $mysqli){
    $stmt = $mysqli->prepare("SELECT usersEmail FROM users WHERE usersUsername = ?");
    $stmt->bind_param("s", $member);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $row = $result->fetch_assoc();
    return $row["usersEmail"];
}



function removeOverdue(){
    $mysqli = connect();
    $current_datetime = date('Y-m-d H:i:s');
    $stmt = $mysqli->prepare("DELETE FROM tasks WHERE due_date <  ? ");
    $stmt-> bind_param("d", $current_datetime);
    $stmt-> execute();
}

function validImage($filetype){
    $allowed_extensions = array('png', 'jpeg', 'jpg');
    if (!in_array($filetype, $allowed_extensions)) {
        return false;
    } else {
        return true;
    }
}

function getNotification($conn, $userid, $group_name){
    $sql = "SELECT * FROM groupTestV2 WHERE groupName =?";
    $prep = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($prep, $sql)) {
        $_SESSION['error'] = 'Sorry, there was an error. Please try again.';
        echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "find_group.php";</script>';
        exit();
    } else {
        // Bind parameters to statement
        mysqli_stmt_bind_param($prep, "s", $group_name);
        // Execute statement
        mysqli_stmt_execute($prep);
        // Get result set from statement
        $result = mysqli_stmt_get_result($prep);

        $to_array = array(); // array to store email addresses

        while($row = mysqli_fetch_assoc($result)) {
            $sql = "SELECT usersEmail FROM users WHERE usersUsername = ?";
            $prep_val = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($prep_val, $sql)) {
                $_SESSION['error'] = 'Sorry, there was an error. Please try again.';
                echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "find_group.php";</script>';
                exit();
            }
            else {
                $username = $row["username"];
                // Bind parameters to statement
                mysqli_stmt_bind_param($prep_val, "s", $username);
                // Execute statement
                mysqli_stmt_execute($prep_val);
                // Get result set from statement
                $result_val = mysqli_stmt_get_result($prep_val);
                $result_email = mysqli_fetch_assoc($result_val);
                $email = $result_email["usersEmail"];
                $to_array[] = $email; // add email address to array
            }
        }

        // send email to all recipients
        $to = implode(",", $to_array); // convert array to comma-separated string
        $subject = "New User joined ".$group_name;
        $message = '<p>'.$userid.' joined the group</p>';
        $headers = "Content-type: text/html\r\n";
        mail($to, $subject, $message, $headers);
    }
}
