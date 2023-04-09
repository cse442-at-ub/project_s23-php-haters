<?php
session_start();

if(isset($_POST["reset-password-submit"])){
    $selector = $_POST["selector"];
    $validator = $_POST["validator"];
    $password = $_POST["pwd"];
    $passwordRepeat = $_POST["pwd-repeat"];
    if(empty($password) || empty($passwordRepeat)){
        header("Location: create-new-password.php?newpwd=empty");
        exit();
    }
    else if($password != $passwordRepeat){
        header("Location: create-new-password.php?newpwd=pwdnotsame");
        exit();
    }

    $currentDate = date("U");

    $host = "oceanus.cse.buffalo.edu";
    $dbUser = "riadmukh";
    $dbPassword = "50356618";
    $database = "cse442_2023_spring_team_ae_db";

    $conn = new mysqli($host, $dbUser, $dbPassword, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM pwdReset WHERE pwdResetSelector=? AND pwdResetExpires >= ?";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: login.php");
        exit();
    }
    else{
        mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        if(!$row = mysqli_fetch_assoc($result)){
            echo "You need to resubmit your reset request.";
            exit();
        }
        else{
            $tokenBinary = hex2bin($validator);
            $tokenCheck = password_verify($tokenBinary,$row["pwdResetToken"]);

            if(!$tokenCheck){
                echo "You need to resubmit your reset request.";
                exit();
            }
            else if($tokenCheck === true){
                $tokenEmail = $row['pwdResetToken'];

                $sql = "SELECT * FROM users WHERE usersEmail = ?;";

                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    header("Location: login.php");
                    exit();
                }
                else{
                    mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if($row = mysqli_fetch_assoc($result)){
                        echo "There was an error!";
                        exit();
                    }
                    else{
                        $sql = "UPDATE users SET usersPassword = ? WHERE usersEmail = ?";
                        $stmt = mysqli_stmt_init($conn);
                        if(!mysqli_stmt_prepare($stmt, $sql)){
                            header("There was an error");
                            exit();
                        }
                        else {
                            $newPwdHash = password_hash($password, PASSWORD_DEFAULT);
                            mysqli_stmt_bind_param($stmt, "ss", $newPwdHash, $tokenEmail);
                            mysqli_stmt_execute($stmt);

                            $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?";
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                                echo "There was an error!";
                                exit();
                            }
                            else{
                                mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                                mysqli_stmt_execute($stmt);
                                header("Location: login.php?newpwd=passwordupdated");
                            }
                        }
                    }
                }
            }
        }
    }
}
else{
    header("Location: login.php");
}
