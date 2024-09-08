<?php
include("database.php");

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
$noMainTask = false;
$noAssignedTask = false;

if (isset($_GET['search_query'])) {
    $searchQuery = $_GET['search_query'];

    // Query to search in task lists and tasks
    $query = "
        SELECT tl.task_list_id, tl.list_title, t.title, t.status, t.timestamp
        FROM taskLists tl 
        LEFT JOIN tasks t ON tl.task_list_id = t.task_list_id 
        WHERE (tl.list_title LIKE ? OR t.title LIKE ? OR t.status LIKE ?) 
        AND tl.username = ? 
        ORDER BY tl.timestamp DESC, t.timestamp DESC
    ";
    $stmt = $conn->prepare($query);
    $likeQuery = "%" . $searchQuery . "%"; // adding wildcards for partial matching
    $stmt->bind_param("ssss", $likeQuery, $likeQuery, $likeQuery, $username);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $searchResults = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $noMainTask = true;
    }
    $stmt->close();

    $query = "
    SELECT title, owner, status
    FROM tasks 
    WHERE assigned = ? 
    AND (title LIKE ? OR status LIKE ?) 
    ORDER BY timestamp DESC
";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $likeQuery, $likeQuery);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $assignedTasks = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $noAssignedTask = true;
    }
    $stmt->close();
}
$conn->close();

if ($noMainTask == true && $noAssignedTask == true) {
    echo "No Results found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Task Lists and Tasks</title>
</head>

<body>
    <header>
        <a href="home.php">Home</a>
        <a href="tasks.php">Edit Tasks</a>
    </header>
    <form method="GET">
        <input type="text" name="search_query" placeholder="Search"
            value="<?php echo htmlspecialchars($searchQuery); ?>">
        <button type="submit">Search</button>
    </form>

    <?php if (!empty($searchResults)): ?>
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
                    <li><?php echo htmlspecialchars($result['title']); ?> - <?php echo $result['status']?> - <?php echo $result['timestamp']?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php if (!empty($assignedTasks)): ?>
        <h3>Tasks Assigned to You:</h3>
        <ul>
            <?php foreach ($assignedTasks as $task): ?>
                <li><?php echo htmlspecialchars($task['title']); ?> - <?php echo $task['status']?> - by <?php echo htmlspecialchars(($task['owner'])); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>