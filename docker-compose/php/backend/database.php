<?php
$dbServer = "mysql"; // Use the service name defined in docker-compose.yml
$dbUser = "webuser"; // The user you defined
$dbPass = "webpass"; // The password you defined
$dbName = "di_internet_technologies_project"; // The database name you defineddbName = "trello";
$conn = "";

try {
    $conn = mysqli_connect($dbServer, $dbUser, $dbPass, $dbName);
    if (!$conn) {
        throw new Exception("Connection failed: " . mysqli_connect_error());
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

