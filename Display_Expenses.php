<?php

$host = "oceanus.cse.buffalo.edu";
$user = "arpithir";
$password = "50340819";
$database = "cse442_2023_spring_team_ae_db";
$conn = mysqli_connect($host, $user, $password, $database);

$expenseName = $_GET['expenseName'];
$sql = "SELECT * FROM allExpensesV2 WHERE expenseName = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $expenseName);
$stmt->execute();
$result = $stmt->get_result();

$usersAndAmounts = array();
while ($row = $result->fetch_assoc()) {
    for ($i = 1; $i <= 4; $i++) {
        $userKey = "user" . $i;
        $amtKey = $userKey . "amt";
        if (!empty($row[$userKey]) && !empty($row[$amtKey])) {
            $usersAndAmounts[] = array("user" => $row[$userKey], "amount" => $row[$amtKey]);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Expense Details</title>
    <link rel="stylesheet" href="Display_Expenses.css">
</head>
<body style="background: #CDB7E9;">
<div class="expense-details">
        <h2 class="h2"><?php echo $expenseName; ?></h2>

    <table>
        <tr>
            <th>User</th>
            <th>Amount</th>
        </tr>
        <?php foreach ($usersAndAmounts as $userAmount): ?>
            <tr>
                <td><?php echo $userAmount['user']; ?></td>
                <td><?php echo $userAmount['amount']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>