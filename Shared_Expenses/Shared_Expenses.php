<?php

//include "process-form.php";
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
//using 'hGilmore999' for testing purposes'
    $sql = "SELECT * FROM allExpenses WHERE username = 'hGilmore909' ";
    $graph = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
    <title>Shared Expenses</title>

    <link rel="stylesheet" href="Shared_Expenses.css">

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
        }
        function closeBillForm() {
            document.getElementById("form-container").style.display = "none";
        }
        //clears form fields and resets it.
        function textClear() {
            var frm=document.getElementsByName("addBillForm")
            frm.submit(); //submit the form
            frm.reset(); //reset all from data
            return false;

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
    <form action="process-form.php" method="post" name="addBillForm" onsubmit="textClear()">

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
        $sql = "SELECT * FROM allExpenses;";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $expenseName = $row['expenseName'];
            $dueDate = $row['dueDate'];
            $amount = $row['totalAmt'];
            echo '<div class="bill_box">
    <span class="bill_Name">';$expenseName; '</span>
    <span class="bill_Date">';$dueDate; '</span>
    <span class="bill_Amount">'; $amount; '</span></div>';
    echo '<style>.bill_box {
        top: +20%; bottom: -20%;
    }</style>';
    }?>
</div>

</body>