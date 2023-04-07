<?php
include 'util.php';

session_start();
//$name = $_SESSION["username"];
//$name = "Ben";
//$name = "asfd";
$name = 'hGilmore909';


$group_name = getGroupName($name, connect());

// check if any task at all are overdue
removeOverdue();
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <title>Roomaid Task Schedule</title>
  <link rel="stylesheet" href="task-schedule.css">
  <link rel="stylesheet" href="shared-inventory.css">
    <script>
        // Doesn't let user choose a date before current date
        window.onload = function() {
            var taskDateInput = document.getElementById("due-date");
            var currentDate = new Date().toISOString().split('T')[0];
            taskDateInput.min = currentDate;
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

  <div class="pageTitle">
    Task Schedule
  </div>

  <div class="formcontainer">
    <form action="add_task.php"  method="post" name="addTaskForm" id="addTaskForm">
      <div class="taskTitle">
        <input type="text" id="task-name" name="task-name" placeholder="Enter Task Name" required>
      </div>
      <div class="setPriority">
        <label for="priority">Set Priority To Task:</label>
        <select name="priority" id="priority" required>
          <option value="1">1 (Least Important)</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5 (Most Important)</option>
        </select>
      </div>
      <div class="taskDueDate">
        <label for="due-date">Set Task Due Date:</label>
        <input type="date" id="due-date" name="due-date" required>
      </div>
      <button id="submitButton"  type="submit">Submit</button>
    </form>
      <script>
          const form = document.getElementById('addTaskForm');
          const submitButton = document.getElementById('submitButton');
          submitButton.addEventListener('click', function() {
              form.submit();
              location.reload();
          });
      </script>
  </div>



<!--  Here is where tasks are displayed. Works similar to the item-section in shared-inventory.php-->
  <div class="task-section">
      <?php
        // get task
//      echo ($group_name);
        if (ctype_space($group_name)) {
            echo '<script>console.log("No Results")</script>';
            return "You are not in a group";
        } else {
            $tasks = getTasks($group_name, connect());
            // make it iterable
            $data = array();
            if (mysqli_num_rows($tasks) > 0) {
                while ($row = mysqli_fetch_assoc($tasks)) {
                    $data[] = $row;
                }
            }
            // iterate through them
            foreach ($data as $row) {
                echo '<div class="task_box">';
                echo '<span class="taskName" id="taskboxname">' . $row['task'] . '</span>';
                echo '<div class="taskpriority">Priority: ' . $row['importance'] . '</div>';
                echo '<span class="taskdate" id="taskduedate">Do by: ' . $row['due_date'] . '</span>';
                echo '</div>';
                echo '</div>';
            }
        }
      ?>

</body>
</html>

