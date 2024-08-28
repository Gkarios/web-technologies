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

$searchResults = [];
$assignedTasks = [];
$searchQuery = '';

if (isset($_GET['search_query'])) {
    $searchQuery = $_GET['search_query'];
    
    // Determine whether to search or display all results
    $likeQuery = "%" . $searchQuery . "%";
    $whereClauseTaskLists = $searchQuery !== '' ? "(tl.list_title LIKE ? OR t.title LIKE ?)" : "1=1";
    $whereClauseTasks = $searchQuery !== '' ? "title LIKE ?" : "1=1";
    
    // Query to search in task lists and tasks
    $query = "
        SELECT tl.task_list_id, tl.list_title, t.title 
        FROM taskLists tl 
        LEFT JOIN tasks t ON tl.task_list_id = t.task_list_id 
        WHERE $whereClauseTaskLists 
        AND tl.username = ? 
        ORDER BY tl.timestamp DESC, t.timestamp DESC
    ";
    $stmt = $conn->prepare($query);

    if ($searchQuery !== '') {
        $stmt->bind_param("sss", $likeQuery, $likeQuery, $username);
    } else {
        $stmt->bind_param("s", $username);
    }
    
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $searchResults = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "No results found in task lists.";
    }
    $stmt->close();

    // Query to get tasks assigned directly to the user
    $query = "
        SELECT title 
        FROM tasks 
        WHERE $whereClauseTasks 
        AND assigned = ? 
        ORDER BY timestamp DESC
    ";
    $stmt = $conn->prepare($query);

    if ($searchQuery !== '') {
        $stmt->bind_param("ss", $likeQuery, $username);
    } else {
        $stmt->bind_param("s", $username);
    }
    
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $assignedTasks = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "No assigned tasks found.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Task Lists and Tasks</title>
</head>

<body>
    <form method="GET">
        <input type="text" name="search_query" placeholder="Search"
            value="<?php echo htmlspecialchars($searchQuery); ?>">
        <button type="submit">Search</button>
    </form>

    <?php if (!empty($searchResults)): ?>
        <p>Search Results in Task Lists:</p>
        <?php
        $currentTaskList = null;
        foreach ($searchResults as $result): 
            // Check if we are on a new task list
            if ($currentTaskList !== $result['task_list_id']):
                if ($currentTaskList !== null):
                    // Close the previous task list's UL tag
                    echo "</ul>";
                endif;
                
                // Update current task list ID and print the new task list title
                $currentTaskList = $result['task_list_id'];
                ?>
                <h3><?php echo htmlspecialchars($result['list_title']); ?></h3>
                <ul>
            <?php endif; ?>

            <?php if (!empty($result['title'])): ?>
                <li><?php echo htmlspecialchars($result['title']); ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!empty($assignedTasks)): ?>
        <p>Tasks Assigned to You:</p>
        <ul>
            <?php foreach ($assignedTasks as $task): ?>
                <li><?php echo htmlspecialchars($task['title']); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>

</html>
