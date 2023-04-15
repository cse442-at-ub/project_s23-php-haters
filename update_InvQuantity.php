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
    // ^^^ I'm storing groupName in $SESSION VAR from my 'inventory.php' page
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


    // check which button they hit, PREVENT IF <0
    if ($action == 'minus' && $item_quantity > 0) {
        $item_quantity--;
    } elseif ($action == 'plus') {
        $item_quantity++;
    }elseif($action == 'delete'){
        //need to delete itmName for that GROUPname!!
        $sql_delete = "DELETE FROM groupInventory WHERE itemName = ? AND groupName = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("ss", $item_name, $groupName);
        $stmt_delete->execute();
        $stmt_delete->close();
        header("Location: inventory.php");
        exit;
    }elseif($action == 'add' && $item_quantity >= 0){
        $item_tag = $_POST['item-tag'];
        $sql_add = "INSERT INTO groupInventory (groupName, itemName, quantity, tag1) VALUES (?, ?, ?, ?)";
        $stmt_add = $conn->prepare($sql_add);
        $stmt_add->bind_param("ssis", $groupName, $item_name, $item_quantity, $item_tag);
        $stmt_add->execute();
            //  dont think im getting here?? try to debug
        echo "add this item--->: " . $item_name;
        if ($stmt_add->error) {
            echo "Error: " . $stmt_add->error;
        } else {
            echo "Item added successfully";
        }

        $stmt_add->close();
        header("Location: inventory.php");
        exit;
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
