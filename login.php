<?php
session_start();
include ('database.php');
include ("header.html");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="/trello/css/form.css" />
    </head>
    <body>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required>
        <br>
        <br>
        <input type="submit" name="login" value="Login">
        <br>
    </form>
    </body>
</html>


<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT username, password, email, firstName, lastName, simplepushKey FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user data
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start the session

            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['firstName'] = $user['firstName'];
            $_SESSION['lastName'] = $user['lastName'];
            $_SESSION['simplepushKey'] = $user['simplepushKey'];

            header("Location: index.php");
            exit;
        } else {
            // Invalid password
            echo "Wrong Password!";
        }
    } else {
        // User not found
        echo '<div class="statusMessage">Username not found</div>';
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();

?>

<!DOCTYPE html>
<html>
    <body>
        <form action="signup.php" method="POST">
            <br><br>
            <p>Don't have an account?</p>
            <input type="submit" value="Sign Up">
        </form>
    </body>
</html>
