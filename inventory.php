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
                <li><a href="home2.php" class="nav-button">Home</a></li>
                <li><a href="#" class="nav-button">Schedule</a></li>
                <li><a href="#" class="nav-button">Group</a></li>
                <li><a href="inventory.php" class="nav-button">Inventory</a></li>
                <li><a href="Shared_Expenses.php" class="nav-button">Expenses</a></li>
                <li><a href="#"><img class='icon-pfp' src="Giraffe.png" alt="Profile"></a></li>
                </li>
            </ul>
        </nav>
    </div>
</header>
<!--  Buttons above the scroll box   -->
<div>
    <button id="add-item-btn" class="btn" style="background-color: #EEE3A4; color: #000000;" onclick="showAddItemBox()">Add Item</button>
</div>

<!--add item pop up box-->
<div id="add-item-modal" style="display: none;">
    <form>
        <label for="item-name">Name:</label>
        <input type="text" id="item-name" name="item-name" required>
        <br>
        <label for="item-quantity">Quantity:</label>
        <input type="number" id="item-quantity" name="item-quantity" required min="0">
        <br>
        <label for="item-tag">Tag:</label>
        <input type="text" id="item-tag" name="item-tag" required>
        <br><br>
        <button class="btn" type="submit">Add</button>
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
            echo "<div>";
            echo "<strong>Name:</strong> " . $item['name'] . "<br>";
            echo "<strong>Quantity:</strong> ";
            echo "<form action='update_InvQuantity.php' method='post'>";
            echo "<input type='hidden' name='item-name' value='" . $item['name'] . "'>";
            echo "<input type='hidden' name='item-quantity' value='" . $quantity . "'>";
            echo "<div class='quantity-btn'>";
            echo "<button class='btn minus-btn' type='submit' name='action' value='minus'>-</button>";
            echo "<span class='quantity'>" . $quantity . "</span>";
            echo "<button class='btn plus-btn' type='submit' name='action' value='plus'>+</button>";
            echo "</div>";
            echo "</form>";
            echo "<br><strong>Tag:</strong> " . $item['tag'];
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

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const name = document.getElementById('item-name').value;
        const quantity = parseInt(document.getElementById('item-quantity').value);
        const tag = document.getElementById('item-tag').value;
        const item = {
            name: name,
            quantity: quantity,
            tag: tag
        };
        addItem(item);
        form.reset();
        hideAddItemBox();
    });

    function addItem(item) {
        const scrollBox = document.querySelector('.scroll-box');
        const itemDiv = document.createElement('div');
        itemDiv.classList.add('item'); // Add 'item' class to the new item div
        //const div = document.createElement('div'); //not looking good, try each el as own div

        const div1 = document.createElement('div');
        const nameSpan = document.createElement('span');
        nameSpan.innerText = "Name: " + item.name;
        const quantityBtnDiv = document.createElement('div');
        quantityBtnDiv.classList.add('quantity-btn');
        const minusBtn = document.createElement('button');
        minusBtn.classList.add('btn', 'minus-btn');
        minusBtn.setAttribute('data-id', item.name);
        minusBtn.innerText = '-';
        const quantitySpan = document.createElement('span');
        quantitySpan.classList.add('quantity');
        quantitySpan.innerText = item.quantity;
        const plusBtn = document.createElement('button');
        plusBtn.classList.add('btn', 'plus-btn');
        plusBtn.setAttribute('data-id', item.name);
        plusBtn.innerText = '+';
        const div2 = document.createElement('div');
        const tagSpan = document.createElement('span');
        tagSpan.innerText = "Tag: " + item.tag;
        const removeBtn = document.createElement('button');
        removeBtn.classList.add('btn');
        removeBtn.innerText = 'Remove';
        removeBtn.addEventListener('click', function() {
            scrollBox.removeChild(itemDiv);
        });
        div1.appendChild(nameSpan);
        quantityBtnDiv.appendChild(minusBtn);
        quantityBtnDiv.appendChild(quantitySpan);
        quantityBtnDiv.appendChild(plusBtn);
        div1.appendChild(quantityBtnDiv);
        div2.appendChild(tagSpan);
        div2.appendChild(removeBtn);
        itemDiv.appendChild(div1);
        itemDiv.appendChild(div2);
        scrollBox.appendChild(itemDiv);
    }

</script>
</body>
