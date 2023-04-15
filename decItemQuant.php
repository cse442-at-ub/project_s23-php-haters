<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemName = $_POST["itemName"];
    $username = "hGilmore909";
    $quantity = $_POST["quantity"];
    $checkitem = $_POST["itemname"];
    $checkquant = $_POST["itemquant"];
}

$host = "oceanus.cse.buffalo.edu";
$user = "venkatay";
$password = "50337119";
$database = "cse442_2023_spring_team_ae_db";

$mysqli = mysqli_connect($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($checkquant > 0) {
    $sql = "UPDATE sharedInventory SET quantity = quantity - 1 WHERE itemName = '$checkitem'";
} else {
    $sql = "UPDATE sharedInventory SET quantity = 0 WHERE itemName = '$checkitem'";
}

mysqli_query($mysqli, $sql);

$mysqli->close();

header('Location: shared-inventory.php');
exit();