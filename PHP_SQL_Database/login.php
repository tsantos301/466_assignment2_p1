<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>

<body>
  <div class="container">
    <div class="header">
      <h2>Login</h2>
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

        <button type="submit" name="login_user" > Login </button>
<!--        <a type="submit" name="login_user" onclick = "location.href='index.html'"> Login </a>-->
<!--        <p><a href="index.php">Login</a></p>-->

        <?php include('server.php') ?>
      <p> Dont have an account? <a href="registration.php">Create One Here.</a></p>

  </div>
</body>

</html>
