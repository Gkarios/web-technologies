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

// Output the search results for AJAX request
if ($noMainTask == true && $noAssignedTask == true) {
    echo "No Results found.";
} else {
    // Display the main tasks
    if (!empty($searchResults)) {
        $currentTaskList = null;
        foreach ($searchResults as $result) {
            if ($currentTaskList !== $result['task_list_id']) {
                if ($currentTaskList !== null) {
                    echo "</ul>";
                }
                $currentTaskList = $result['task_list_id'];
                echo "<h3>" . htmlspecialchars($result['list_title']) . "</h3><ul>";
            }

            if (!empty($result['title'])) {
                echo "<li>" . htmlspecialchars($result['title']) . " - " . $result['status'] . " - " . $result['timestamp'] . "</li>";
            }
        }
        echo "</ul>";
    }

    // Display tasks assigned to the user
    if (!empty($assignedTasks)) {
        echo "<h3>Tasks Assigned to You:</h3><ul>";
        foreach ($assignedTasks as $task) {
            echo "<li>" . htmlspecialchars($task['title']) . " - " . $task['status'] . " - by " . htmlspecialchars($task['owner']) . "</li>";
        }
        echo "</ul>";
    }
}