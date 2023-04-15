<?php

//function checkLogin($conn){
//    if(isset($_SESSION['username'])){ // check if user session variable is set
//        $id = $_SESSION['username']; // get the user's username from the session variable
//        $query = "SELECT * FROM users WHERE usersUsername = '$id' limit 1;"; // query the database to get the user's data
//        $result = mysqli_query($conn, $query); // execute the query
//        header("location: home.html");
//        exit();
//        die();
//    }
//    else{
//        header("location: login.php");
//    }
//
//}


$host = "oceanus.cse.buffalo.edu";
$user = "venkatay";
$password = "50337119";
$database = "cse442_2023_spring_team_ae_db";

$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//checkLogin($conn);
?>



<?php
$sql = "SELECT * FROM sharedInventory WHERE (username = 'hGilmore909')"; //test user
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
        <title>Shared Inventory</title>

    <link rel="stylesheet" href="shared-inventory.css">

    <!--Add Item Form-->
    <script>
        function openItemForm() {
            document.getElementById("form-container").style.display = "block";
            document.getElementById("info-from").style.display = "none";
        }
        function closeItemForm() {
            document.getElementById("form-container").style.display = "none";
        }

    </script>

</head>
<body>

<header>
    <div>
        <img class='icon' src="Saturn.png" alt="RoomAid">
        <span class="h3">RoomAid</span>
        <nav>
            <ul>
                <li><a href="home.html" class="nav-button">Home</a></li>
                <li><a href="#" class="nav-button">Schedule</a></li>
                <li><a href="#" class="nav-button">Calendar</a></li>
                <li><a href="shared-inventory.php" class="nav-button">Inventory</a></li>
                <li><a href="Shared_Expenses.php" class="nav-button">Expenses</a></li>
            </ul>
        </nav>
        <a href="#"><img id='icon-pfp' src="profile.png" alt="Profile"></a>
    </div>
</header>


<div class="add_item">
    <button class="add_item" onclick="openItemForm()"><span id="add_item"> + Add Item</span></button>
</div>
<div class="form-container" id="form-container">
    <form action="/additem.php" method="post" name="addItemForm">

        <input type="text" id="itemName" name="itemName" placeholder="Enter Item Name" required>
        <input type="text" id="quantity" name="quantity" placeholder="0" required>
        <br>
        <div class="tagtitle">
            <h3>Select Tag</h3>
        </div>
        <input type="radio" id="food" name="tag" value="Food">
        <label for="food">Food</label>
        <br>
        <input type="radio" id="drinks" name="tag" value="Drinks">
        <label for="drinks"> Drinks</label>
        <br>
        <input type="radio" id="things" name="tag" value="Things">
        <label for="things"> Things</label>
        <br>
<!--        <input type="file" name="image">-->
        <button type="button" style="font-family: 'Inter', sans-serif; font-style: normal; font-weight: 400; font-size: 1.5vw" class="btn cancel" onclick="closeItemForm()">Cancel</button>
        <button type="submit" style="font-family: 'Inter', sans-serif; font-style: normal; font-weight: 400; font-size: 1.5vw" class="btn">Submit</button>
    </form>
</div>


<div class="item_section">
    <?php
    $result = mysqli_query($conn, $sql);
    $top_increment = 3;
    $i = 1;

    while ($row = mysqli_fetch_assoc($result)) {
        $itemName = $row['itemName'];
        $quantity = $row['quantity'];
        $tag = $row['tag'];
        $top = $top_increment * $i;
        $item_box_id = "item_box".$i;
        echo
            '<div class="item_box" id='.$item_box_id.'>
                <span class="itemName" id="itemName">'.$itemName.'</span>
                <div class="quantitytitle">Quantity</div>
                <span class="quantity" id="quantity">'.$quantity.'</span>
    
                <span class="tag" id="item-tag">'.$tag.'</span>
                <div class="incquant">
                    <form method="post" action="incItemQuant.php">
                        <input type="hidden" name="itemname" value="'.$itemName.'">
                        <input type="hidden" name="itemquant" value="'.$quantity.'">
                        <button type="submit" name="action">+</button>
                    </form>
                </div>
                <div class="decquant">
                     <form method="post" action="decItemQuant.php">
                        <input type="hidden" name="itemname" value="'.$itemName.'">
                        <input type="hidden" name="itemquant" value="'.$quantity.'">
                        <button type="submit" name="action">-</button>
                    </form>
                </div>
                <div class="removeitem">
                    <form method="post" action="removeItem.php">
                        <input type="hidden" name="itemname" value="'.$itemName.'">
                        <input type="hidden" name="itemquant" value="'.$quantity.'">
                        <button type="submit" name="action">X</button>
                    </form>
                </div>
            
            </div>';
        $i++;
    }
    ?>
</div>

</body>
</html>
