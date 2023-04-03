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
        .title {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .title h2 {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
        }

        body {
            background-color: #CDB7E9;
        }

        .scroll-box {
            background-color: #FFFFFF;
            height: 75vh;
            max-height: calc(75vh - 10vh);
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
            font-size: 50px;
            line-height: 1.5;
        }

        #add-item-btn {
            background-color: #EEE3A4;
            color: #000000;
            font-size: 24px;
            padding: 10px 20px;
            border: 3px solid #000000;
            border-radius: 10px;
            /*margin: 0 auto; !* center button horizontally for now...  *!*/
            display: block; /* make button a block element to center it vertically for now */
            /*margin-top: 10px; !* top margin to put space between button and nav bar!!  */
        }

        /*adding new item-  trying to fix layout for new items so it matches others */
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 25px; /* Add this line to match the margin of the other items */
        }

        form > * {
            margin-bottom: 15px; /* this shud match the margin of the other items???...ahhh idk */
        }

        form label {
            font-size: 20px;
            margin-right: 10px;
        }

        form input {
            padding: 5px;
            font-size: 20px;
            border: none;
            border-bottom: 2px solid black;
            background-color: #F3ECC1;
        }

        /* trying modal box to add an item */
        #add-item-modal {
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        #add-item-modal form {
            background-color: #F3ECC1;
            margin: auto;
            padding: 20px;
            border: 2px solid #000000;
            width: 80%;
        }

        #add-item-modal form label {
            font-size: 20px;
            margin-right: 10px;
        }

        #add-item-modal form input {
            padding: 5px;
            font-size: 20px;
            border: none;
            border-bottom: 2px solid black;
            background-color: #FFFFFF;
            margin-bottom: 10px;
            width: 100%;
        }
        #add-item-modal form button[type="submit"],
        #add-item-modal form button[type="button"] {
            margin-top: 10px;
            font-size: 20px;
            font-weight: bold;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #FFFFFF;
            color: #000000;
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
    <h2 style="font-family: sans-serif;">MY SHARED INVENTORY:</h2>
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
    //const form = document.querySelector('form');
    const modal = document.getElementById("add-item-modal");
    const form = modal.querySelector("form");

    // function showAddItemBox() {// javascript can dynamically show/hide forms to enter info ;)
    //     document.getElementById("add-item-box").style.display = "block";
    // }
    function showAddItemBox() {
        const modal = document.getElementById("add-item-modal");
        modal.style.display = "block";
    }

    //
    // function hideAddItemBox() {
    //     document.getElementById("add-item-box").style.display = "none";
    // }
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

    const minusBtns = document.querySelectorAll('.minus-btn');
    const plusBtns = document.querySelectorAll('.plus-btn');
    // cant get to work with newly entered item^^  making new function to track new item buttons

    minusBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const itemId = btn.getAttribute('data-id');
            const quantityEl = btn.parentElement.querySelector('.quantity');
            let quantity = parseInt(quantityEl.innerText);
            if (quantity > 0) {
                quantity--;
                quantityEl.innerText = quantity;
                if (quantity < 3) {
                    //btn.parentElement.parentElement.style.backgroundColor = "#E50303"; //do 40% of this??
                    btn.parentElement.parentElement.style.backgroundColor = "#F27A93"; //lighter red looks bettr
                }
            }
        });
    });

    // plus button for quantity
    plusBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const itemId = btn.getAttribute('data-id');
            const quantityEl = btn.parentElement.querySelector('.quantity');
            let quantity = parseInt(quantityEl.innerText);
            quantity++;
            quantityEl.innerText = quantity;
            if (quantity >= 3) {
                btn.parentElement.parentElement.style.backgroundColor = "#F3ECC1";
            }
        });
    });

    // adding another listener but only for newly added items
    const newMinusBtn = itemDiv.querySelector('.minus-btn');
    const newPlusBtn = itemDiv.querySelector('.plus-btn');

    newMinusBtn.addEventListener('click', () => {
        const quantityEl = itemDiv.querySelector('.quantity');
        let quantity = parseInt(quantityEl.innerText);
        if (quantity > 0) {
            quantity--;
            quantityEl.innerText = quantity;
            if (quantity < 3) {
                itemDiv.style.backgroundColor = "#F27A93";
            }
        }
    });

    newPlusBtn.addEventListener('click', () => {
        const quantityEl = itemDiv.querySelector('.quantity');
        let quantity = parseInt(quantityEl.innerText);
        quantity++;
        quantityEl.innerText = quantity;
        if (quantity >= 3) {
            itemDiv.style.backgroundColor = "#F3ECC1";
        }
    });


</script>
</body>
