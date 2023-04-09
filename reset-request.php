<?php
session_start();
if(isset($_POST["reset-request-submit"])){
    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32);
    //$url = "http://localhost:63342/CSE442Sprint2/create-new-password?selector=" . $selector . "&validator=" . bin2hex($token);
    $url = "https://www-student.cse.buffalo.edu/CSE442-542/2023-Spring/cse-442ae/CSE442-PHP-HATERS/create-new-password.php?selector=" . $selector . "&validator=" . bin2hex($token);
    $expires = date("U") + 1800;

    $host = "oceanus.cse.buffalo.edu";
    $dbUser = "riadmukh";
    $dbPassword = "50356618";
    $database = "cse442_2023_spring_team_ae_db";

    $conn = new mysqli($host, $dbUser, $dbPassword, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $userEmail = $_POST["email"];

    $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: login.php");
        exit();
    }
    else{
        mysqli_stmt_bind_param($stmt, "s", $userEmail);
        mysqli_stmt_execute($stmt);
    }

    $sql = "INSERT INTO pwdReset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?,?,?,?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: login.php");
        exit();
    }
    else{
        $hashToken = password_hash($token, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashToken, $expires);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);


    $to = $userEmail;
    $subject = "Reset your password for RoomAid";
    $message = '<p>We received a password reset request. The link to reset your password is below. If you did not make this request, you can ignore this email</p>';
    $message .= '<p>Here is your password reset link: </br>';
    $message .= '<a href="' . $url . '">' . $url . '</a></p>';
    $headers = "Content-type: text/html\r\n";

    mail($to, $subject, $message, $headers);
    header("Location: forgotpw.php?reset=success");
}

else{
    header("Location: login.php");
}

