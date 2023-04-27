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

function check_password_strength($password): bool{
    if (preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\D)(?=.*\d)(?=.*[^A-Za-z0-9\s]).+$/', $password) && strlen($password) >= 8 && strlen($password) <= 20) {
        return false;
    }
    return true;
}

if (isset($_POST['group'])) { 
    if(isset($_SESSION['username'])){ // check if user session variable is set
        $current_user = $_SESSION['username'];
        $grpName = $_POST['group'];
        $pass = $_POST["grppassword"];
        $sql = "SELECT * FROM groupTestV2 WHERE groupName = '$grpName'";
        $result = mysqli_query($conn,$sql);
        $result_val = mysqli_fetch_assoc($result);
        $group_name = $result_val['groupName'];
        if(check_password_strength($pass)){
            $_SESSION['error'] = 'Please enter a password with less than 20 characters including uppercase, lowercase, numeric and special characters.';
            echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "find_group.php";</script>';
            exit();
        }
        if (is_null($group_name)){
            $ps = password_hash($pass, PASSWORD_DEFAULT);
            $sql = "INSERT INTO groupPassword (groupName, groupPassword) VALUES ('$grpName','$ps')";
            $arr = "INSERT INTO groupTestV2 VALUES ('$current_user', '$grpName')";
            $stmt = $conn->prepare($arr);
            $stmt2 = $conn->prepare($sql);
            $stmt->bind_param("ss", $current_user, $grpName); // BIND
            $stmt2->bind_param("ss", $grpName, $ps);
            $stmt->execute();
            $stmt2->execute();
            echo "<script>window.location.href='group.php';</script>";
            $stmt->close();
            $stmt2->close();
        }
        else {
            $_SESSION['error'] = 'Sorry, the group name already exists. Pick a new group name.';
            echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "find_group.php";</script>';
            exit();
        }

    }
}


function joinGroup($conn){
    if(isset($_SESSION['username'])){ // check if user session variable is set
        $current_user = $_SESSION['username'];
        $search_grp = $_SESSION['search'];
        $pass = $_POST["grpPass"];

        $sql = "SELECT * FROM groupPassword WHERE groupName=?";
        $prep = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($prep, $sql)) {
            $_SESSION['error'] = 'Sorry, there was an error. Please try again.';
            echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "find_group.php";</script>';
            exit();
        }
        else {
            // Bind parameters to statement
            mysqli_stmt_bind_param($prep, "s", $search_grp);
            // Execute statement
            mysqli_stmt_execute($prep);
            // Get result set from statement
            $result = mysqli_stmt_get_result($prep);

            if ($row = mysqli_fetch_assoc($result)) {
                // Verify password hash
                if (password_verify($pass, $row['groupPassword'])) {
                    // Passwords match, Join the group
                    $arr = "INSERT INTO groupTestV2 VALUES ('$current_user', '$search_grp')";
                    $stmt = $conn->prepare($arr);
                    $stmt->execute();
                    echo "<script>window.location.href='group.php';</script>";
                    $stmt->close();
                } else {
                    // Passwords don't match
                    $_SESSION['error'] = 'Sorry, incorrect password. Please try again.';
                    echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "find_group.php";</script>';
                    exit();
                }
            }
        }
    }
}

if (isset($_POST['join'])) {
    joinGroup($conn);
}


if (isset($_POST['search'])) {
    addUser($conn);
}

function addUser($conn) {
    // Set the new style
    // $style = 'display: block;';
    if(isset($_SESSION['username'])){ // check if user session variable is set
        $current_user = $_SESSION['username'];
        $search_val = $_POST['search'];
        $_SESSION['search'] = $search_val;
        $sql = "SELECT * FROM groupTestV2 WHERE groupName = '$search_val'";
        $result = mysqli_query($conn,$sql);
        $result_val = mysqli_fetch_assoc($result);
        $group_name = $result_val['groupName'];
        if (!is_null($group_name)){
            $sql2 = "SELECT COUNT(*) AS total FROM groupTestV2 WHERE groupName = '$search_val'";
            $num = mysqli_query($conn,$sql2);
            $num_arr = mysqli_fetch_assoc($num);
            $num_people = $num_arr['total'];
            if ($num_people >= 4){
                $_SESSION['error'] = 'Sorry, the group is currently full. Create a new group or Join a different group.';
                echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "find_group.php";</script>';
                exit();
            }
            if ($num_people < 4) {?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
                <title>Find Group</title>
                <link rel="stylesheet" href="find_group.css">

            <!--The Script for opening and closing of create group form-->
                <script>
                    function closeGroupInfo() {
                        window.location.href='find_group.php';
                    }
                </script>
            </head>
            <body>
                <div class="group_info_box" id="group_info_box">
                    <form action="find_group_be.php" method="post" name="group_info_form" class="group_info_form">
                        <h2 style="text-align: center; font-family: 'Inter', sans-serif; font-style: normal; font-weight: 400; font-size: 2.5vw;"><?php echo $_POST["search"]; ?></h2>

                        <?php
                            if(isset($current_user)){ // check if user session variable is set
                                $sql = "SELECT username FROM groupTestV2 WHERE groupName = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("s", $search_val); // BIND
                                $stmt->execute();
                                $result  = $stmt->get_result();
                                $stmt->close();
                                $top_increment = 14;
                                $i = 1;
                                while ($row = $result->fetch_assoc()) {
                                    $userName = $row['username'];
                                    $top = $top_increment * $i;
                                    $user_name_id = "name".$i;
                                    echo '<span class="name" id='.$user_name_id.' style="top:'. $top .'%;">'.$userName.'</span>';
                                    $i++;
                                }
                            }
                        ?>
                        <input type="password" id="grpPass" name="grpPass" placeholder="Enter Password" required>
                        <button type="submit" class="join" name="join">Join</button>
                        <button type="button" class="cancel" onclick="closeGroupInfo()">Cancel</button>
                    </form>
                </div>
            </body>
            </html>
            <?php
            }  
        }
        else {
            $_SESSION['error'] = 'Sorry, there is no group with that name.';
            echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "find_group.php";</script>';
        }
    }
}
?>
