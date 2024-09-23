<?php
session_start();
include("backend/database.php");
ob_start();
include("header.html");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>SIGN UP</title>
    <link rel="stylesheet" href="css/form.css" />
</head>

<body>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        first name:<br>
        <input type="text" name="firstName"><br>
        last name:<br>
        <input type="text" name="lastName"><br>
        username:<br>
        <input type="text" name="username"><br>
        password:<br>
        <input type="password" name="password"><br>
        email:<br>
        <input type="email" name="email"><br>
        notification key:<br>
        <input type="text" name="simplepushKey"><br><br>
        <input type="submit" name="signup" value="Sign up">
    </form>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
    $firstName = filter_input(INPUT_POST, "firstName", FILTER_UNSAFE_RAW);
    $lastName = filter_input(INPUT_POST, "lastName", FILTER_UNSAFE_RAW);
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $simplepushKey = filter_input(INPUT_POST, "simplepushKey", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($username) || empty($password) || empty($firstName) || empty($lastName) || empty($email)) {
        echo '<div class="statusMessage">Please fill in all the required boxes</div>';
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (firstName, lastName, username, password, email, simplepushKey) VALUES ('$firstName', '$lastName', '$username', '$passwordHash', '$email', '$simplepushKey')";


        try {
            mysqli_query($conn, $sql);
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['firstName'] = $firstName;
            $_SESSION['lastName'] = $lastName;
            header("Location: index.php");
            exit;
        } catch (mysqli_sql_exception $e) {
            echo '<div class="statusMessage">That username has been taken</div>';
        }
        try {
            $_SESSION['simplepushKey'] = $simplepushKey;
        } catch (mysqli_sql_exception $e) {
            echo '<div class="statusMessage">The SimplePushKey is already being used</div>';
        }
    }
}

mysqli_close($conn);
ob_end_flush();
?>

<!DOCTYPE html>
<html>

<body>
    <form action="login.php" method="POST">
        <p>Already have an account?</p>
        <input type="submit" value="Log In">
    </form>
</body>

</html>
