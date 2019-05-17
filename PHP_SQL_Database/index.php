<!-- to access this page http://192.168.64.2/PHP_SQL_Database/index.php -->
<?php include 'navigation.php'; ?>
<?php
//error_reporting(E_ERROR | E_PARSE);

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
        echo "Error added record: " . $db->error;
    }
    unset($_POST);
    header("Location:index.php"); // failed to do this
    // getList($db,$username);
}


function removeLink($db,$urlString,$username){
    echo "delete";
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

function editLink($db,$oldURL,$newURL,$username){
    echo "edit link run";
    $editQuery = "UPDATE Links SET url = '$newURL' WHERE url ='$oldURL' AND username = '$username'";
    mysqli_query($db,$editQuery);
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
            echo "<li class='links'><button id='editButton' class=\"edit\" >|||</button><span class=\"close\" >x</span><a id='editContent' class = 'linkText' target=\"_blank\" href=".$fullLink." contentEditable = 'false'>$print</a></li>"; // because of this line
        }
    } else {
        echo "No links have been Bookmarked Yet";
    }
}


echo var_dump($_POST);
echo $_POST['data'];
echo isset($_POST['data']);

?>

<!DOCTYPE html>
<html>
<link href="https://fonts.googleapis.com/css?family=Great+Vibes|Pacifico|Shadows+Into+Light" rel="stylesheet">
<head>
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
        //echo $_SESSION['success'];
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
<!--      <h1><u>Bookmarking Service.</u></h1>-->
      <form action="index.php" method="post">
          <label>Enter link to add:</label><br>
          <input type="text" name="urlSTRING" required/>
          <input type="submit" name="addLINK" value="+" />
      </form>
      <ul>
      <?php

      //main logic:
      // Create connection
      $db = mysqli_connect('localhost','root','','Bookmarking') or die("could not connect to database");


      //If the add button was clicked add a URL to the list on refresh
      if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['addLINK']) and $_POST['urlSTRING']!= NULL) {
          $urlString = $_POST['urlSTRING'];
          echo "add";
          addLink($db,$urlString,$username);
          unset($_POST);
      }

      //If delete button is clicked remove a URL from the list on refresh .... data is the url string passed to be deleted.
      if(isset($_POST['data'])) {
          echo "delete";
          $urlDeleteString = $_POST['data'];
          removeLink($db,$urlDeleteString,$username);
          unset($_POST);
          //header("Location: login.php");
      }

      $testing = isset($_POST['newlink']);
      //echo "hello";
      echo $_POST['newlink'];

      //If edit button is saved edit the URL from the list on refresh
      if(isset($_POST['newlink'])) {
          echo "yelloe";;
          $newURL = $_POST['newlink'];
          $oldURL = $_POST['originalLink'];
          //addLink($db,$newURL,$username);
          editLink($db,$oldURL,$newURL,$username);
          unset($_POST);
          //header("Location: login.php");
      }

      //Display the links
      getList($db,$username);

      //Do I need to do this?
      //mysqli_close($db);


      ?>
      </ul>


      <script>

          function pass_String(remove,urlString,originalString){
              console.log("this is pass string");


              var xhttp = new XMLHttpRequest();

              xhttp.open("POST", "index.php", true);
              xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
              console.log("before if");
              if(remove==true) { //Deleting a link
                  xhttp.send("data=" + urlString);
              }else{ //editing a link
                   console.log("should come here for editing");
                   console.log(urlString);
                   console.log(originalString);

                  // if(isURL(urlString)==false){
                  //     alert("Invalid URL, Please enter a valid URL");
                  //     editable.contentEditable = "true";
                  //     document.getElementById("editButton").innerHTML = "Editing";
                  //     document.getElementById("editButton").style.backgroundColor='#f44242';
                  //     return;
                  // }

                  //xhttp.send("newlink=" + urlString);

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

          // https://stackoverflow.com/questions/5717093/check-if-a-javascript-string-is-a-url
          function isURL(str) {
              var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
                  '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
                  '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
                  '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
                  '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
                  '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
              return !!pattern.test(str);
          }

          /* Get all elements with class="close" */
          var closebtns = document.getElementsByClassName("close");
          var i;

          /* Loop through the elements, and hide the parent, when clicked on */
          for (i = 0; i < closebtns.length; i++) {
              closebtns[i].addEventListener("click", function () {
                  this.parentElement.style.display = 'none';
                  var urlString = this.parentElement.lastElementChild.innerHTML.toString();
                  //console.log(urlString);
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

                  editable = document.getElementById("editContent");
                  console.log("EDIT MODE: " + editable);
                  if(editable.contentEditable == "false") {
                      editable.contentEditable = "true";
                      document.getElementById("editButton").innerHTML = "Editing";
                      document.getElementById("editButton").style.backgroundColor='#f44242';
                      originalLink = this.parentElement.lastElementChild.innerHTML.toString();
                      console.log("original string:" + originalLink);
                  }else{
                      console.log("NORMAL MODE");
                      editable.contentEditable = "false";
                      document.getElementById("editButton").innerHTML = "|||";
                      document.getElementById("editButton").style.backgroundColor='#f6f6f6';
                      newLink = this.parentElement.lastElementChild.innerHTML.toString();
                      console.log("new link string:" + newLink);
                      console.log("original link double check:" + originalLink);
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
