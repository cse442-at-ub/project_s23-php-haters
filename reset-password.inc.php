<?php
session_start();

if(isset($_POST["reset-password-submit"])){
    $selector = $_POST["selector"];
    $validator = $_POST["validator"];
//    echo $selector = $_POST["selector"];
//    echo $validator = $_POST["validator"];
    $password = $_POST["pwd"];
    $passwordRepeat = $_POST["pwd-repeat"];
    function passwordMatch($password, $passwordRepeat): bool{
        if($password !== $passwordRepeat){ // check if email and confirm email inputs match
            return true; // return true if they don't match
        }
        else{
            return false; // return false if they match
        }
    }

    function check_password_strength($password): bool{
        if (preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\D)(?=.*\d)(?=.*[^A-Za-z0-9\s]).+$/', $password) && strlen($password) >= 8 && strlen($password) <= 20) {
            return false;
        }
        return true;
    }
    if(passwordMatch($password, $passwordRepeat) !== false){
        $_SESSION['error'] = 'Sorry, the passwords did not match.';
        echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "create-new-password.php";</script>';
        exit();
    }
    if(check_password_strength($password)){
        $_SESSION['error'] = 'Please enter a password with less than 20 characters including uppercase, lowercase, numeric and special characters.';
        echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "create-new-password.php";</script>';
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

    $sql = "SELECT * FROM pwdReset WHERE pwdResetSelector='$selector' AND pwdResetExpires >= '$currentDate'";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        $_SESSION['error'] = 'Sorry, there was an error. Please try again.';
        echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "create-new-password.php";</script>';
        exit();
    }
    else{
        mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        echo $selector, $validator;
        if(!$row = mysqli_fetch_assoc($result)){
            $_SESSION['error'] = 'Sorry, there was an error. Please try again.';
            echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "create-new-password.php";</script>';
            exit();
        }
        else{
            $tokenBinary = hex2bin($validator);
            $tokenCheck = password_verify($tokenBinary,$row["pwdResetToken"]);

            if(!$tokenCheck){
                $_SESSION['error'] = 'Sorry, there was an error. You need to resubmit your reset request.';
                echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "create-new-password.php";</script>';
                exit();
            }
            else{
                $tokenEmail = $row['pwdResetEmail'];


                $sql = "SELECT * FROM users WHERE usersEmail = '$tokenEmail';";

                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    $_SESSION['error'] = 'Sorry, there was an error. Please try again.';
                    echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "create-new-password.php";</script>';
                    exit();
                }
                else{
                    mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if(!$row = mysqli_fetch_assoc($result)){
                        $_SESSION['error'] = 'Sorry, there was an error. Please try again.';
                        echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "create-new-password.php";</script>';
                        exit();
                    }
                    else{
                        echo $tokenEmail, $password;

                        $sql = "UPDATE users SET usersPassword = ? WHERE usersEmail = ?";
                        $stmt = mysqli_stmt_init($conn);
                        if(!mysqli_stmt_prepare($stmt, $sql)){
                            $_SESSION['error'] = 'Sorry, there was an error. Please try again.';
                            echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "create-new-password.php";</script>';
                            exit();
                        }
                        else {
                            $newPwdHash = password_hash($password, PASSWORD_DEFAULT);
                            mysqli_stmt_bind_param($stmt, "ss", $newPwdHash, $tokenEmail);
                            mysqli_stmt_execute($stmt);


                            $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                                $_SESSION['error'] = 'Sorry, there was an error. Please try again.';
                                echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "create-new-password.php";</script>';
                                exit();
                            }
                            else {
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
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    header('Location: login.php?reset=success');
}

else{header("Location: login.php");}
