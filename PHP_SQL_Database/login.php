<?php include 'navigation.php'; ?>
<!DOCTYPE html>
<html>
<link href="https://fonts.googleapis.com/css?family=Great+Vibes|Pacifico|Shadows+Into+Light" rel="stylesheet">
<head>
    <style>
        <?php include 'CSS/main.css'; ?>
    </style>
    <title>Login</title>
</head>

<body>
  <div class="container">
    <div class="header">
      <h2><u>Login</u></h2>
    </div>

    <!-- action word determines what will be run when you submit the form -->
    <form action="login.php" method="post">

      <!-- the for attribute is used in accesibility services -->
      <div>
        <label for="username"> Username: </label>
        <input type="text" name="username" required>
      </div>

      <div>
        <label for="password"> Password: </label>
        <input type="password" name="password_1" required>
      </div>

        <button class="bigButton" type="submit" name="login_user" > Login </button>

        <?php include('server.php') ?>
      <p> Dont have an account? <a href="registration.php">Create One Here.</a></p>
	</form>
  </div>
</body>

</html>
