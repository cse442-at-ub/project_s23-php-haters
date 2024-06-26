<?php
session_start();
$host = "oceanus.cse.buffalo.edu";
$user = "riadmukh";
$password = "50356618";
$database = "cse442_2023_spring_team_ae_db";
// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getUser($conn, $username, $password){

    // SQL query to select user with given username
    $sql = "SELECT * FROM users WHERE usersUsername=?";
    $prep = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($prep, $sql)) {return false;}
    else {
        // Bind parameters to statement
        mysqli_stmt_bind_param($prep, "s", $username);
        // Execute statement
        mysqli_stmt_execute($prep);
        // Get result set from statement
        $result = mysqli_stmt_get_result($prep);

        if ($row = mysqli_fetch_assoc($result)) {
            // Verify password hash
            if (password_verify($password, $row['usersPassword'])) {
                // Passwords match, return user info
                return $row;
            } else {
                // Passwords don't match
                return false;
            }
        } else {
            // No user found with given username
            return false;
        }
    }
}

function checkGroup($conn){
    if(isset($_SESSION['username'])){ // check if user session variable is set
        $current_user = $_SESSION['username'];
        $sql = "SELECT groupName FROM groupTestV2 WHERE username = ?";
        //  Block pesky SQL injections with prepared statement ;)
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $current_user); // BIND
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result(); // BIND
        $result_group  = $stmt->fetch();
        if ($result_group == null){
            header("location: find_group.php");
        }
        else {
            header("location: home.php");
        }
    }
    die();
}

function checkLogin($conn){
    if(isset($_SESSION['username'])){ // check if user session variable is set
        $id = $_SESSION['username']; // get the user's username from the session variable
        $query = "SELECT * FROM users WHERE usersUsername = '$id' limit 1"; // query the database to get the user's data
        $result = mysqli_query($conn, $query); // execute the query
        checkGroup($conn);
        exit();
    }
    die();
}

if (isset($_POST["username"])){
    $username = $_POST["username"];
    $password = $_POST["password"];

    $user = getUser($conn, $username, $password);

    if(!$user){
        $_SESSION['error'] = 'Username and password did not match.';
        echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "register.php";</script>';
        exit();
    }

    $_SESSION["username"] = $username;

    checkLogin($conn);
    exit();
}
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <title>Roomaid Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<body>
<div class="loginElement">
    <form method="post" action="login.php">
        <label>
            <input type="text" id="username" name="username" placeholder="Enter Username" required>
        </label>
        <br>
        <input type="password" id="password" name="password" placeholder="Enter Password" required>
        <br>
        <div class="forgotpw">
            <a href="forgotpw.php">Forgot Password?</a> <!-- Need to redirect to forgot password page -->
        </div>
        <p>
            <input type="submit" value="LOGIN"> <!-- Need to redirect to homepage -->
        <div class="newuser">
            <a href="register.php">New Here? Sign Up!</a>
        </div>
    </form>
    <?php
    if (isset($_GET["newpwd"])) {
        if ($_GET["newpwd"] == "passwordupdated") {
            echo '<p class="signupsucess">Your password has been updated successfully!</p>';
        }
    }
    if (isset($_GET["invite"])) {
        if ($_GET["invite"] == "success") {
            echo '<script>alert("You have successfully joined the group.")</script>';
        }
    }
    ?>
</div>
</body>
</html>
