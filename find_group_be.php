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

if (isset($_POST['group'])) { 
    if(isset($_SESSION['username'])){ // check if user session variable is set
        $current_user = $_SESSION['username'];
        $grpName = $_POST['group'];
        $sql = "SELECT * FROM groupTestV2 WHERE groupName = '$grpName'";
        $result = mysqli_query($conn,$sql);
        $result_val = mysqli_fetch_assoc($result);
        $group_name = $result_val['groupName'];
        if (is_null($group_name)){
            $arr = "INSERT INTO groupTestV2 VALUES ('$current_user', '$grpName')";
            //  Block pesky SQL injections with prepared statement ;)
            $stmt = $conn->prepare($arr);
            $stmt->bind_param("ss", $current_user, $grpName); // BIND
            $stmt->execute();
            echo "<script>window.location.href='group.php';</script>";
            $stmt->close();
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
        echo $search_grp;
        $arr = "INSERT INTO groupTestV2 VALUES ('$current_user', '$search_grp')";
        $stmt = $conn->prepare($arr);
        $stmt->execute();
        echo "<script>window.location.href='group.php';</script>";
        $stmt->close();
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
                        <h2 id="Name"><?php echo $_POST["search"]; ?></h2>

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
