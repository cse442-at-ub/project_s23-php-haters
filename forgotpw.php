<!DOCTYPE html>

<html lang="en">
<head>
    <title>Roomaid Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="forgotElement">
    <h2>Forgot Password?</h2>
    <h3>No worries, we will send you reset instructions!</h3>
    <form method="post" action="reset-request.php">
        <label>
            <input type="email" id="email" name="email" placeholder="Enter your e-mail address" required>
        </label>
        <br>
        <input type="submit" name ="reset-request-submit" value="Reset Password">
        <div class="newuser">
            <a href="login.php" style="margin-left:3%">Back To Login</a>
        </div>
    </form>
    <?php
    if (isset($_GET["reset"])) {
        if ($_GET["reset"] == "success") {
            echo '<p class="signupsucess">Check your e-mail</p>';
        }
    }
    ?>
</div>
</body>
</html>
