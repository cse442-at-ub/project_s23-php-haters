<?php
session_start();
$host = "oceanus.cse.buffalo.edu";
$user = "accartwr";
$password = "50432097";
$database = "cse442_2023_spring_team_ae_db";
$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//    Find group
$current_user = $_SESSION['username'];
$sql = "SELECT groupName FROM groupTestV2 WHERE username = ?";
//  Block pesky SQL injections with prepared statement ;)
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $current_user); // BIND
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($groupName); // BIND
$result_group  = $stmt->fetch(); // return1 if in group, 0 if not

$_SESSION['groupName'] = $groupName; //STORING GROUPNAME 2 USE W/ UPDATE_QUANTITY PAGE!

?>
<!DOCTYPE html>
<html>
<head>
    <title>Inventory Items</title>
    <link rel="stylesheet" href="inventory.css">
</head>

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
                <li><a href="#"><img id='icon-pfp' src="profile.png" alt="Profile"></a></li>
            </ul>
        </nav>
    </div>
</header>

<!--  Buttons above the scroll box   -->
<div>
    <button id="add-item-btn" class="btn" style="background-color: #EEE3A4; color: #000000;" onclick="showAddItemBox()">Add Item</button>
</div>

<!-- Add item pop up box -->
<div id="add-item-modal" style="display: none;">
    <form id="add-item-form" method="post" action="update_InvQuantity.php">
        <label for="item-name">Name:</label>
        <input type="text" id="item-name" name="item-name" required>
        <br>
        <label for="item-quantity">Quantity:</label>
        <input type="number" id="item-quantity" name="item-quantity" required min="0">
        <br>
        <label for="item-tag">Tag:</label>
        <input type="text" id="item-tag" name="item-tag" required>
        <br><br>
        <input type="hidden" name="action" value="add">
        <button class="btn" type="submit" onclick="submitAddItemForm(event)">Add</button>
        <button class="btn" type="button" onclick="hideAddItemBox()">Close</button>
    </form>
</div>

<div class="title">
    <?php if (!$result_group) { ?>
        <h2 style="font-family: cursive;">Hello <?php echo $_SESSION['username'] ;?>, You must join a group to view inventory!</h2>
    <?php } else { ?>
        <h2 style="font-family: cursive;">Hello <?php echo $_SESSION['username'] ;?>, Your viewing <?php echo $groupName;?>'s inventory items:</h2>
    <?php } ?>
</div>

<!--SCROLL BOX  -->
<div class="scroll-box">
    <?php
    if (!$result_group) {
        echo "$current_user must join a group to view inventory!";
    } else {
        $sql_inventory = "SELECT itemName as name, quantity, tag1 as tag FROM groupInventory WHERE groupName = ?";
        $stmt_inventory = $conn->prepare($sql_inventory);
        $stmt_inventory->bind_param("s", $groupName);
        $stmt_inventory->execute();
        $result_inventory = $stmt_inventory->get_result();
        $inventory = $result_inventory->fetch_all(MYSQLI_ASSOC);

        // Display inventory items
        foreach ($inventory as $item) {
            echo "<div class='item' style='background-color: ";
            $quantity = $item['quantity'];
            if ($quantity == 0) {
                echo "#e83939";     // RED - EMPTY
            } elseif ($quantity == 1 || $quantity == 2) {
                echo "#f69661"; // ORANGE- WARNING
            } else {
                echo "#F3ECC1"; // YELLOW- NORMAL
            }
            echo ";'>";
            echo "<div class='item-content' style='display: flex; align-items: center;'>";
            echo "<span>" . $item['name'] . "</span>";

            echo "<form action='update_InvQuantity.php' method='post' style='display: flex; align-items: center;'>";
            echo "<input type='hidden' name='item-name' value='" . $item['name'] . "'>";
            echo "<input type='hidden' name='item-quantity' value='" . $quantity . "'>";
            echo "<div class='quantity-btn'>";
            echo "<button class='btn minus-btn' type='submit' name='action' value='minus'>-</button>";
            echo "<span class='quantity'>" . $quantity . "</span>";
            echo "<button class='btn plus-btn' type='submit' name='action' value='plus'>+</button>";
            echo "</div>";
            echo  $item['tag'] ;
            // Add delete button next to the Tag field
            echo " <button class='btn delete-btn' type='submit' name='action' value='delete'>Delete</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
        }

    }
    $stmt->close();
    $stmt_inventory->close();
    ?>
</div>
<script>
    const modal = document.getElementById("add-item-modal");
    const form = modal.querySelector("form");

    function showAddItemBox() {
        const modal = document.getElementById("add-item-modal");
        modal.style.display = "block";
    }

    function hideAddItemBox() {
        const modal = document.getElementById("add-item-modal");
        modal.style.display = "none";
    }

    // need to send form data to updateInvQuantity file!!
    function submitAddItemForm(e) {
        e.preventDefault();
        const form = document.getElementById("add-item-form");
        form.submit();
    }

</script>
</body>
