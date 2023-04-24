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
    $currentDate = date("U");
    if (isset($_GET["selector"]) && isset($_GET["validator"])) {
        $selector = $_GET["selector"];
        $validator = $_GET["validator"];

        $sql = "SELECT * FROM pwdReset WHERE pwdResetSelector='$selector' AND pwdResetExpires >= '$currentDate'";
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