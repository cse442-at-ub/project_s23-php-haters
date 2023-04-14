<?php
include 'util.php';

session_start();
$name = $_SESSION["username"];
//$name = "Ben";
//$name = "asfd";
//$name = 'hGilmore909';


$group_name = getGroupName($name, connect());

// check if any task at all are overdue
removeOverdue();
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <title>Roomaid Task Schedule</title>
  <link rel="stylesheet" href="task-schedule.css">
  <link rel="stylesheet" href="group.css">
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
            <span class="h3"> RoomAid </span>
            <nav>
                <ul>
                    <li><a href="home.php " class="nav-button">Home</a></li>
                    <li><a href="task-schedule.php" class="nav-button">Schedule</a></li>
                    <li><a href="group.php" class="nav-button">Group</a></li>
                    <li><a href="inventory.php" class="nav-button">Inventory</a></li>
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
            <div>
                <?php echo getGroupMembers($group_name, connect()); ?>
            </div>
            <button id="submitButton"  type="submit">Submit</button>
        </form>
      <script>
          const form = document.getElementById('addTaskForm');
          const submitButton = document.getElementById('submitButton');
          submitButton.addEventListener('click', function (event) {
              if (form.checkValidity()) {
                  console.log("VALID FORM")
                  form.submit();
                  location.reload();
              } else {
                  console.log("ERROR ERROR")
              }
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
                ?>
                <div class="task_box">
                    <span class="taskName" id="taskboxname"><?php echo $row['task']; ?></span>
                    <div class="taskpriority">Priority: <?php echo $row['importance']; ?></div>
                    <span class="taskdate" id="taskduedate">Do by: <?php echo $row['due_date']; ?></span>
                    <form class="deleteEntry" method="POST" action="taskComplete.php">
                        <input type="hidden" name="check_task" value="<?php echo $row['task']; ?>">
                        <input type="hidden" name="check_imp" value="<?php echo $row['importance']; ?>">
                        <input type="hidden" name="check_date" value="<?php echo $row['due_date']; ?>">
                        <button class="deleteEntryBtn" type="submit" name="delete_task">X</button>
                    </form>
                </div>
                <?php
            }
        }
      ?>
  </div>
</body>
</html>

