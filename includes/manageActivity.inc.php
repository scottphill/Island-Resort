<?php

require '../header.php';
echo "<div class = back>";
require 'dbh.inc.php';

if(isset($_POST["add-activity-submit"])){
    
    $activityName = $_POST['activityname'];
    $location = $_POST["location"];
    $price = $_POST["price"];
    
    $maxIDquery = 'SELECT * FROM `activities` ORDER BY `ActivityID` DESC LIMIT 1';
    $newID = mysqli_fetch_array(mysqli_query($conn, $maxIDquery))['ActivityID'] + 1;
    
    $addQuery = 'INSERT INTO `activities`(`ActivityID`, `ActivityName`,`Location`, `Price`)                       VALUES('.$newID.', "'.$activityName.'", "'.$location.'", '.$price.')';
    
    if($conn->query($addQuery) === TRUE){
        echo "<div class = msg><p>Activity successfully added!</p></div>";
        echo "<div class = msg><p>$addQuery</p></div>";
    }
    else
         echo "<div class = msg><p>Activity creation failed.</p></div>";
}

if(isset($_POST["remove-activity-submit"])){
    
    $activityID = $_GET['itemID'];
    $activity = "SELECT * FROM `activities` WHERE activityID = '$activityID'";
    $result1 = mysqli_query($conn, $activity);
    $activityRow = mysqli_fetch_array($result1);
    $activityPrice = $activityRow['Price'];
    
    
    
    $checkElseOwn = "SELECT * FROM `actpermissions` WHERE activityID_fk = '$activityID'";
    $result2 = mysqli_query($conn, $checkElseOwn);
    
    $remove = "DELETE FROM `activities` WHERE `activities`.`ActivityID` = '$activityID'";
    
    while($row = mysqli_fetch_array($result2))//row is an array containing the value at each index
    {
        $user = $row['username_fk'];
        echo "<div class = msg><strong><p>$user...</p></strong></div>";
        $userQuery = "SELECT * FROM `users` WHERE username = '$user'";
        $result3 = mysqli_query($conn, $userQuery);
        $userInfo = mysqli_fetch_array($result3);
        
        $funds = $userInfo['Funds'];
        $funds = $funds + $activityPrice;

        $changeFunds = "UPDATE users SET Funds = '$funds' WHERE username = '$user'";
        $alterTable = "DELETE FROM `actpermissions` WHERE `actpermissions`.`username_fk` = '$user' AND `actpermissions`.`activityID_fk` = '$activityID'";
        
        if($conn->query($changeFunds)) {
            echo "<div class = msg><p>Funds updated!</p></div>";
            echo "<div class = msg><p>$changeFunds</p></div>";
        }
        
        if($conn->query($alterTable)) {
            echo "<div class = msg><p>actpermissions updated successfully!</p></div>";
            echo "<div class = msg><p>$alterTable</p></div>";
        }
        else
            echo "<div class = msg><p>Error: $alterTable $conn->error</p></div>";
    }
    if($conn->query($remove) === TRUE){
        echo "<div class = msg><p>Activity successfully removed!</p></div>";
        echo "<div class = msg><p>$remove</p></div>";
    }
    else {
        echo "<div class = msg><p>Activity removal failed!</p></div>";
    }
}

echo "</div>";
//mysqli_stmt_close($stmt);

?>