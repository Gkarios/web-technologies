<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Trello</title>
    </head>
</html>

<?php
session_start();
include ("database.php");
include ("header.html");
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "HOME PAGE <br>";

if (!isset($_SESSION["username"])) {
    echo "You are not logged in <br>";


    ?>
    <!DOCTYPE html>
    <html lang="en">

    <body>
        <form action="login.php" method="POST">
            <br>
            <button type="submit">Log In</button>
        </form>
    </body>

    </html>

    <?php
} else {
    echo "Welcome, " . $_SESSION['username'] . "<br> <br>";
    if (isset($_SESSION['update_success'])) {
        echo "<h1>" . $_SESSION['update_success'] . "</h1>";
        // Clear the success message to prevent it from displaying on subsequent visits
        unset($_SESSION['update_success']);
    }

    echo "Edit your account: "
    ?>

        <html lang="en">
    <body>
        <form action="edit.php" method="POST">
            <br>
            <button type="submit">Edit</button>
        </form>
    </body>

    <p>Search</p>
        <form action="results.php?search_query=" method="POST">
            <button type="submit">Search</button>
        </form>
    </p>Manage your task list</p>
        <form action="tasks.php" method="POST">
            <button type="submit">Manage Tasks</button>
        </form>
    <br>
        <form action="export.php" method="POST">
            <button type="submit">Export XML</button>
        </form>
</html>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
    </head>

    <body>
        <form action="logout.php" method="POST">
            <br>
            <button type="submit">Logout</button>
        </form>
    </body>

    </html>
<?php
}?>