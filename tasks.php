<?php
include("database.php");
include("header.html");
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Handle creating new task list
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_task_list'])) {
    $taskListTitle = $_POST['task_list_title'];

    $query = "INSERT INTO taskLists (list_title, username) VALUES ('$taskListTitle', '$username')";
    mysqli_query($conn, $query);
}

// Handle adding new task
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_task'])) {
    $taskTitle = $_POST['task_title'];
    $taskListId = $_POST['task_list_id'];

    $stmt = $conn->prepare("INSERT INTO tasks (title, task_list_id, owner) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $taskTitle, $taskListId, $username);
    $stmt->execute();
}

// Handle deleting task list
if (isset($_GET['delete_task_list'])) {
    $taskList_id = $_GET['delete_task_list'];

    $stmt = $conn->prepare("DELETE FROM taskLists WHERE username=? AND task_list_id=?");
    $stmt->bind_param("si", $username, $taskList_id);
    $stmt->execute();

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
}

// Handle assigning task
if (isset($_POST['assign_task'])){
    $task_id = $_POST['task_id'];
    $assigned_user = $_POST['assigned_user'];
    
    //username check
        $stmt = $conn->prepare("UPDATE tasks SET assigned=? WHERE owner=? AND id=?");
        $stmt->bind_param("ssi", $assigned_user, $username, $task_id);
        if ($stmt->execute()){
            echo "Task assigned successfully.";
        } else{
            echo "database error" . $stmt->error;
        }
        $stmt->close();
}

// Handle unassigning task
if (isset($_GET['unassign_task'])) {
    $task_id = $_GET['unassign_task'];

    $stmt = $conn->prepare("UPDATE tasks SET assigned=NULL WHERE id=?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
}

// Retrieve all task lists for the user
$query = "SELECT * FROM taskLists WHERE username='$username' ORDER BY timestamp DESC";
$taskLists = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Your Task Lists</title>
</head>
<h1>Welcome, <?php echo $username; ?></h1>

<form method="POST">
    <input type="text" name="task_list_title" placeholder="New Task List Title" required>
    <button type="submit" name="new_task_list">Create New Task List</button>
</form>

<?php while ($taskList = mysqli_fetch_assoc($taskLists)) { ?>
    <div class="task-list">
        <h2><?php echo $taskList['list_title']; ?>
            <a href="?delete_task_list=<?php echo $taskList['task_list_id']; ?>">Delete Task List</a>
        </h2>

        <form method="POST">
            <input type="hidden" name="task_list_id" value="<?php echo $taskList['task_list_id']; ?>">
            <input type="text" name="task_title" placeholder="New Task Title" required>
            <button type="submit" name="add_task">Add Task</button>
        </form>

        <?php
        $currentTaskList_id = $taskList['task_list_id'];
        $query = "SELECT * FROM tasks WHERE task_list_id='$currentTaskList_id' AND owner='$username' ORDER BY timestamp DESC";
        $tasks = mysqli_query($conn, $query);

        while ($task = mysqli_fetch_assoc($tasks)) { ?>
            <div class="task">
                <p><?php echo $task['title']; ?> - <?php echo $task['status'] ?>
                    <a href="?delete_task=<?php echo $task['id']; ?>">Delete Task</a>
                    <form method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        <input type="text" name="assigned_user" placeholder="Enter user to assign" required>
                        <button type="submit" name="assign_task">Assign the task to someone</button>
                    </form>
                </p>
            </div>
        <?php } ?>
    </div>
<?php }
?>
<h2> Assigned Tasks</h2>
<?php $query = "SELECT * FROM tasks WHERE assigned = '$username' ORDER BY timestamp DESC";
$tasks = mysqli_query($conn, $query);

while ($task = mysqli_fetch_assoc($tasks)) {
    ?>
    <div class="task">
        <p><?php echo $task['title']; ?> - <?php echo $task['status'] ?>
            <a href="?unassign_task=<?php echo $task['id']; ?>">Leave Task</a>
        </p>
    </div>
<?php } ?>
</body>

</html>