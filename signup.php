<?php
include ("database.php");
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>SIGN UP</title>
</head>

<body>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <h2>WELCOME TO TRELLO RIPOFF</h2>
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
        simplepush.io key<br>
        <input type="text" name="simplepushKey"><br>
        <input type="submit" name="register" value="register">
    </form>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = filter_input(INPUT_POST, "firstName", FILTER_SANITIZE_STRING);
    $lastName = filter_input(INPUT_POST, "lastName", FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $simplepushKey = filter_input(INPUT_POST, "simplepushKey", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($username) || empty($password) || empty($firstName) || empty($lastName) || empty($email)) {
        echo "Please fill in all the required boxes!";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (firstName, lastName, username, password, email, simplepushKey) VALUES ('$firstName', '$lastName', '$username', '$passwordHash', '$email', '$simplepushKey')";
        
        $_SESSION["username"] = $username;

        try {
            mysqli_query($conn, $sql);
            header("Location: /trello/home.php");
            exit;
        } catch (mysqli_sql_exception $e) {
            echo "THAT USERNAME HAS BEEN TAKEN B.";
        }
    }
}

mysqli_close($conn);
?>