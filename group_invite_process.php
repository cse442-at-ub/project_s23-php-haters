<?php
session_start();

//Database Connection
$host = "oceanus.cse.buffalo.edu";
$user = "arpithir";
$pass = "50340819";
$database = "cse442_2023_spring_team_ae_db";

//make sure we found oceanus
$conn = mysqli_connect($host, $user, $pass, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_POST["invite-submit"])) {
    $currentDate = date("U");
    $selector = $_POST["selector"];
    $validator = $_POST["validator"];
    $user_name = $_POST["usrName"];

    $sql = "SELECT * FROM userInvite WHERE inviteSelector='$selector' AND inviteExpires >= '$currentDate'";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        $_SESSION['error'] = 'Sorry, there was an error. Please try again.';
        echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "group_invite.php";</script>';
        exit();
    }
    else {
        mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if(!$row = mysqli_fetch_assoc($result)){
            $_SESSION['error'] = 'Sorry, there was an error. Please try again.';
            echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "group_invite.php";</script>';
            exit();
        }
        else{
            $tokenBinary = hex2bin($validator);
            $tokenCheck = password_verify($tokenBinary,$row["inviteToken"]);

            if(!$tokenCheck){
                $_SESSION['error'] = 'Sorry, there was an error. Please try again.';
                echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "group_invite.php";</script>';
                exit();
            }
            else {
                $tokenGroup = $row['inviteGroup'];

                if(isset($_POST["invite-submit"])) {
                    $sql = "SELECT * FROM users WHERE userName = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $user_name); // BIND
                    $stmt->execute();
                    $result  = $stmt->get_result();
                    $stmt->close();
                    $result_user = $result->fetch_assoc();
            
                    //Checking if the user has an account or not
                    if ($result_user == null) {
                        $_SESSION['error'] = 'Sorry, you do not have a account pleaase create one.';
                        echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "register.php";</script>';
                        exit();
                    }
                    else {
                        // Checking if the user already has a group or not
                        $sql = "SELECT * FROM groupTestV2 WHERE username = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $user_name); // BIND
                        $stmt->execute();
                        $result  = $stmt->get_result();
                        $stmt->close();
                        $result_val = $result->fetch_assoc();
            
                        if ($result_val != null) {
                            $_SESSION['error'] = 'Sorry, you already have a group. Please leave that group to join a new group.';
                            echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "login.php";</script>';
                            exit();
                        }
                        else {
                            // Checking if the invited group is full or not
                            $sql2 = "SELECT COUNT(*) AS total FROM groupTestV2 WHERE groupName = '$tokenGroup'";
                            $num = mysqli_query($conn,$sql2);
                            $num_arr = mysqli_fetch_assoc($num);
                            $num_people = $num_arr['total'];
                            if ($num_people >= 4){
                                $_SESSION['error'] = 'Sorry, the group is currently full. Create a new group or Join a different group.';
                                echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "login.php";</script>';
                                exit();
                            }
                            if ($num_people < 4){
                                // Adding the user to the group if its not full.
                                $arr = "INSERT INTO groupTestV2 VALUES ('$user_name', '$tokenGroup')";
                                $stmt = $conn->prepare($arr);
                                $stmt->execute();
                                header("Location: login.php?invite=success");
                                $stmt->close();
                            }
                        }
                    }      
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
else {
    header("Location: login.php");
}