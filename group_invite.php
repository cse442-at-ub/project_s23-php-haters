<?php
ini_set('display_errors', 1);
?>
<html>
<head>
    <title>Group Invite</title>
    <link rel="stylesheet" href="group.css">
</head>
<div class="invite-form">
    <?php
    $host = "oceanus.cse.buffalo.edu";
    $user = "arpithir";
    $pass = "50340819";
    $database = "cse442_2023_spring_team_ae_db";
    
    //make sure we found oceanus
    $conn = mysqli_connect($host, $user, $pass, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    $currentDate = date("U");
    if (isset($_GET["selector"]) && isset($_GET["validator"])) {
        $selector = $_GET["selector"];
        $validator = $_GET["validator"];

        $sql = "SELECT * FROM userInvite WHERE inviteSelector='$selector' AND inviteExpires >= '$currentDate'";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $inviteGroup = $row['inviteGroup'];
        if(ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false){
            ?>
            <form action="group_invite_process.php" method="post">
                <input type="hidden" name="selector" value="<?php echo $selector ?>">
                <input type="hidden" name="validator" value="<?php echo $validator ?>">
                <h2 id="invite"><?php echo $inviteGroup; ?>'s Invite</h2>
                <input type="text" name="usrName" id="username" placeholder="Enter your Username" required>
                <button type="submit" name="invite-submit" id="invite_submit">Submit</button>
            </form>
            <?php
        } } else {
        $_SESSION['error'] = 'Sorry, there was an error.';
        echo '<script>alert("' . $_SESSION['error'] . '"); window.location.href = "login.php";</script>';
        exit();
    } ?>
</div>
</html>