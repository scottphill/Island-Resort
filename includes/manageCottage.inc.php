<?php

require '../header.php';
echo "<div class = back>";
require 'dbh.inc.php';

if(isset($_POST["add-cottage-submit"])){
    
    $location = $_POST["location"];
    $size = $_POST["size"];
    $price = $_POST["price"];
    $user = $_SESSION['user'];
    
    $maxIDquery = 'SELECT * FROM `cottages` ORDER BY `HouseID` DESC LIMIT 1';
    $newID = mysqli_fetch_array(mysqli_query($conn, $maxIDquery))['HouseID'] + 1;
    
    $addQuery = 'INSERT INTO `cottages`(`HouseID`, `Location`, `Size`, `Price`)                                 VALUES('.$newID.', "'.$location.'", "'.$size.'", '.$price.')';
    
    if($conn->query($addQuery) === TRUE) {
        echo "<div class = msg><p>Cottage successfully added!</p></div>";
        echo "<div class = msg><p>$addQuery</p></div>";
    }
    else
        echo "<div class = msg><p>Cottage creation failed.</p></div>";
}

if(isset($_POST["remove-cottage-submit"])){
    
    $cottage = $_GET['itemID'];
    
    $checkElseOwn = "SELECT * FROM `stays-in` WHERE houseID_fk = '$cottage'";
    $result = mysqli_query($conn, $checkElseOwn);
    if(mysqli_num_rows($result) != 0) {
        $elsePurchased = true;
        $row = mysqli_fetch_array($result);
        $user = $row['username_fk'];
    }
    
    
    $remove = "DELETE FROM `cottages` WHERE `cottages`.`HouseID` = '$cottage'";
    
    if($conn->query($remove) === TRUE) {
        echo "<div class = msg><p>Cottage successfully removed!</p></div>";
        echo "<div class = msg><p>$remove</p></div>";
    }
    else{
        echo "<div class = msg><p>Cottage removal failed.</p></div>";
        if($elsePurchased) {
            echo "<div class = msg><p>Someone is staying in this cottage.</p></div>";
            echo "<div class = msg><p>$checkElseOwn</p></div>";
            echo "<div class = msg><p>Query returns not null.</p></div>";
            echo "<div class = msg><p>Owner = <strong>$user</strong></p></div>";
        }
    }
}
echo "</div>";
//mysqli_stmt_close($stmt);

?>