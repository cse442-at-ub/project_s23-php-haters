<?php
session_start();
$user_id = $_SESSION["username"];
if (!$user_id) { ?>
    <h2 style="font-family: 'monospace';">YOU MUST SIGN IN FIRST!!!
        <?php header('Location: login.php'); ?></h2>
<?php }
?>
<?php
$image_path = "uploads/$user_id/";
// display other parts of your application with the uploaded image
$files = glob($image_path . '*.{jpg,jpeg,png,gif}', GLOB_BRACE); // check if an image is already uploaded
if (count($files) > 0 && !isset($_FILES["image"])) { // only display the image if a new photo has not been uploaded
    $image_filename = basename($files[0]);
    $image_src = $image_path.$image_filename;
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
                <li><a href="Shared_Expenses.php" class="nav-button">Expenses</a></li>
                <li><a href="ppage.php"><img class='nav-icon' id='icon-pfp' src="<?php echo $image_src ?>" alt="Profile"></a></li>
            </ul>
        </nav>
    </div>
</header>