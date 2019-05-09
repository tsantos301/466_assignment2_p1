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

if(isset($_GET['logout'])){
  session_destroy();
  unset($_SESSION['username']);
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
  <h1>Bookmarking Service.</h1>

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

    <h3>Welcome: <strong><?php
                            $username = $_SESSION['username'];
                            echo $username;

                          ?></strong></h3>

      <form action="index.php" method="post">
          <input type="text" name="urlSTRING" required/>
          <input type="submit" name="addLINK" value="+" />
      </form>

      <?php

      function addLink($db, $urlString, $username){

          $addQuery = "INSERT INTO Links (username, url )
                VALUES ( '$username' , '$urlString')";

          if ($db->query($addQuery) === FALSE) {
              echo "Error added record: " . $db->error;
          }
          unset($_POST);
          header("Location:index.php");
          // getList($db,$username);


      }


      function removeLink($db,$urlString,$username){
          $deleteQuery = "DELETE FROM Links WHERE url = '$urlString' AND username = '$username'";

          if ($db->query($deleteQuery) === FALSE) {
              echo "Error deleting record: " . $db->error;
          }
          getList($db,$username);
      }

      function getList($db,$username){
          $sql = "SELECT url FROM Links WHERE username = '$username'";
          $result = mysqli_query($db, $sql);
          //$result = mysqli_fetch_array($result);


          if (mysqli_num_rows($result) > 0) {
              // output data of each row
              while($row = mysqli_fetch_assoc($result)) {
                  $print = $row['url'];
                  $fullLink = "http://".$row['url'];
                  //echo "Link: <a>" . $row['url']. "</a><br>";
                  echo "<a target=\"_blank\" href='".$fullLink."'>$print</a><br>";
              }
          } else {
              echo "No links have been Bookmarked Yet";
          }
      }

      // Create connection
      $db = mysqli_connect('localhost','root','','Bookmarking') or die("could not connect to database");

      getList($db,$username);

      //if add button is clicked add a URL to the list
      if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['addLINK']) and $_POST['urlSTRING']!= NULL) {

          $urlString = $_POST['urlSTRING'];
          addLink($db,$urlString,$username);
          unset($_POST);

      }

      mysqli_close($db);


      ?>

      <!--    ~~~~~~~~~~~Logout ~~~~~~~~~~~-->

      <!--<button onclick="location.href="index.html?logout='1'">Logout</button>-->
      <!--    <button>Logout<a href="index.php?logout='1'" target="_blank"></a></button>-->
      <p><a href="index.php?logout='1'">Logout</a></p>





  <?php endif ?>




</body>
</html>
