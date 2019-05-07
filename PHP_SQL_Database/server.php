<?php
error_reporting(E_ERROR | E_PARSE);
/*
1. Start up our session
2. Initialize variables
3. Connect to SQLDatabase
4. Write the logic for registering the user onto the database
*/

//Start Session
session_start();

//Initializing variables
$username = "";
$email = "";

$errors = array();



//Connect to database mysqli_connect(IP_ADDRESS_OF_DATABASE,'USER_TO_LOGIN_WITH','PASSWORD','DATABASE_NAME_YOU_WANT')
$db = mysqli_connect('localhost','root','','Bookmarking') or die("could not connect to database");

//Register Users

//Get all the variables from the form submitted in POST
$username = mysqli_real_escape_string($db,$_POST['username']);
$email = mysqli_real_escape_string($db,$_POST['email']);
$password_1 = mysqli_real_escape_string($db,$_POST['password_1']);
$password_2 = mysqli_real_escape_string($db,$_POST['password_2']);

// Validating user form input -- dont need this. form does it for us.

//if(empty($username)) {array_push($errors,"Username is required");}
//if(empty($email)) {array_push($errors,"Email is required");}
//if(empty($password_1)) {array_push($errors,"Password is required");}
//if(empty($password_2)) {array_push($errors,"Must verify password");}
if($password_1 != $password_2){array_push($errors,"Passwords must match");}

//Check if username already exists in database

$username_check = " SELECT * FROM Users WHERE username = '$username' or email = '$email' LIMIT 1 ";

$results = mysqli_query($db,$username_check);
$user = mysqli_fetch_assoc($results);


//wait until the submit button is pressed to do the checks
if(isset($_POST['register_user'])) {
  if ($user) {  // if the user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }
    if ($user['email'] === $email) {
      array_push($errors, "This email is already associated with an account");
    }

  }
}

//Register User if no errors in the form and submit has been selected
if(count($errors)==0 && isset($_POST['register_user'])){
  $password = md5($password_1); //hash the password
  $query_insertUser = "INSERT INTO Users (username,email,password) VALUES ('$username','$email','$password')";

  mysqli_query($db,$query_insertUser);
  $_SESSION['username']=$username;
  $_SESSION['success']="You are now Logged In";

  header("location: index.php");
}

//Login the User
//button name was login_user
if(isset($_POST['login_user'])){
  $username = mysqli_real_escape_string($db,$_POST['username']);
  $password = mysqli_real_escape_string($db,$_POST['password_1']);

  if(empty($username)){
    array_push($errors, "Username is required");
  }

  if(empty($password)){
    array_push($errors, "Password is required");
  }

  //if(count($errors)==0){

    $password = md5($password);
    $query_verifyPassword = "SELECT * FROM Users WHERE PASSWORD ='$password' AND username = '$username'";
    $verify = mysqli_query($db,$query_verifyPassword);

    //Check result of verification and decide what to do based on result
    if(mysqli_num_rows($verify)){
      $_SESSION['username'] = $username;
      $_SESSION['success'] = "Logged in Successfully";
      header("location:index.php");

    }else{
      array_push($errors,"Wrong Username or Password, please try again");
    }
 // }
}



















 ?>
