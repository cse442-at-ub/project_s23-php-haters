<?php
ini_set('display_errors', 1);
?>
<html>
<head>
    <title>Roomaid Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<div class="wrapper-main">
    <section class="section-default">
        <?php
        if (isset($_GET["selector"]) && isset($_GET["validator"])) {
            $selector = $_GET["selector"];
            $validator = $_GET["validator"];

            echo $selector = $_GET["selector"];
            echo $validator = $_GET["validator"];

            if(ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false){
                ?>
        <form action="reset-password.inc.php" method="post">
            <input type="hidden" name="selector" value="<?php echo $selector ?>">
            <input type="hidden" name="validator" value="<?php echo $validator ?>">
            <h2 style="margin-left: 42%;margin-top: 15%">Reset Password</h2>
            <input style="margin-left: 30%" type="password" name="pwd" placeholder="Enter a new password" required>
            <br>
            <input style="margin-left: 30%" type="password" name="pwd-repeat" placeholder="Repeat the new password" required>
            <br>
            <button  style="margin-left: 30%" type="submit" name="reset-password-submit" id="reset-password-submit">Reset Password</button>
        </form>
        <?php
} } else {
    echo "Could not validate your request!";
} ?>
    </section>
</div>
</html>
