<?php
include ("database.php");
include ("header.html");
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update your information</title>
</head>
<body>
    <h2>Update your Information: </h2>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
        <label for="firstName">First name:</label>
        <input type="text" id="firstName" name="firstName"
            value="<?php echo htmlspecialchars($_SESSION['firstName']); ?>" required>
        <label for="lastName">Last name:</label>
        <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($_SESSION['lastName']); ?>"
            required>
        <br>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>"
            required>
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>
        <br>
        <label for="simplepushKey">Simplepush.io key:</label>
        <input type="password" id="simplepushKey" name="simplepushKey"
            value="<?php echo htmlspecialchars($_SESSION['simplepushKey']); ?>">
        <br>
        <label for="passwordNew">(Optional) Make a new password:</label>
        <input type="password" id="passwordNew" name="passwordNew">
        <br><br>
        <label for="password">Enter your password to update:</label>
        <input type="password" id="password" name="password">
        <input type="submit" name="action" value="cancel">
        <input type="submit" name="action" value="apply">
        <br><br>
    </form>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == "apply") {
        $firstName = filter_input(INPUT_POST, "firstName", FILTER_SANITIZE_STRING);
        $lastName = filter_input(INPUT_POST, "lastName", FILTER_SANITIZE_STRING);
        $usernameNew = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
        $passwordNew= filter_input(INPUT_POST, "passwordNew", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $simplepushKey = filter_input(INPUT_POST, "simplepushKey", FILTER_SANITIZE_SPECIAL_CHARS);
        $username = $_SESSION['username'];


        if (empty($username) || empty($password) || empty($firstName) || empty($lastName) || empty($email)) {
            echo "Please fill in all the required boxes!";
        } else {
            $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $passwordHash = password_hash($passwordNew, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET firstName = ?, lastName = ?, username = ?, password = ?, email = ?, simplepushKey = ? WHERE username = ?");
                $stmt->bind_param("sssssss", $firstName, $lastName, $usernameNew, $passwordHash, $email, $simplepushKey, $username);
                if ($stmt->execute()) {
                    // Update the session variable if username is changed
                    $_SESSION['username'] = $usernameNew;
                    $_SESSION['firstName'] = $firstName;
                    $_SESSION['lastName'] = $lastName;
                    $_SESSION['email'] = $email;
                    $_SESSION['simplepushKey'] = $simplepushKey;
                    $_SESSION['update_success'] = "Information updated successfully.";

                    header("Location: home.php");
                } else {
                    echo "Error updating user details: " . $stmt->error;
                }
            } else {
                echo "Incorrect password!";
            }
        }
    } else if ($action == "cancel") {
        // If HTTP_REFERER is not set, redirect to a default page
        echo "CANCEL";
        header("Location: home.php");
        exit;
    }
}
?>