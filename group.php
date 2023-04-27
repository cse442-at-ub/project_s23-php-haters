<?php
include 'header.php';

$host = "oceanus.cse.buffalo.edu";
$user = "arpithir";
$pass = "50340819";
$database = "cse442_2023_spring_team_ae_db";

//make sure we found oceanus
$conn = mysqli_connect($host, $user, $pass, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Group</title>
    <link rel="stylesheet" href="group.css">
</head>
<body>
<!--<header>-->
<!--    <div>-->
<!--        <img class='icon' src="Saturn.png" alt="RoomAid">-->
<!--        <span class="h3"> RoomAid </span>-->
<!--        <nav>-->
<!--            <ul>-->
<!--                <li><a href="home.php " class="nav-button">Home</a></li>-->
<!--                <li><a href="task-schedule.php" class="nav-button">Schedule</a></li>-->
<!--                <li><a href="group.php" class="nav-button">Group</a></li>-->
<!--                <li><a href="inventory.php" class="nav-button">Inventory</a></li>-->
<!--                <li><a href="Shared_Expenses.php" class="nav-button">Expenses</a></li>-->
<!--            </ul>-->
<!--        </nav>-->
<!--        <a href="#"><img id='icon-pfp' src="profile.png" alt="Profile"></a>-->
<!--    </div>-->
<!--</header>-->

<?php
   if(isset($_SESSION['username'])){ // check if user session variable is set
    $current_user = $_SESSION['username'];
    $sql = "SELECT groupName FROM groupTestV2 WHERE username = ?";
    //  Block pesky SQL injections with prepared statement ;)
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $current_user); // BIND
    $stmt->execute();
    $result  = $stmt->get_result();
    $stmt->close();
    $result_group = $result->fetch_assoc();
    $result_group_name = $result_group['groupName'];
    $_SESSION['groupName'] = $result_group_name;
    echo '<b><span class="group_name">'.$result_group_name.'</span></b>';
   }
?>
<div class="group_box">
    <!--The profile picture and the name will be displayed of the current user if there is just one user in the room.-->
    <!--This is just for the display purposes we will change it as soon as we are done it with the group formation backend-->
    <!-- <img src="profile.png" id="profile"> -->
<?php
if(isset($_SESSION['username'])){ // check if user session variable is set
    $current_user = $_SESSION['username'];
    $sql = "SELECT username FROM groupTestV2 WHERE groupName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $result_group_name); // BIND
    $stmt->execute();
    $result  = $stmt->get_result();
    $stmt->close();
    $top_increment = 12;
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
</div>


<form method="post" id="grp">
    <input type="submit" id="leave_group" name="leave_group" value="Leave Group">
</form>

<!--The user will be directed to again finding the roommate page as soon as he leaves the room.-->
<?php
    if(isset($_POST['leave_group'])){ // check if post request is working is set
        $sql = "DELETE FROM groupTestV2 WHERE username = ? AND groupName = ?";
        $current_user = $_SESSION['username'];
        $grpName = $_SESSION['groupName'];
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $current_user, $grpName); // BIND
        $stmt->execute();
        $stmt->close();
        header("location: find_group.php");
    }
?>

<form method="post" id="usrInvite" action="generate_invite_link.php">
    <button type="submit" id="invite" name="invite">Invite Link [Click to Copy]</button>
</form>

<script>
    function copyToClipboard(text) {
        // Copy the text to the clipboard
        navigator.clipboard.writeText(text).then(() => {
            alert("Copied to Clipboard");
        })
    }
</script>

<?php
    if (isset($_GET["link"])) {
        if ($_GET["link"] == "success") {
            echo '<script>copyToClipboard("'. $_SESSION['url'] .'")</script>';
        }
    }
?>

</body>
</html>