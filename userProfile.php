<?php
require 'includes/dbh.inc.php';
require "header.php";
echo "<div class = back>";

$user = $_GET['user'];
$userActivities = "SELECT A.ActivityID, A.ActivityName FROM users U, actpermissions AP, activities A WHERE U.username = '$user' 
AND AP.username_fk = '$user' AND A.ActivityID = AP.ActivityID_fk";
$canModify = ($_SESSION['permission'] == 2 || $_SESSION['user'] == $user);
if($_SESSION['user'] == $user)
    $userLabel = "Your";
else
    $userLabel = $user."'s";
echo "<div class = 'item-head2'>";
echo "<h3>$user's Profile</h3>";
echo "</div>";
$result = mysqli_query($conn, $userActivities);

if($canModify)
    echo
        '<form action="userSettings.php?user='.$user.'" method="POST">
            <button class = settings-button type="submit" name="settingsBtn">
                <i class="material-icons">
                    settings
                </i>
            </button>
        </form>'
?>

<div class = "table1Head"><h4><?php echo $userLabel;?> Permitted Activities:</h4></div>
<div class="search-table1">
<?php
    echo "<table class='content-table'>
    <thead>
        <tr>
            <th>Activity ID</th>
            <th>Activity Name</th>";
            if($_SESSION['user'] == $user)
                echo "<th>Sell</th>";
        
    echo "</tr>
    </thead>";
    while($row = mysqli_fetch_array($result))
    {
        echo "<tr>";
        echo "<td>" . $row['ActivityID'] . "</td>";
        echo "<td>" . $row['ActivityName'] . "</td>";?>
        
        <?php
        if($_SESSION['user'] == $user)
            echo
                '<td><form action="includes/itemTransaction.php?itemID='.$row['ActivityID'].'&itemType=activity" method="POST">
                        <button class = "tableButton" type="submit" name="transaction">
                            <i class="material-icons">
                                monetization_on
                            </i>
                        </button>
                    </form>
                </td>';
        ?>
    
    <?php
        echo "</tr>";
    }
    echo "</table>";
?>
</div>
<?php
$userCottages = "SELECT C.houseID, C.Location, C.Size FROM users U, cottages C, `stays-in` S WHERE U.username = '$user' 
AND S.username_fk = '$user' AND C.houseID = S.houseID_fk";
$result = mysqli_query($conn, $userCottages);
?>


<div class = "table2Head"><h4><?php echo $userLabel;?> Owned Cottages: </h4></div>
<div class="search-table2">
<?php
    echo "<table class='content-table'>
    <thead>
        <tr>
            <th>Activity ID</th>
            <th>Activity Name</th>
            <th>Size</th>";
    if($_SESSION['user'] == $user)
        echo "<th>Sell</th>";
    echo
        "</tr>
    </thead>";
    while($row = mysqli_fetch_array($result))
    {
        echo "<tr>";
        echo "<td>" . $row['houseID'] . "</td>";
        echo "<td>" . $row['Location'] . "</td>";
        echo "<td>" . $row['Size'] . "</td>";
        if($_SESSION['user'] == $user)
            echo
                '<td><form action="includes/itemTransaction.php?itemID='.$row['houseID'].'&itemType=cottage" method="POST">
                <button class = "tableButton" type="submit" name="transaction">
                    <i class="material-icons">
                        monetization_on
                    </i>
                </button>
                </form></td>';
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    echo "</div>";
?>