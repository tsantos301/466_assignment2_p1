<?php
error_reporting(E_ERROR | E_PARSE);
if($GLOBALS['welcome']==false) {
    include 'navigation.php';
}
?>
<?php

function getPopular($db){

    $popularQuery = "SELECT       url,
                     COUNT(url) AS value_occurrence
                     FROM     Links
                     GROUP BY url
                     ORDER BY value_occurrence DESC
                     LIMIT    10;";

    $result = mysqli_query($db, $popularQuery);
    $rank = 1;
    echo "<h2>Most popular sites of our users: </h2>";
    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $print = $row['url'];
            $fullLink = "http://".$row['url'];
            //echo "Link: <a>" . $row['url']. "</a><br>";
            //<li>Agnes<span class="close">x</span></li>
            echo "<li class='links'><label style='text-align: left;'>$rank.</label><a class = 'linkText' target=\"_blank\" href=".$fullLink." contentEditable = 'false'>$print</a></li>";
            $rank+=1;
        }
    } else {
        echo "No links have been Bookmarked Yet";
    }
}

// Create connection
$db = mysqli_connect('localhost','root','','Bookmarking') or die("could not connect to database");
getPopular($db);

?>

<style>
    <?php include 'CSS/main.css';?>
</style>
