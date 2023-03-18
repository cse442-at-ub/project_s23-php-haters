<?php
session_start();

$host = "oceanus.cse.buffalo.edu";
$user = "riadmukh";
$password = "50356618";
$database = "cse442_2023_spring_team_ae_db";
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function nameValidator($name): bool
{
    if(!preg_match("/^[a-zA-Z]+$/", $name)){ // check if name contains only letters
        return true; // return true if name is invalid
    }
    else{
        return false; // return false if name is valid
    }
}
function usernameValidator($username): bool
{
    if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){ // check if username contains only letters and digits
        return true; // return true if username is invalid
    }
    if(strlen($username) < 6){return true;}
    else{return false;}
}
function emailMatch($email, $confirmEmail): bool{
    if($email !== $confirmEmail){ // check if email and confirm email inputs match
        return true; // return true if they don't match
    }
    else{
        return false; // return false if they match
    }
}
function usernameInDatabase($conn, $username, $email){

    $username = escape_sql($username); // escape special characters in username
    $email = escape_sql($email); // escape special characters in email

    $sql = "SELECT * FROM users WHERE usersUsername = '$username' OR usersEmail = '$email';"; // query the database to check if username or email already exists
    $result = mysqli_query($conn, $sql); // execute the query

    if($ret = mysqli_fetch_assoc($result)){ // check if the query result is not empty
        return $ret; // return the user's data as an associative array
    }
    else{
        return false; // return false if the user is not found in the database
    }
}
function escape_sql($str): string
{
    global $conn;
    // Escape special characters in a string for use in an SQL statement
    return mysqli_real_escape_string($conn, $str);
}
function check_password_strength($password): bool{
    if (preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\D)(?=.*\d)(?=.*[^A-Za-z0-9\s]).+$/', $password) && strlen($password) >= 8 && strlen($password) <= 20) {
        return false;
    }
    return true;
}

if(isset($_POST["fname"])){
    $name = mysqli_real_escape_string($conn,$_POST["fname"]);
    $email = mysqli_real_escape_string($conn,$_POST["email"]);
    $confirmEmail = mysqli_real_escape_string($conn,$_POST["email2"]);
    $username = mysqli_real_escape_string($conn,$_POST["username"]);
    $ps = mysqli_real_escape_string($conn,$_POST["password"]);

    if(nameValidator($name) !== false){
        header("location: register.php?error=badName");
        exit();
    }
    if(usernameValidator($username) !== false){
        header("location: register.php?error=badUsername");
        exit();
    }
    if(emailMatch($email, $confirmEmail) !== false){
        header("location: register.php?error=emailDidNotMatch");
        exit();
    }
    if(usernameInDatabase($conn, $username, $email)){
        header("location: register.php?error=usernameInUse");
        exit();
    }
    if(check_password_strength($ps)){
        header("location: register.php?error=weakPassword");
        exit();
    }

    $ps = password_hash($ps, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (usersName, usersEmail, usersUsername, usersPassword) VALUES ('$name', '$email', '$username', '$ps')";

    if (mysqli_query($conn,$sql)){
        echo "New record created successfully";
        header("Location: login.php");
    }
    else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);}
    }
    mysqli_close($conn);
?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <link rel="stylesheet" href="style.css">
    </head>

    <title>Roomaid Register</title>

    <body>
        <div class="registerElement">
            <form method="post" action="register.php">
                <input type="text" id="fname" name="fname" placeholder="Enter Name" required>
                <br>
                <input type="email" id="email" name="email" placeholder="Enter Email" required>
                <br>
                <input type="email" id="email2" name="email2" placeholder="Confirm Email" required>
                <br>
                <input type="text" id="username" name="username" placeholder="Enter Username" required>
                <br>
                <input type="password" id="password" name="password" placeholder="Enter Password" required>
                <br>
                <input type="submit" value="REGISTER"> <!--Need to redirect to homepage-->
                <div class="olduser">
                    <a href="login.php">Already Have An Account?</a>
                </div>
            </form>
        </div>
    </body>
</html>
