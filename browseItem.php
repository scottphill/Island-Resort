
<?php
require "header.php";
require 'includes/dbh.inc.php';

$itemID = $_GET['itemID'];
$itemType = $_GET['itemType'];
$user = $_SESSION['user'];
$elsePurchased = false;

?>
<div class = "back">
<?php
if($itemType == 'cottage'){
    $checkMeOwn = "SELECT * FROM `stays-in` WHERE username_fk = '$user' AND houseID_fk = '$itemID'";
    $checkElseOwn = "SELECT * FROM `stays-in` WHERE username_fk != '$user' AND houseID_fk = '$itemID'";
    if(mysqli_num_rows(mysqli_query($conn, $checkElseOwn)) != 0)
        $elsePurchased = true;
    
    $picFolder = 'cottagePics';
    $numPics = 15;
    $sellBtnTxt = 'Sell';
    
    $item = "SELECT * FROM cottages WHERE HouseID = '$itemID'";
    $result = mysqli_query($conn, $item);
    $row = mysqli_fetch_array($result);
    
    $location = $row['Location'];
    $price = $row['Price'];
    $size = $row['Size'];
    
    echo "<div class = 'item-head'>";
    echo "<h3>$location $itemID</h3>";
    echo "</div>";
}
else if($itemType == 'activity'){
    $checkMeOwn = "SELECT * FROM `actpermissions` WHERE username_fk = '$user' AND activityID_fk = '$itemID'";
    $picFolder = 'activityPics';
    $numPics = 20;
    $sellBtnTxt = 'Refund';
    
    $item = "SELECT * FROM activities WHERE activityID = '$itemID'";
    $result = mysqli_query($conn, $item);
    $row = mysqli_fetch_array($result);
    
    $location = $row['Location'];
    $price = $row['Price'];;
    $activity = $row['ActivityName'];
    
    echo "<div class = 'item-head'>";
    echo "<h3>$activity</h3>";
    echo "</div>";
}

$purchased = (mysqli_num_rows(mysqli_query($conn, $checkMeOwn)) != 0);
?>
<div class = "main-frame">
<div class = "frame1">
    <a href="#">
        <img src="img/<?php echo $picFolder ?>/<?php echo (($itemID*5)-5)%$numPics; ?>.jpg">
    </a>
</div>
<div class = "frame2">
    <a href="#">
        <img src="img/<?php echo $picFolder ?>/<?php echo (($itemID*5)-4)%$numPics; ?>.jpg">
    </a>
    <a href="#">
        <img src="img/<?php echo $picFolder ?>/<?php echo (($itemID*5)-3)%$numPics; ?>.jpg">
    </a>
</div>
<div class = "frame3">
    <a href="#">
        <img src="img/<?php echo $picFolder ?>/<?php echo (($itemID*5)-2)%$numPics; ?>.jpg">
    </a>
    <a href="#">
        <img src="img/<?php echo $picFolder ?>/<?php echo (($itemID*5)-1)%$numPics; ?>.jpg">
    </a>
</div>
</div>
    

<form action="includes/itemTransaction.php?itemID=<?php echo $itemID; ?>&itemType=<?php echo $itemType; ?>" method="POST">
    <div class = transaction>
    <?php if(!$elsePurchased) { ?>
        <button class = "searchButton1" type="submit" name="transaction">
            <?php if($purchased) echo $sellBtnTxt; else echo "Purchase"; ?>
        </button>
    <?php } ?>
        
    <?php if($itemType == 'cottage') { ?>
        <div class = icon-list>
            <ul>
                <li class="material-icons">place</li><br><br>
                <li class="material-icons">house</li><br><br>
                <li class="material-icons">attach_money</li><br><br>
                <?php if($elsePurchased) echo "<li class='material-icons'>error</li><br><br>";?>
            </ul>
        </div>
        <?php if($elsePurchased) echo "<div class = det-list1e>"; else echo "<div class = det-list1>"; ?>
            <ul>
                <li>Location: <?php echo $location; ?></li><br>
                <li>Size: <?php echo $size; ?></li><br>
                <li>Price: <?php echo $price; ?></li><br>
                <?php if($elsePurchased) echo "<li><strong><u>This home is currently owned by someone else. Come back later!</strong></u></li><br><br>";?>
            </ul>
        </div>
    <?php } ?>
        
     <?php if($itemType == 'activity') { ?>
        <div class = icon-list>
            <ul>
                <li class="material-icons">place</li><br><br>
                <li class="material-icons">attach_money</li><br><br>
                <?php if($purchased) echo "<li class='material-icons'>error</li><br><br>";?>
            </ul>
        </div>
        <?php if($purchased) echo "<div class = det-list2e>"; else echo "<div class = det-list2>"; ?>
            <ul>
                <li>Location: <?php echo $location; ?></li><br>
                <li>Price: <?php echo $price; ?></li><br>
                <?php if($purchased) echo "<li><strong><u>Refunds return only 50% of original price.</strong></u></li><br><br>";?>
            </ul>
        </div>
    <?php } ?>
    </div>
</div>
</form>
<?php
    require "footer.php";
?>