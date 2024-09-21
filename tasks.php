<?php
include("backend/database.php");
include("header.html");
include("backend/Simplepush.php");
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['username'])) {
    $_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Handle creating new task list
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_task_list'])) {
    $taskListTitle = $_POST['task_list_title'];

    $stmt = $conn->prepare("INSERT INTO taskLists (list_title, username) VALUES (?, ?)");
    $stmt->bind_param("ss", $taskListTitle, $username);
    $stmt->execute();
    $stmt->close();
}

// Handle adding new task
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_task'])) {
    $taskTitle = $_POST['task_title'];
    $taskListId = $_POST['task_list_id'];

    $stmt = $conn->prepare("INSERT INTO tasks (title, task_list_id, owner) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $taskTitle, $taskListId, $username);
    $stmt->execute();
    $stmt->close();
}

// Handle deleting task list
if (isset($_GET['delete_task_list'])) {
    $taskList_id = $_GET['delete_task_list'];

    $stmt = $conn->prepare("DELETE FROM taskLists WHERE username=? AND task_list_id=?");
    $stmt->bind_param("si", $username, $taskList_id);
    $stmt->execute();
    $stmt->close();

    // Also delete associated tasks
    $query = "DELETE FROM tasks WHERE task_list_id='$taskList_id'";
    mysqli_query($conn, $query);
}

// Handle deleting task
if (isset($_GET['delete_task'])) {
    $task_id = $_GET['delete_task'];

    $stmt = $conn->prepare("DELETE FROM tasks WHERE id=?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $stmt->close();
}

// Handle assigning task
if (isset($_POST['assign_task'])) {
    $task_id = $_POST['task_id'];
    $assigned_user = $_POST['assigned_user'];
    $task_title = $_POST['task_title'];

    //username check
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->bind_param("s", $assigned_user);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $stmt = $conn->prepare("UPDATE tasks SET assigned=? WHERE owner=? AND id=?");
        $stmt->bind_param("ssi", $assigned_user, $username, $task_id);
        if ($stmt->execute()) {
            echo "<div class='statusMessage'>Task assigned successfully.</div>";
        } else {
            echo "<div class='statusMessage'>database error" . $stmt->error . "</div>";
        }
        $stmt->close();

        //send a SimplePush Key
        $stmt = $conn->prepare("SELECT simplepushkey FROM users WHERE username = ?");
        $stmt->bind_param("s", $assigned_user);
        $key = null;
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $resultKey = $result->fetch_assoc();
            $key = $resultKey['simplepushkey'];
        } else {
            echo "sql query messed up" . $stmt->error;
        }
        $stmt->close();
        if ($key) {
            echo "working key";
            $title = "NEW TASK";
            $message = "$username HAS ASSIGNED you with the task: $task_title";
            Simplepush::send($key, $title, $message);
        }
    } else {
        echo "<div class='statusMessage'>username not found</div>";
    }
}

// Handle unassigning task
if (isset($_GET['unassign_task'])) {
    $task_id = $_GET['unassign_task'];

    $stmt = $conn->prepare("UPDATE tasks SET assigned=NULL WHERE id=?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $stmt->close();
}

// Handle changing a task's status
if (isset($_GET['change_status'])){
    $status = $_GET['change_status'];
    $task_id = $_GET['task_id'];
    
    $stmt = $conn->prepare("UPDATE tasks SET status=? where id=?");
    $stmt->bind_param("si", $status, $task_id);
    $stmt->execute();
    $stmt->close();
}

// Retrieve all task lists for the user
$query = "SELECT * FROM taskLists WHERE username='$username' ORDER BY timestamp DESC";
$taskLists = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Your Task Lists</title>
    <link rel="stylesheet" href="css/results.css"/>
    <script src="css/theme.js"></script>
</head>
<body>
<a href="results.php?search_query=" class="button">Search for Tasks</a>
<h1>Manage your tasks</h1>

<form method="POST">
    <input type="text" name="task_list_title" placeholder="New Task List Title" required>
    <button type="submit" name="new_task_list">Create New Task List</button>
</form>

<?php while ($taskList = mysqli_fetch_assoc($taskLists)) { ?>
    <div class="task-list">
        <h2><?php echo $taskList['list_title'] . "\t\t"; ?>
            <a class='button2' href="?delete_task_list=<?php echo $taskList['task_list_id']; ?>">Delete Task List</a>
        </h2>

        <form method="POST">
            <input type="hidden" name="task_list_id" value="<?php echo $taskList['task_list_id']; ?>">
            <input type="text" name="task_title" placeholder="New Task Title" required>
            <button type="submit" name="add_task">Add Task</button>
        </form>

        <?php
        $currentTaskList_id = $taskList['task_list_id'];
        $query = "SELECT * FROM tasks WHERE task_list_id='$currentTaskList_id' AND owner='$username' ORDER BY status ASC";
        $tasks = mysqli_query($conn, $query);

        while ($task = mysqli_fetch_assoc($tasks)) { ?>
            <div class="task">
                <p><?php echo $task['title']; ?> - <?php echo $task['status'] ?>
                    <button type="button" class="button2" id="change_status_<?php echo $task['id']; ?>">Change status</button>
                    <div class="divOptions" id="statusOptions_<?php echo $task['id']; ?>" style="display: none;"></div>
                    <a href="?delete_task=<?php echo $task['id']; ?>" class="button2">Delete Task</a>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                    <input type="hidden" name="task_title" value="<?php echo $task['title']; ?>">
                    <input type="text" name="assigned_user" placeholder="Enter user to assign" required>
                    <button type="submit" name="assign_task">Assign the task to someone</button>
                </form>
                </p>
            </div>
        
            <script>
                const taskId_<?php echo $task['id'] ? $task['id'] : 'null'; ?> = <?php echo json_encode($task['id'] ? $task['id'] : 'null'); ?>;
                document.getElementById('change_status_<?php echo $task['id']; ?>').addEventListener('click', function(){
                    const statusOptions = document.getElementById('statusOptions_<?php echo $task['id']; ?>');
                    statusOptions.innerHTML = '';
        
                    const options = ['in progress', 'stand by', 'completed'];
                    options.forEach(option => {
                        const link = document.createElement('a');
                        link.textContent = option;
                        link.style.display = 'block';
                        link.href = `tasks.php?change_status=${encodeURIComponent(option)}&task_id=${encodeURIComponent(taskId_<?php echo $task['id']; ?>)}`;
                        statusOptions.appendChild(link);
                    });
                    statusOptions.style.display = 'inline';
                });
            </script>
            <?php }
        } ?>
</body>
</html>