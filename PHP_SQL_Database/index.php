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

    <h3 style = "text-align: left;">Welcome: <strong><?php
                            $username = $_SESSION['username'];
                            echo $username;

                          ?></strong></h3>

      <h1>Bookmarking Service.</h1>
      <form action="index.php" method="post">
          <label>Enter link to add:</label><br>
          <input type="text" name="urlSTRING" required/>
          <input type="submit" name="addLINK" value="+" />
      </form>
      <ul>
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

         mysqli_query($db,$deleteQuery);
//          if ($db->query($deleteQuery) === FALSE) {
//              echo "Error deleting record: " . $db->error;
//          }else{
//              echo "this should have worked";
//          }
          //getList($db,$username);
          //mysqli_close($db);
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
                  //<li>Agnes<span class="close">x</span></li>
                  echo "<li><span class=\"close\" onclick='pass_String()' >x</span><a target=\"_blank\" href='".$fullLink."'>$print</a></li>";
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

      echo isset($_POST['data']);
      //if delete button is clicked add a URL to the list .... data is the url string passed to be deleted.
      if(isset($_POST['data'])) {
          echo "do we get to this?69696";
          $urlDeleteString = $_POST['data'];
          removeLink($db,$urlDeleteString,$username);
          unset($_POST);

      }

      //removeLink($db,"www.apple.ca",$username);
      //mysqli_close($db);


      ?>
      </ul>
      <!--    ~~~~~~~~~~~Logout ~~~~~~~~~~~-->

      <!--<button onclick="location.href="index.html?logout='1'">Logout</button>-->
      <!--    <button>Logout<a href="index.php?logout='1'" target="_blank"></a></button>-->
      <p><a href="index.php?logout='1'">Logout</a></p>

      <script>
          function pass_String(urlString){
              console.log("this is pass string");
              var xhttp = new XMLHttpRequest();
              xhttp.open("POST", "index.php", true);
              xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
              xhttp.send("data="+urlString);
              location.reload();

          }
          /* Get all elements with class="close" */
          var closebtns = document.getElementsByClassName("close");
          var i;

          /* Loop through the elements, and hide the parent, when clicked on */
          for (i = 0; i < closebtns.length; i++) {
              closebtns[i].addEventListener("click", function () {
                  this.parentElement.style.display = 'none';
                  var urlString = this.parentElement.lastElementChild.innerHTML.toString();
                  console.log(urlString);
                  pass_String(urlString);

              });
          }
      </script>



  <?php endif ?>




</body>
</html>
