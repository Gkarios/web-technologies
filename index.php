<!DOCTYPE html>
<html lang="en">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Trello</title>
    <link rel="stylesheet" href="/trello/css/homepage.css"/>
</html>

<?php
session_start();
include ("backend/database.php");
include ("header.html");
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!isset($_SESSION["username"])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <body>
    <div class="mid-section">
      <img src="images/done.png" class="tasklist" />
      <div class="mid-txt">
        <h1>
          Organize projects<br />and collaborate<br />with ease and efficiency
        </h1>
        <p>
          Effortlessly manage projects from start to finish,<br />
          streamline tasks, and keep your team aligned,<br />
          all while staying on top of deadlines and goals.
        </p>
        <div class="acc-btns">
          <form action="signup.php" method="POST">
            <button class="start">Get Started</button>
          </form>
          <form action="login.php" method="POST">
            <button class="login">Log in</button>
          </form>
        </div>
      </div>
      <img src="images/business-network.png" class="team" />
      <div class="frame"><img src="images/woman.png" class="person" /></div>
      <img src="images/to-do-list.png" class="tasklist2" />
    </div>
    </body>

    </html>

    <?php
} else {
  echo "<h2>Welcome, " . $_SESSION['username'] . "</h2>";
  if (isset($_SESSION['update_success'])) {
        echo "<h2>" . $_SESSION['update_success'] . "</h2>";
        // Clear the success message to prevent it from displaying on subsequent visits
        unset($_SESSION['update_success']);
    }
        ?>
<html>
  <head>
    <link rel = "stylesheet" href = "css/registered.css">
    <script src="css/theme.js"></script>
    <body>
    <div id="account-menu">
    <img id="theme-toggle" src="images/moon.png" title="Enter dark mode" />
      <h2>Account Options</h2>
      <a href="/trello/edit.php">Edit your account</a>
      <a href="/trello/tasks.php">Manage your tasks</a>
      <a href="/trello/backend/export.php">Export to XML</a>
      <a href="/trello/logout.php">Log out</a>
    </div>
    </body>
    <script>
      document.getElementById('theme-toggle').addEventListener('click', toggleTheme);
    </script>
</html>
<?php
}?>
