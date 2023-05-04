<?php
    session_start();

    $host = "oceanus.cse.buffalo.edu";
    $user = "arpithir";
    $pass = "50340819";
    $database = "cse442_2023_spring_team_ae_db";

    //make sure we found oceanus
    $conn = mysqli_connect($host, $user, $pass, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (!isset($_SESSION['username'])) {
        // Redirect to the login page
        header('Location: login.php');
        exit;
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

    if(isset($_POST['leave_group'])){ // check if post request is working is set
        $username = $_SESSION['username'];
        $groupname = $_SESSION['groupName'];
        $email = getEmail($username, $conn);
        $sql = "UPDATE groupInventory SET groupName = 'Test' WHERE groupName = '$groupname'";
        mysqli_query($conn, $sql);
        $sql1 = "DELETE FROM groupTestV2 WHERE username = '$username'";
        mysqli_query($conn, $sql1);
        $sql4 = "UPDATE groupInventory SET groupName = '$groupname' WHERE groupName = 'Test'";
        mysqli_query($conn, $sql4);
        $sql2 = "DELETE FROM tasks WHERE email='$email'";
        mysqli_query($conn, $sql2);
        $sql5 = "DELETE FROM allExpensesV2 WHERE (username = '$username' AND user2amt = '0.00' AND user3amt = '0.00' AND user4amt = '0.00')";
        mysqli_query($conn, $sql5);
        $sql6 = "UPDATE allExpensesV2 SET username = user2, user1amt = user2amt, user2 = '', user2amt = '0.00' WHERE username = '$username' AND user2amt != '0.00'";
        mysqli_query($conn, $sql6);
        $sql7 = "UPDATE allExpensesV2 SET username = user3, user1amt = user3amt, user3 = '', user3amt = '0.00' WHERE username = '$username' AND user3amt != '0.00'";
        mysqli_query($conn, $sql7);
        $sql8 = "UPDATE allExpensesV2 SET username = user4, user1amt = user4amt, user4 = '', user4amt = '0.00' WHERE username = '$username' AND user4amt != '0.00'";
        mysqli_query($conn, $sql8);
        $sql9 = "UPDATE allExpensesV2 SET user2 = '', user2amt = '0.00' WHERE user2 = '$username'";
        mysqli_query($conn, $sql9);
        $sql10 = "UPDATE allExpensesV2 SET user3 = '', user3amt = '0.00' WHERE user3 = '$username'";
        mysqli_query($conn, $sql10);
        $sql11 = "UPDATE allExpensesV2 SET user4 = '', user4amt = '0.00' WHERE user4 = '$username'";
        mysqli_query($conn, $sql11);

    //      Deletes the password for the group that has only one user.
        $sql2 = "SELECT COUNT(*) AS total FROM groupTestV2 WHERE groupName = '$groupname'";
        $num = mysqli_query($conn,$sql2);
        $num_arr = mysqli_fetch_assoc($num);
        $num_people = $num_arr['total'];

        if ($num_people < 1) {
            $sql = "DELETE FROM groupPassword WHERE groupName = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s",$groupname); // BIND
            $stmt->execute();
            $stmt->close();
        }
        header("location: find_group.php");
    }