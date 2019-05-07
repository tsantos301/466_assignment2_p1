<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Registration</title>
</head>

<body>
  <div class="container">
    <div class="header">
      <h2>Register</h2>
    </div>

    <!-- action word determines what will be run when you submit the form -->
    <form action="registration.php" method="post">
      <?php include('errors.php') ?>
      <!-- the for attribute is used in accesibility services -->
      <div>
        <label for="username"> Username: </label>
        <input type="text" name="username" required>
      </div>

      <div>
        <label for="email"> Email: </label>
        <input type="email" name="email" required>
      </div>

      <div>
        <label for="password"> Password: </label>
        <input type="password" name="password_1" required>
      </div>

      <div>
        <label for="password"> Confirm Password: </label>
        <input type="password" name="password_2" required>
      </div>

      <button type="submit" name="register_user"> Submit </button>

      <p> Already a user? <a href= "login.php" > Login Here </a></p>

  </div>
</body>

</html>