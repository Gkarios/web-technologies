<?php
include("backend/database.php");
include("header.html");

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['username'])) {
    $_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
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

?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Search Task Lists and Tasks</title>
<link rel="stylesheet" href="css/results.css" />
</head>

<body>
    <a href="tasks.php" class="button">Edit Tasks</a>
    <br>
    <h1>Search Your Tasks</h1>
    <form method="GET">
        <input type="text" name="search_query" placeholder="Search"
            value="<?php echo htmlspecialchars($searchQuery); ?>">
        <button type="submit">Search</button>
    </form>

    <div class="results">
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
                    <li><?php echo htmlspecialchars($result['title']); ?> - <div class="status"><?php echo $result['status'] ?></div> -
                        <div class="timestamp"><?php echo $result['timestamp'] ?></div>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php if (!empty($assignedTasks)): ?>
        <h3>Tasks Assigned to You:</h3>
        <ul>
            <?php foreach ($assignedTasks as $task): ?>
                <li><?php echo htmlspecialchars($task['title']); ?> - <div class="status"><?php echo $task['status'] ?></div> - by
                    <?php echo htmlspecialchars(($task['owner'])); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <br>
    <?php endif;

if ($noMainTask == true && $noAssignedTask == true) {
    echo '<br><div class="statusMessage">No results found.</div>';
}

    ?>
    </div>
</body>

</html>
