<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemName = $_POST["itemName"];
    $username = "hGilmore909";
    $tag = $_POST["tag"];
    $quantity = $_POST["quantity"];

}

$host = "oceanus.cse.buffalo.edu";
$user = "venkatay";
$password = "50337119";
$database = "cse442_2023_spring_team_ae_db";

$mysqli = mysqli_connect($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$itemName = mysqli_real_escape_string($mysqli,$_POST["itemName"]);
$itemName = htmlspecialchars($itemName);
$quantity = mysqli_real_escape_string($mysqli,$_POST["quantity"]);
$quantity= htmlspecialchars($quantity);

if($quantity > 0){
    $sql = "INSERT INTO sharedInventory VALUES ('$itemName', '$quantity', '$tag', '$username')";
}

mysqli_query($mysqli, $sql);

$mysqli->close();

header('Location: shared-inventory.php');
exit();
