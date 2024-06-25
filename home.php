<?php
session_start();
include ("database.php");

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
    echo "Welcome, " . $_SESSION['username'];

    $sql = "SELECT simplepushkey FROM users WHERE username = '{$_SESSION['username']}'";
    try {
        $result = mysqli_query($conn, $sql);
        $resultKey = mysqli_fetch_assoc($result);
        $key = $resultKey['simplepushkey'];
    } catch (mysqli_sql_exception $e) {
        echo "sql query messed up" . $e->getMessage() . "";
    }

    if ($key) {
        $title = "NEW TASK";
        $message = "A USER HAS STARTED A NEW TASK";
        // Simplepush::send($key, $title, $message);
    } else {
        echo "<br><br>(you have no notification key)";
    }
}

    ?>
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







class Simplepush
{
    const API = 'https://api.simplepush.io/send';
    const SALT_COMPAT = '1789F0B8C4A051E5';

    public static function send($key, $title, $message, $event = null)
    {
        if (!isset($key) || !isset($message)) {
            return FALSE;
        }

        $payload = self::generate_payload($key, $title, $message, $event);

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($payload)
            )
        );
        $context = stream_context_create($options);
        return file_get_contents(self::API, false, $context);
    }

    public static function send_encrypted($key, $password, $salt, $title, $message, $event)
    {
        if (!isset($key) || !isset($message) || !isset($password)) {
            return FALSE;
        }

        // Compatibility with old clients
        if (!isset($salt)) {
            $salt = self::SALT_COMPAT;
        }

        $payload = self::generate_payload($key, $title, $message, $event, $password, $salt);

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($payload)
            )
        );
        $context = stream_context_create($options);
        return file_get_contents(self::API, false, $context);
    }

    private static function generate_payload($key, $title, $message, $event, $password = null, $salt = null)
    {
        $payload = array('key' => $key);

        if (isset($event)) {
            $payload['event'] = $event;
        }

        if (!isset($password)) {
            if (isset($title)) {
                $payload['title'] = $title;
            }

            $payload['msg'] = $message;
        } else {
            $encryption_key = self::generate_encryption_key($password, $salt);
            $iv = self::generate_iv();
            $iv_hex = bin2hex($iv);

            $payload['encrypted'] = 'true';
            $payload['iv'] = $iv_hex;

            if (isset($title)) {
                $title = self::encrypt($encryption_key, $iv, $title);
                $payload['title'] = $title;
            }

            $message = self::encrypt($encryption_key, $iv, $message);
            $payload['msg'] = $message;
        }

        return $payload;
    }

    private static function generate_iv()
    {
        return random_bytes(16);
    }

    private static function generate_encryption_key($password, $salt)
    {
        $hex_string = substr(sha1($password . $salt), 0, 32);
        return hex2bin($hex_string);
    }

    private static function encrypt($encryption_key, $iv, $data)
    {
        $encrypted = openssl_encrypt($data, "aes-128-cbc", $encryption_key, OPENSSL_RAW_DATA, $iv);
        return self::base64_url_encode($encrypted);
    }

    private static function base64_url_encode($input)
    {
        return strtr(base64_encode($input), '+/=', '-_,');
    }
}