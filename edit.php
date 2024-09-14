<?php
include("database.php");
include("header.html");
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    <link rel="stylesheet" href="css/edit.css" />
</head>
<h2>Update your Information: </h2>
<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
    <label for="firstName">First name:</label>
    <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($_SESSION['firstName']); ?>"
        required>
    <label for="lastName">Last name:</label>
    <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($_SESSION['lastName']); ?>"
        required>
    <br><br>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>"
        required>
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>
    <br><br>
    <label for="simplepushKey">Simplepush.io key:</label>
    <input type="text" id="simplepushKey" name="simplepushKey"
        value="<?php if (isset($_SESSION['simplepushKey'])) {echo htmlspecialchars($_SESSION['simplepushKey']); }?>">
    <br><br>
    <label for="passwordNew">(Optional) Make a new password:</label>
    <input type="password" id="passwordNew" name="passwordNew">
    <br><br><br>
    <label for="password">Enter your password to update:</label>
    <input type="password" id="password" name="password">
    <br><br>
    <input type="submit" name="action" value="cancel">
    <input type="submit" name="action" value="apply">
    <br>
    <br><br>
    <br><br>
    <br><br>
    <label for="delete" style="color: #9e9999; font-size: 22px;">Delete your account permanently:</label>
    <input type="submit" name="action" style="background-color: gray; border-radius: 11px; width: 120px; height: 40px;"
        value="delete">
    <br><br>
</form>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($action == "apply") {
        $firstName = filter_input(INPUT_POST, "firstName", FILTER_SANITIZE_SPECIAL_CHARS);
        $lastName = filter_input(INPUT_POST, "lastName", FILTER_SANITIZE_SPECIAL_CHARS);
        $usernameNew = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $passwordNew = filter_input(INPUT_POST, "passwordNew", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $simplepushKey = filter_input(INPUT_POST, "simplepushKey", FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($username)  || empty($firstName) || empty($lastName) || empty($email)) {
            echo '<div class="statusMessage">Please fill in all the required boxes!</div>';
        } else if (empty($password)){
                echo '<div class="statusMessage">Please fill in the password</div>';
        } else {
            if (password_verify($password, $user['password'])) {
                $passwordHash = "";
                if ($passwordNew != "") {
                    $passwordHash = password_hash($passwordNew, PASSWORD_DEFAULT);
                } else {
                    $passwordHash = $user['password'];
                }

                $conn->begin_transaction();
                try {

                    $stmt1 = $conn->prepare("UPDATE tasks SET owner = ? WHERE owner = ?");
                    $stmt1->bind_param("ss", $usernameNew, $username);
                    $stmt1->execute();

                    $stmt3 = $conn->prepare("UPDATE tasks SET assigned = ? WHERE assigned = ?");
                    $stmt3->bind_param("ss", $usernameNew, $username);
                    $stmt3->execute();

                    $stmt2 = $conn->prepare("UPDATE taskLists SET username = ? WHERE username = ?");
                    $stmt2->bind_param("ss", $usernameNew, $username);
                    $stmt2->execute();

                    $stmt4 = $conn->prepare("UPDATE users SET firstName = ?, lastName = ?, username = ?, password = ?, email = ?, simplepushKey = ? WHERE username = ?");
                    $stmt4->bind_param("sssssss", $firstName, $lastName, $usernameNew, $passwordHash, $email, $simplepushKey, $username);
                    $stmt4->execute();

                    // Commit the transaction if everything succeeds
                    $conn->commit();
                } catch (Exception $e) {
                    // Rollback transaction on error
                    $conn->rollback();
                    echo "Failed to update tasks: " . $e->getMessage();
                }

                if (isset($stmt1))
                    $stmt1->close();
                if (isset($stmt2))
                    $stmt2->close();
                if (isset($stmt3))
                    $stmt3->close();
                if (isset($stmt4))
                    $stmt4->close();


                $_SESSION['username'] = $usernameNew;
                $_SESSION['firstName'] = $firstName;
                $_SESSION['lastName'] = $lastName;
                $_SESSION['email'] = $email;
                $_SESSION['simplepushKey'] = $simplepushKey;
                $_SESSION['update_success'] = "Information updated successfully.";

                header("Location: index.php");
            } else {
            echo '<div class="statusMessage">Incorrect password</div>';
            }
        }
    } else if ($action == "cancel") {
        // If HTTP_REFERER is not set, redirect to a default page
        echo "CANCEL";
        header("Location: index.php");
        exit;
    } else if ($action == "delete") {
        if (password_verify($password, $user['password'])) {
            $randomWords = [];
            $wordNum = 5;
            for ($i = 0; $i < $wordNum; $i++) {
                $randomWords[$i] = getRandomWord();
            }
            $randomPass = password_hash($randomWords[3], PASSWORD_DEFAULT);
            $conn->begin_transaction();
            try {

                $stmt1 = $conn->prepare("UPDATE tasks SET owner = ? WHERE owner = ?");
                $stmt1->bind_param("ss", $randomWords[2], $username);
                $stmt1->execute();

                $stmt3 = $conn->prepare("UPDATE tasks SET assigned = ? WHERE assigned = ?");
                $stmt3->bind_param("ss", $randomWords[2], $username);
                $stmt3->execute();

                $stmt2 = $conn->prepare("UPDATE taskLists SET username = ? WHERE username = ?");
                $stmt2->bind_param("ss", $randomWords[2], $username);
                $stmt2->execute();

                $randomWords[4] = $randomWords[4] . "@" . getRandomWord();
                $null = null;
                $stmt4 = $conn->prepare("UPDATE users SET firstName = ?, lastName = ?, username = ?, password = ?, email = ?, simplepushKey = ? WHERE username = ?");
                $stmt4->bind_param("sssssss", $randomWords[0], $randomWords[1], $randomWords[2], $randomPass, $randomWords[4], $null, $username);
                $stmt4->execute();
                // Commit the transaction if everything succeeds
                $conn->commit();
            } catch (Exception $e) {
                // Rollback transaction on error
                $conn->rollback();
                echo "Failed to update tasks: " . $e->getMessage();
            }
            if (isset($stmt1))
                $stmt1->close();
            if (isset($stmt2))
                $stmt2->close();
            if (isset($stmt3))
                $stmt3->close();
            if (isset($stmt4))
                $stmt4->close();

            // Update the session variables with new values
            header("Location: logout.php");
            exit;
        } else {
            if (!empty($password)){
            echo '<div class="statusMessage">Incorrect password</div>';
            } else{
                echo '<div class="statusMessage">Please fill in the password</div>';
            }
        }
    } else {
        "error in stmt" . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}


//RETURNS RANDOM WORD
function getRandomWord()
{
    $api_url = 'https://random-word-api.herokuapp.com/word?number=1';
    $response = file_get_contents($api_url);
    $words = json_decode($response);
    return $words[0];
}
?>