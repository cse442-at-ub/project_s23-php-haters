<!DOCTYPE html>
<html>
<head>
    <title>Inventory Items</title>
    <style>
        /*Nav Bar */
        .icon-pfp {
            position: relative;
            height: 10vh;
        }

        header {
            width: 100%;
            position: static;
            height: 10vh;
            display: flex;
        }

        header * {
            display: flex;
            align-items: center;
        }

        header li {
            margin-right: 1%;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            font-style: normal;
        }

        .nav-button {
            box-sizing: border-box;
            background: #EEE3A4;
            padding: 3vh 8vh;
            border: 3px solid #000000;
            border-radius: 10px;
            margin-right: 5vh;
        }

        /* main  Webpage  */
        body {
            background-color: #CDB7E9;
        }

        .scroll-box {
            background-color: #FFFFFF;
            height: 70vh;
            max-height: calc(70vh - 10vh);
            overflow: auto;
            padding: 25px;
            position: fixed;
            bottom: 10px;
            width: 80%;
            left: 10%;
            border: 2px solid black;
        }

        .item {
            background-color: #F3ECC1;
            color: #000000;
            padding: 30px;
            margin-bottom: 25px;
            border-radius: 25px;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }


        .item div {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .item strong {
            font-size: 20px;
            margin-right: 10px;
        }

        .item span {
            font-size: 20px;
            font-weight: bold;
            margin-right: 10px;
        }

        .item button {
            font-size: 20px;
            font-weight: bold;
            margin-right: 10px;
        }

        .btn {
            background-color: #FFFFFF;
            color: #000000;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            line-height: 1.5;
        }

    </style>
</head>

<body>
<header>
    <div>
        <img class='icon' src="Saturn.png" alt="RoomAid">
        <span class="h3"> RoomAid </span>
        <nav>
            <ul>
                <li><a href="#" class="nav-button">Home</a></li>
                <li><a href="#" class="nav-button">Schedule</a></li>
                <li><a href="#" class="nav-button">Calendar</a></li>
                <li><a href="#" class="nav-button">Inventory</a></li>
                <li><a href="Shared_Expenses.php" class="nav-button">Expenses</a></li>
                <li><a href="#"><img class='icon-pfp' src="Giraffe.png" alt="Profile"></a></li>
                </li>
            </ul>
        </nav>
    </div>
</header>

<!--  Buttons above scroll box. do we need tag stuff?   -->
<div>
    <button class="btn" style="background-color: #EEE3A4; color: #000000;" onclick="showAddItemBox()">Add Item</button>
</div>
<div id="add-item-box" style="display: none;">
    <form>
        <label for="item-name">Name:</label>
        <input type="text" id="item-name" name="item-name" required>
        <br>
        <label for="item-quantity">Quantity:</label>
        <input type="number" id="item-quantity" name="item-quantity" required>
        <br>
        <label for="item-tag">Tag:</label>
        <input type="text" id="item-tag" name="item-tag" required>
        <br><br>
        <button class="btn" type="submit">Add</button>
        <button class="btn" type="button" onclick="hideAddItemBox()">Cancel</button>
    </form>
</div>


<!--SCROLL BAR STATIC OBJECTS FOR NOW?-->
<div class="scroll-box">
    <?php
    // Static inventory data
    $inventory = array(
        array("name" => "Item 1", "quantity" => 10, "tag" => "Tag 1"),
        array("name" => "Item 2", "quantity" => 5, "tag" => "Tag 2"),
        array("name" => "Item 3", "quantity" => 15, "tag" => "Tag 3"),
        array("name" => "Item 4", "quantity" => 20, "tag" => "Tag 4"),
        array("name" => "Item 5", "quantity" => 7, "tag" => "Tag 5"),
        array("name" => "Item 6", "quantity" => 12, "tag" => "Tag 6")
    );

    // Display inventory items
    foreach ($inventory as $item) {
        echo "<div class='item'>";
        echo "<div>";
        echo "<strong>Name:</strong> " . $item['name'] . "<br>";
        echo "<strong>Quantity:</strong> ";
        echo "<button class='btn minus-btn' data-id='".$item['name']."'>-</button>";
        echo "<span class='quantity'>".$item['quantity']."</span>";
        echo "<button class='btn plus-btn' data-id='".$item['name']."'>+</button>";
        echo "<br><strong>Tag:</strong> " . $item['tag'];
        echo "</div>";
        echo "</div>";
    }
    ?>
</div>

<script>
    // Event listeners for plus and minus buttons...querySelectorAll finds buttons
    const minusBtns = document.querySelectorAll('.minus-btn');
    const plusBtns = document.querySelectorAll('.plus-btn');

    minusBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const itemId = btn.getAttribute('data-id');
            const quantityEl = btn.parentElement.querySelector('.quantity');
            let quantity = parseInt(quantityEl.innerText);
            if (quantity > 0) {
                quantity--;
                quantityEl.innerText = quantity;
                if (quantity < 3) {
                    btn.parentElement.parentElement.style.backgroundColor = "#E50303"; //do 40% of this??
                }
            }
        });
    });

    plusBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const itemId = btn.getAttribute('data-id');
            const quantityEl = btn.parentElement.querySelector('.quantity');
            let quantity = parseInt(quantityEl.innerText);
            quantity++;
            quantityEl.innerText = quantity;
        });
    });
      // javascript dynamically wil show box to enter info ;)
    function showAddItemBox() {
        document.getElementById("add-item-box").style.display = "block";
    }

    function hideAddItemBox() {
        document.getElementById("add-item-box").style.display = "none";
    }
  
</script>
</body>
