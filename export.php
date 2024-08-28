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

// Fetch task lists and tasks owned by the user
$query = "
    SELECT tl.task_list_id, tl.list_title, t.title, t.assigned, t.status, t.id
    FROM taskLists tl 
    LEFT JOIN tasks t ON tl.task_list_id = t.task_list_id 
    WHERE tl.username = ? 
    ORDER BY tl.timestamp DESC, t.timestamp DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();
$taskLists = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch tasks assigned directly to the user
$query = "
    SELECT title 
    FROM tasks 
    WHERE assigned = ? 
    ORDER BY timestamp DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();
$assignedTasks = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();

// Generate XML
$dom = new DOMDocument('1.0', 'UTF-8');
$dom->formatOutput = true;

// Root element
$root = $dom->createElement('UserTasks');
$dom->appendChild($root);

// Username element
$userElement = $dom->createElement('Username', htmlspecialchars($username));
$root->appendChild($userElement);

// Task lists and their tasks
if (!empty($taskLists)) {
    $taskListsElement = $dom->createElement('TaskLists');
    $root->appendChild($taskListsElement);

    $currentTaskList = null;
    foreach ($taskLists as $taskList) {
        if ($currentTaskList !== $taskList['task_list_id']) {
            $currentTaskList = $taskList['task_list_id'];

            $taskListElement = $dom->createElement('TaskList');
            $taskListElement->setAttribute('id', $currentTaskList);
            $taskListElement->setAttribute('title', htmlspecialchars($taskList['list_title']));

            $taskListsElement->appendChild($taskListElement);
        }

        if (!empty($taskList['title'])) {
            // Default to empty string if null

            $taskTitle = $taskList['title'] ?? '';
            $taskStatus = $taskList['status'] ?? '';
            $taskAssigned = $taskList['assigned'] ?? '';

            $taskElement = $dom->createElement('Task', htmlspecialchars($taskTitle));
            $taskElement->setAttribute("id", $taskList['id']);
            $taskElement->setAttribute("status", htmlspecialchars($taskStatus));
            $taskElement->setAttribute("assigned", htmlspecialchars($taskAssigned));
            $taskListElement->appendChild($taskElement);
        }
    }
}

// Directly assigned tasks
if (!empty($assignedTasks)) {
    $assignedTasksElement = $dom->createElement('AssignedTasks');
    $root->appendChild($assignedTasksElement);

    foreach ($assignedTasks as $task) {
        $taskElement = $dom->createElement('Task', htmlspecialchars($task['title']));
        $assignedTasksElement->appendChild($taskElement);
    }
}

// Set headers to download as an XML file
header('Content-Type: application/xml; charset=UTF-8');
header('Content-Disposition: attachment; filename="user_tasks.xml"');

// Output the XML content
echo $dom->saveXML();
exit;
?>