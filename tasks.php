<?php
include("database.php");
include("header.html");
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Handle creating new task list
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_task_list'])) {
    $taskListTitle = $_POST['task_list_title'];
    $query = "INSERT INTO taskLists (title, username) VALUES ('$taskListTitle', '$username')";
    mysqli_query($conn, $query);
}

// Handle adding new task
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_task'])) {
    $taskTitle = $_POST['task_title'];
    $taskOwner = $ 
    $query = "INSERT INTO tasks (title) VALUES ('$taskTitle')";
    mysqli_query($conn, $query);
}

// Handle deleting task list
if (isset($_GET['delete_task_list'])) {
    $query = "DELETE FROM taskLists WHERE username='$username'";
    mysqli_query($conn, $query);

    // Also delete associated tasks
    $query = "DELETE FROM tasks WHERE username='$username'";
    mysqli_query($conn, $query);
}

// Handle deleting task
if (isset($_GET['delete_task'])) {
    $query = "DELETE FROM tasks WHERE username='$username'";
    mysqli_query($conn, $query);
}

// Retrieve all task lists for the user
$query = "SELECT * FROM taskLists WHERE username='$username' ORDER BY timestamp DESC";
$taskLists = mysqli_query($conn, $query);

?>

<!DOCTYPE html  >
<html>
<head>
    <title>Manage Your Task Lists</title>
</head>
<body>
    <h1>Welcome, <?php echo $username; ?></h1>
    
    <form method="POST">
        <input type="text" name="task_list_title" placeholder="New Task List Title" required>
        <button type="submit" name="new_task_list">Create New Task List</button>
    </form>

    <?php while ($taskList = mysqli_fetch_assoc($taskLists)) { ?>
        <div class="task-list">
            <h2><?php echo $taskList['title']; ?> 
                <a href="?delete_task_list=<?php echo $taskList['timestamp']; ?>">Delete Task List</a>
            </h2>
            
            <form method="POST">
                <input type="text" name="task_title" placeholder="New Task Title" required>
                <button type="submit" name="add_task">Add Task</button>
            </form>

            <?php
            $currentTaskList = $taskList['title'];
            $query = "SELECT * FROM tasks WHERE task='$currentTaskList' ORDER BY timestamp DESC";
            $tasks = mysqli_query($conn, $query);

            while ($task = mysqli_fetch_assoc($tasks)) { ?>
                <div class="task">
                    <p><?php echo $task['title']; ?> - <?php echo $task['task']; ?> 
                        <a href="?delete_task=<?php echo $task['timestamp']; ?>">Delete Task</a>
                    </p>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</body>
</html>
