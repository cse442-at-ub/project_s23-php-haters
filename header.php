<?php
//include 'util.php';

session_start();
$user_id = $_SESSION["username"];
if (!$user_id) { ?>
    <h2 style="font-family: 'monospace';">YOU MUST SIGN IN FIRST!!!
        <?php header('Location: login.php'); ?></h2>
<?php }
?>
<?php
$host = "oceanus.cse.buffalo.edu";              // The hostname of the database server
$user = "bensonca";                             // The MySQL user
$password = "50355548";                         // The MySQL user's password
$database = "cse442_2023_spring_team_ae_db";    // The name of the database to connect to


$username = $_SESSION["username"];
$mysqli = mysqli_connect($host, $user, $password, $database);
$sql = "SELECT imager FROM homies WHERE user = '$username'";
$result = mysqli_query($mysqli, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $image_dat = $row['imager'];
    $image_data = base64_decode($image_dat);

    // Display the image using a data URL
    $data_url = 'data:image/jpeg;base64,' . base64_encode($image_data);
    $image_src = $data_url;
} else {
    $image_src = "profile.png";
}
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" type="text/css" href="home.css">

<body>
<header>
    <div>
        <img class='icon' src="Saturn.png" alt="RoomAid">
        <span class="h3"> RoomAid </span>
        <nav>
            <ul>
                <li><a href="home.php" class="nav-button">Home</a></li>
                <li><a href="task-schedule.php" class="nav-button">Schedule</a></li>
                <li><a href="group.php" class="nav-button">Group</a></li>
                <li><a href="inventory.php" class="nav-button">Inventory</a></li>
                <li><a href="expensesV4.php" class="nav-button">Expenses</a></li>
                <li><a href="ppage.php"><img class='nav-icon' id='icon-pfp' src="<?php echo $image_src ?>" alt="Profile"></a></li>
            </ul>
        </nav>
    </div>
</header>