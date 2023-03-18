<?php

// session_start();
//$current_user = $_SESSION['username'];

$host = "oceanus.cse.buffalo.edu";
$user = "arpithir";
$password = "50340819";
$database = "cse442_2023_spring_team_ae_db";

//make sure we found oceanus
$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}?>

<?php
// $sql = "SELECT * FROM roomAidExpenses WHERE username = '$current_user'";
//using 'hGilmore909' for testing purposes and we will replace that with current user after make group formation.'
$sql = "SELECT * FROM allExpensesV2 WHERE (username = 'hGilmore909' OR user2 = 'hGilmore909' OR user3 = 'hGilmore909' OR user4 = 'hGilmore909')";
$graph = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
    <title>Shared Expenses</title>

    <link rel="stylesheet" href="Shared_Expenses.css">
<!--Using Google Developers Chart API to represent the chart for user expenses.-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Expense Name', 'Total Amount'],
                <?php
                while ($chart = mysqli_fetch_assoc($graph)) {
                    echo "['".$chart['expenseName']."',".$chart['totalAmt']."],";
                }
                ?>
            ]);
            //Formatting the Chart
            var options = {
                backgroundColor: '#CDB7E9',
                width: 645,
                height: 420,
                legend: 'none',
                pieHole: 0.4,
            };
            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(data, options);
        }
    </script>

    <!--The script for Add Bill Form-->
    <script>
        function openBillForm() {
            document.getElementById("form-container").style.display = "block";
            document.getElementById("info-from").style.display = "none";
        }
        function closeBillForm() {
            document.getElementById("form-container").style.display = "none";
        }
        //clears form fields and resets it.
        function textClear() {
            var frm=document.getElementsByName("addBillForm")
            frm.submit(); //submit the form
            return false;
        }
    </script>

<!--Script for opening and closing of the bill infor form-->
<!--    <script>-->
<!--        function openBillInfo(expenseName) {-->
<!--            document.getElementById("info-from").style.display = "block";-->
<!--            document.getElementById("form-container").style.display = "none";-->
<!--            // var billName = document.getElementById("store_var").getAttribute("data-value");-->
<!--            document.getElementById("expense_name").innerHTML = billName;-->
<!--            // document.getElementById("date").innerHTML = dueDate;-->
<!--        }-->
<!--        function closeBillInfo() {-->
<!--            document.getElementById("info-from").style.display = "none";-->
<!--        }-->
<!--    </script>-->
</head>
<body>

<header>
    <div>
        <img class='icon' src="Saturn.png" alt="RoomAid">
        <span class="h3">RoomAid</span>
        <nav>
            <ul>
                <li><a href="#" class="nav-button">Home</a></li>
                <li><a href="#" class="nav-button">Schedule</a></li>
                <li><a href="#" class="nav-button">Calendar</a></li>
                <li><a href="#" class="nav-button">Inventory</a></li>
                <li><a href="#" class="nav-button">Expenses</a></li>
            </ul>
        </nav>
        <a href="#"><img id='icon-pfp' src="profile.png" alt="Profile"></a>
    </div>
</header>


<div class="add_bill">
    <button class="add_bill" onclick="openBillForm()"><span id="add_bill"> + Add Bill</span></button>
</div>
<div class="form-container" id="form-container">
    <form action="/process-form.php" method="post" name="addBillForm" onsubmit="textClear()">

        <input type="text" id="Name" style="font-family: 'Inter', sans-serif; font-style: normal; font-weight: 400; font-size: 1.5vw" placeholder="Enter Bill Name" name="Bill_Name" required>
        <input type="date" id="Date" style="font-family: 'Inter', sans-serif; font-style: normal; font-weight: 400; font-size: 1.5vw" placeholder="EDate" name="Date" required>
        <!--Here the Placeholder for all the user will change as we have group formation page up and running. This is just for test of the functionality -->
        <input type="number"  id="User_1" min="0.00" max="10000.00" step="0.01" style="font-family: 'Inter', sans-serif; font-style: normal; font-weight: 400; font-size: 1.5vw" placeholder="User 1 Pays" name="U1" required>
        <input type="number"  id="User_2" min="0.00" max="10000.00" step="0.01" style="font-family: 'Inter', sans-serif; font-style: normal; font-weight: 400; font-size: 1.5vw" placeholder="User 2 Pays" name="U2" required>
        <input type="number"  id="User_3" min="0.00" max="10000.00" step="0.01" style="font-family: 'Inter', sans-serif; font-style: normal; font-weight: 400; font-size: 1.5vw" placeholder="User 3 Pays" name="U3" required>
        <input type="number"  id="User_4" min="0.00" max="10000.00" step="0.01" style="font-family: 'Inter', sans-serif; font-style: normal; font-weight: 400; font-size: 1.5vw" placeholder="User 4 Pays" name="U4" required>

        <button type="submit" style="font-family: 'Inter', sans-serif; font-style: normal; font-weight: 400; font-size: 1.5vw" class="btn">Submit</button>
        <button type="button" style="font-family: 'Inter', sans-serif; font-style: normal; font-weight: 400; font-size: 1.5vw" class="btn cancel" onclick="closeBillForm()">Cancel</button>
    </form>
</div>


<div id="donutchart" style="width: 900px; height: 500px;"></div>



<div class="bill_section">
    <?php
//    Here 'hGilmore909' is a test user in a group but it will be change to current user once group formation page is done.
    $sql = "SELECT * FROM allExpensesV2 WHERE (username = 'hGilmore909' OR user2 = 'hGilmore909' OR user3 = 'hGilmore909' OR user4 = 'hGilmore909')";
    $result = mysqli_query($conn, $sql);
    $top_increment = 3;
    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $expenseName = $row['expenseName'];
        $dueDate = $row['dueDate'];
        $amount = $row['totalAmt'];
        $top = $top_increment * $i;
        $bill_box_id = "bill_box".$i;
        echo '<div class="bill_box" id='.$bill_box_id.' onclick="window.open(\'Display_Expenses.php?expenseName='.$expenseName.'\', \'_blank\', \'width=600, height=400\')" style="top: '. $top .'%;">
    <span class="bill_Name" id="bill_Name">'.$expenseName.'</span>
    <span class="bill_Date" id="bill_Date">'.$dueDate.'</span>
    <span class="bill_Amount" id="bill_Amount">'.$amount.'</span></div>';
//        echo '<div id="store_var" data-value='.$row['expenseName'].' style="display: none"></div>';
        $i++;
    }?>
</div>


<!--Not displaying fancy for Sprint 2 so working with the basic functionality for now-->
<!--<div class="info-from" id="info-from">-->
<!--    <form action="/info.php" name="BillInfoForm">-->
<!---->
<!--        <button type="button" style="font-family: 'Inter', sans-serif; font-style: normal; font-weight: 400; font-size: 2vw" class="back" onclick="closeBillInfo()">Back</button>-->
<!---->
<!--        <span id="expense_name"></span>-->
<!--        <span id="date">Date: </span>-->
<!---->
<!---->
<!--    </form>-->
<!--</div>-->


</body>
</html>