<?php

$servername = "localhost";
$username = "username";
$password = "";
$dbname = "Bookmarking";

// Create connection
$db = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$sql = "SELECT url FROM Links WHERE username = '$username'";
$result = mysqli_query($db, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo "Link: " . $row["url"]. "<br>";
    }
} else {
    echo "0 results";
}

mysqli_close($db);

function addLink($db, $urlString){

    $addQuery = "INSERT INTO Links (username, url )
                VALUES ( "tsantos" , "google.ca")";

    if ($db->query($addQuery) === TRUE) {
        echo "Record added successfully";
    } else {
        echo "Error added record: " . $db->error;
    }

}


function removeLink($db,$urlString){
    $deleteQuery = "DELETE FROM Links WHERE url = '$urlString'";

    if ($db->query($deleteQuery) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $db->error;
    }
}
?>