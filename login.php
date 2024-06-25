<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
    </head>
    <body>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit" value="Login">
        <br>
    </form>
    </body>
</html>


<?php
session_start();
include 'database.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ?");
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
            $_SESSION['user_id'] = $user['id'];
            header("Location: home.php");
            exit;
        } else {
            // Invalid password
            echo "Wrong Password!";
        }
    } else {
        // User not found
        echo "Username not found!";
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