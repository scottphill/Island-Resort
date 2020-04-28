<?php

require '../header.php';

echo "<div class = back>";
if(isset($_POST["transaction"])) {
    
    require 'dbh.inc.php';
    $itemID = (int)$_GET['itemID'];
    //$itemID = (int)$itemID;
    $itemType = $_GET['itemType'];
    $user = $_SESSION['user'];
    
    //For cottage transactions
    if($itemType == 'cottage'){
        $prmsnTbl = 'stays-in';
        $itemIDtype = 'houseID_fk';
        //we must make sure the user has enough funds to make a purchase
        $checkFunds = "SELECT U.Funds, C.Price FROM users U, cottages C WHERE U.username = '$user' AND C.HouseID = '$itemID'";
        $sellMult = 1;
    }
    //For activity transactions
    else if($itemType == 'activity'){
        $prmsnTbl = 'actpermissions';
        $itemIDtype = 'activityID_fk';
        $checkFunds = "SELECT U.Funds, A.Price FROM users U, activities A WHERE U.username = '$user' AND A.ActivityID = '$itemID'";
        $sellMult = 0.5;
    }
    
    $checkMeOwn = "SELECT * FROM `$prmsnTbl` WHERE username_fk = '$user' AND `$itemIDtype` = '$itemID'";//checks ownership of item
    $purchased = (mysqli_num_rows(mysqli_query($conn, $checkMeOwn))!=0);//if row# != 0 then user has purchased this item already
    $result = mysqli_query($conn, $checkFunds);
    
    $row = mysqli_fetch_array($result);
    $funds = $row['Funds'];
    $price = $row['Price'];
    $balance = $funds - $price;
    
    
    if($purchased)//if true then the user wants to sell
        $funds = $funds + ($sellMult * $price);
    else if($balance < 0) {//if they dont want to sell they want to purchase, but we need to check if they have enough.
        echo "<div class = msg><p>Insufficient funds!</p></div>";
        echo "<div class = msg><p>$checkFunds</p></div>";
        echo "<div class = msg><p>Funds = $funds, Price = $price</p></div>";
        exit(); }
    else//The user wants to purchase
        $funds = $funds - $price;
    
    $changeFunds = "UPDATE users SET Funds = '$funds' WHERE username = '$user'";
    
    if($purchased)
        $alterTable = "DELETE FROM `$prmsnTbl` WHERE `$prmsnTbl`.`$itemIDtype` = $itemID";
    else
        $alterTable = "INSERT INTO `$prmsnTbl` ($itemIDtype, username_fk) VALUES ('$itemID', '$user')";
    
    if ($conn->query($changeFunds) === TRUE) {
        echo "<div class = msg><p>Funds updated Successfully!</p></div>";
        echo "<div class = msg><p>$changeFunds</p></div>";
    } 
    else {
        echo "Error: " . $changeFunds . "<br>" . $conn->error;
    }

    if ($conn->query($alterTable) === TRUE) {
        echo "<div class = msg><p>$prmsnTbl altered successfully!</p></div>";
        echo "<div class = msg><p>$alterTable</p></div>";
    } 
    else {
        echo "Error: " . $alterTable . "<br>" . $conn->error;
    }
}
echo "</div>";
//mysqli_stmt_close($stmt);

?>