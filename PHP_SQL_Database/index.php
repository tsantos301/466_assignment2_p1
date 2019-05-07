<!-- to access this page http://192.168.64.2/PHP_SQL_Database/index.php -->

<?php
//error_reporting(E_ERROR | E_PARSE);

session_start();
//~~~~~~~~~REDIRECTS~~~~~~~~~~~~~~~~
//trying to access page witbout being logged in

if(isset($_SESSION['username'])==0){
  $_SESSION['msg'] = "You must login to view this page";
  header("Location: login.php");
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
?>

<!DOCTYPE html>
<html>
<link href="https://fonts.googleapis.com/css?family=Great+Vibes|Pacifico|Shadows+Into+Light" rel="stylesheet">
<head>
  <title>Homepage</title>
    <style>
        <?php include 'CSS/main.css'; ?>
    </style>
</head>

<body>
  <h1>This is the homepage.</h1>

  <?php if(isset($_SESSION['success'])) : ?>

  <div>
      <h3>
        <?php
        echo $_SESSION['success'];
        unset($_SESSION['success']);
        ?>
      </h3>
  </div>
  <?php endif ?>

<!-- if the user logs in print information about them -->

  <?php if(isset($_SESSION['username'])) : ?>

    <h3>Welcome: <strong><?php echo $_SESSION['username']; ?></strong></h3>
      <!--<button onclick="location.href="index.html?logout='1'">Logout</button>-->
      <!--    <button>Logout<a href="index.php?logout='1'" target="_blank"></a></button>-->
      <p><a href="index.php">Logout</a></p>
      <?php
        session_destroy();
        unset($_SESSION['username']);
      ?>
  <?php endif ?>


</body>
</html>
