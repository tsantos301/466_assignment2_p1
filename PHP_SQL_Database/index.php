<!-- to access this page http://192.168.64.2/PHP_SQL_Database/index.php -->
<?php include 'navigation.php'; ?>
<?php
error_reporting(E_ERROR | E_PARSE);

session_start();
//~~~~~~~~~REDIRECTS~~~~~~~~~~~~~~~~
//trying to access page witbout being logged in

if(isset($_SESSION['username'])==FALSE){
  $_SESSION['msg'] = "You must login to view this page";
  header("Location: login.php");
}

if(isset($_GET['logout'])){
  session_destroy();
  unset($_SESSION['username']);
  header("Location: login.php");
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

function addLink($db, $urlString, $username){

    $addQuery = "INSERT INTO Links (username, url )
                VALUES ( '$username' , '$urlString')";

    if ($db->query($addQuery) === FALSE) {
        echo "Error adding record: " . $db->error;
    }
    unset($_POST);
    header("Location:index.php");
}


function removeLink($db,$urlString,$username){
    $deleteQuery = "DELETE FROM Links WHERE url = '$urlString' AND username = '$username' LIMIT 1";

          if ($db->query($deleteQuery) === FALSE) {
              echo "Error deleting record: " . $db->error;
          }
}

function editLink($db,$oldURL,$newURL,$username){
    $editQuery = "UPDATE Links SET url = '$newURL' WHERE url ='$oldURL' AND username = '$username'";
    mysqli_query($db,$editQuery);
}

function getList($db,$username){
    $sql = "SELECT url FROM Links WHERE username = '$username'";
    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $print = $row['url'];
            $fullLink = $row['url'];
            echo "<li class='links'><button id='editButton' class=\"edit\" >|||</button><span class=\"close\" >x</span><a id='editContent' class = 'linkText' target=\"_blank\" href=".$fullLink." contentEditable = 'false'>$print</a></li>"; // because of this line
        }
    } else {
        echo "No links have been Bookmarked Yet";
    }
}

?>

<!DOCTYPE html>
<html>
<link href="https://fonts.googleapis.com/css?family=Great+Vibes|Pacifico|Shadows+Into+Light" rel="stylesheet">
<head>
    <!--Prevent caching from happening so that the list displays correctly-->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Homepage</title>
    <style>
        <?php include 'CSS/main.css';?>
    </style>
</head>

<body>

  <?php if(isset($_SESSION['success'])) : ?>

  <div>
      <h3>
        <?php
        unset($_SESSION['success']);
        ?>
      </h3>
  </div>
  <?php endif ?>

<!-- if the user logs in print information about them -->

  <?php if(isset($_SESSION['username'])) : ?>

    <h3 style = "text-align: left;">Hello, <strong><?php
                            $username = $_SESSION['username'];
                            echo $username;

                          ?></strong>.</h3>
      <form action="index.php" method="post">
          <label>Enter link to add:</label><br>
          <input type="url" name="urlSTRING" required/>
          <input type="submit" name="addLINK" value="+" />
      </form>
      <ul>
      <?php

      //main logic:
      // Create connection
      $db = mysqli_connect('localhost','root','Allen07150794y','Bookmarking') or die("could not connect to database");


      //If the add button was clicked add a URL to the list on refresh
      if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['addLINK']) and $_POST['urlSTRING']!= NULL) {
          $urlString = $_POST['urlSTRING'];
          addLink($db,$urlString,$username);
          unset($_POST);
      }

      //If delete button is clicked remove a URL from the list on refresh .... data is the url string passed to be deleted.
      if(isset($_POST['data'])) {
          $urlDeleteString = $_POST['data'];
          removeLink($db,$urlDeleteString,$username);
          unset($_POST);
      }

      $testing = isset($_POST['newlink']);
      //If edit button is saved edit the URL from the list on refresh
      if(isset($_POST['newlink'])) {
          $newURL = $_POST['newlink'];
          $oldURL = $_POST['originalLink'];
          editLink($db,$oldURL,$newURL,$username);
          unset($_POST);
      }

      //Display the links
      getList($db,$username);

      ?>
      </ul>

      <script>
          function pass_String(remove,urlString,originalString){

              var xhttp = new XMLHttpRequest();
              xhttp.open("POST", "index.php", false);
              xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
              if(remove==true) { //Deleting a link
                  xhttp.send("data=" + urlString);
              }else{ //editing a link

                  if(isURL(urlString)==false){
                      if(alert('Invalid URL, Please enter a valid URL')){}
                      else    window.location.reload();
                      editable.contentEditable = "true";
                      document.getElementById("editButton").innerHTML = "Editing";
                      document.getElementById("editButton").style.backgroundColor='#f44242';
                      return;
                  }
                   xhttp.send("newlink=" + urlString +"&originalLink=" + originalString);
              }
              location.reload();
          }

          function editPressed(button) {
              var x = document.getElementById("editContent");
              if (x.contentEditable == "true") { //second click
                  x.contentEditable = "false";
                  button.innerHTML = "|||";
                  button.style.background='#f6f6f6';

              } else { //first click
                  x.contentEditable = "true";
                  button.innerHTML = "Editing";
                  button.style.backgroundColor='#f44242';
              }
          }

          function isURL(str) {
              var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
              if(!regex .test(str)) {
                  return false;
              } else {
                  return true;
              }
          }

          /* Get all elements with class="close" */
          var closebtns = document.getElementsByClassName("close");
          var i;

          /* Loop through the elements, and hide the parent, when clicked on */
          for (i = 0; i < closebtns.length; i++) {
              closebtns[i].addEventListener("click", function () {
                  this.parentElement.style.display = 'none';
                  var urlString = this.parentElement.lastElementChild.innerHTML.toString();
                  pass_String(true,urlString);
              });
          }

          //get the original link value
          /* Get all elements with class="edit" */
          var editbtns = document.getElementsByClassName("edit");
          var j;
          var originalLink;
          var newLink;
          var editMode;
          let editable;

          /* Loop through the elements and get orginal link value*/
          for (j = 0; j < editbtns.length; j++) {
              editbtns[j].addEventListener("click", function () {

                  var link = this.parentElement.lastElementChild;
                  if(link.contentEditable == "false") {
                      link.contentEditable = "true";
                      this.innerHTML = "Editing";
                      this.style.backgroundColor='#f44242';
                      originalLink = this.parentElement.lastElementChild.innerHTML.toString();
                  }else{
                      link.contentEditable = "false";
                      this.innerHTML = "|||";
                      this.style.backgroundColor='#f6f6f6';
                      newLink = this.parentElement.lastElementChild.innerHTML.toString();
                      pass_String(false,newLink,originalLink);
                  }

              });
          }
      </script>

      <!--~~~~~~~~~~~Logout ~~~~~~~~~~~-->
      <p><a href="index.php?logout='1'">Logout</a></p>


  <?php endif ?>




</body>
</html>
