<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
//$current_user = $_SESSION['username'];
//$groupName = $_SESSION['groupName'];

$host = "oceanus.cse.buffalo.edu";
$user = "accartwr";
$password = "50432097";
$database = "cse442_2023_spring_team_ae_db";
$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$current_user = $_SESSION['username'];
$groupName = $_SESSION['groupName'];
//I'm storing groupName in $SESSION VAR from my 'inventory.php' page
// Group.php should be storing in session vars after they join?

////FIND GROUP... or group.php should be storing in session vars after they join?
//$sql = "SELECT groupName FROM groupTest WHERE username = ?";
//$stmt = $conn->prepare($sql);
//$stmt->bind_param("s", $current_user);
//$stmt->execute();
//$stmt->store_result();
//$stmt->bind_result($groupName);
//$stmt->fetch(); // Fetching groupName

// grab info from hidden html form in my inventory.php file
$item_name = $_POST['item-name'];
$item_quantity = $_POST['item-quantity'];
$action = $_POST['action'];

// check what button they hit
if ($action == 'minus') {
    $item_quantity--;
} elseif ($action == 'plus') {
    $item_quantity++;
}

$sql_update = "UPDATE groupInventory SET quantity = ? WHERE itemName = ? AND groupName = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("iss", $item_quantity, $item_name, $groupName);
echo "CurrentUser: " . $current_user . "<br>";
echo "Item name: " . $item_name . "<br>";
echo "Item quantity: " . $item_quantity . "<br>";
echo "Group name: " . $groupName . "<br>";

$stmt_update->execute();
header("Location: inventory.php");
exit;

$stmt_update->close();
?>
