<?php
session_start();
if(isset($_POST["invite"])){
    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32);
    $url = "https://www-student.cse.buffalo.edu/CSE442-542/2023-Spring/cse-442ae/Sprint-4/Sprint4CSE442/group_invite.php?selector=" . $selector . "&validator=" . bin2hex($token);
    $expires = date("U") + 1800;

    $host = "oceanus.cse.buffalo.edu";
    $user = "arpithir";
    $pass = "50340819";
    $database = "cse442_2023_spring_team_ae_db";

    $conn = new mysqli($host, $user, $pass, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $groupName = $_SESSION['groupName'];

    $sql = "DELETE FROM userInvite WHERE inviteGroup=?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        $_SESSION['error'] = 'Sorry, there was an error.';
        echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "group.php";</script>';
        exit();
    }
    else{
        mysqli_stmt_bind_param($stmt, "s", $groupName);
        mysqli_stmt_execute($stmt);
    }

    $sql = "INSERT INTO userInvite (inviteGroup, inviteSelector, inviteToken, inviteExpires) VALUES (?,?,?,?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        $_SESSION['error'] = 'Sorry, there was an error.';
        echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "group.php";</script>';
        exit();
    }
    else{
        $hashToken = password_hash($token, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "ssss", $groupName, $selector, $hashToken, $expires);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    $_SESSION['url'] = $url;

    header("Location: group.php?link=success");
}
else {
    header("Location: group.php");
}
