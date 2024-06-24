<?php
$dbServer = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "nature";
$conn = "";

try {
    $conn = mysqli_connect($dbServer, $dbUser, $dbPass, $dbName);
} catch (mysqli_sql_exception) {
    echo "ERROR: SQLI NOT CONNECTED";
}