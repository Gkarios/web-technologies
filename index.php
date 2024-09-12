<!DOCTYPE html>
<html lang="en">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Trello</title>
    <link rel="stylesheet" href="/trello/css/homepage.css"/>
</html>

<?php
session_start();
include ("database.php");
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
        <form action="signup.php" method="POST">
        <button class="start">Get Started</button>
</form>
      </div>
      <img src="images/business-network.png" class="team" />
      <div class="frame"><img src="images/woman.png" class="person" /></div>
      <img src="images/to-do-list.png" class="tasklist2" />
    </div>
      <div class="accountBtns">
        <h1>Already have an account?</h1>
        <form action="login.php" method="POST">
        <button class="acc" style="font-size:34px;">Log in</button>
</form>
      </div>
    </body>

    </html>

    <?php
} else {
    echo "<h2 style='font-size: 24px; border-color: #007bff; color: #fff; border: 1px solid $007bff; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;'>Welcome, " . $_SESSION['username'] . "</h2>";
    if (isset($_SESSION['update_success'])) {
        echo "<h1>" . $_SESSION['update_success'] . "</h1>";
        // Clear the success message to prevent it from displaying on subsequent visits
        unset($_SESSION['update_success']);
    }
        ?>
<html>
    <a style='font-size:24px; font-weight:bold; background-color: #007bff; color: #fff; border: 1px solid #007bff; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;' href="/trello/edit.php">Edit your account</a>
    <a style='font-size:24px; font-weight:bold; background-color: #007bff; color: #fff; border: 1px solid #007bff; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;' href="/trello/tasks.php">Manage your tasks</a>
    <a style='font-size:24px; font-weight:bold; background-color: #007bff; color: #fff; border: 1px solid #007bff; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;' href="/trello/export.php">Export to XML</a>
    <a style='font-size:24px; font-weight:bold; background-color: #007bff; color: #fff; border: 1px solid #007bff; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;' href="/trello/logout.php">Log out</a>
</html>
<?php
}?>