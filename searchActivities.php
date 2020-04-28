<?php
    require "header.php";
    require 'includes/dbh.inc.php';//need this line for access to $conn

    if(isset($_POST["search-submit"])){
        $activityName = mysqli_real_escape_string($conn, $_POST['ActivityName']);
        $location = mysqli_real_escape_string($conn, $_POST['Location']);
    }
    else{
        $activityName = 'Any';
        $location = 'Any';
    }

    $activityNames = array('Any', 'Skydiving', 'Snorkling', 'Rock Climbing');
    $locations = array('Any', 'Banana Bay', 'Moon River', 'Kiko Rock', 'Cliffside');
?>
<div class = "back">
<div class="search-options">
<form action="searchActivities.php" method="POST">
    <label for="ActivityName">Choose an activity:</label>
    <select class = "selected" name="ActivityName">
        <?php foreach($activityNames as $var): ?>
        <option class = "option" value="<?php echo $var ?>"
                <?php if($var == $activityName): ?>selected="selected"<?php endif; ?>>
                <?php echo $var ?>
        </option>
        <?php endforeach; ?>
    </select>
    <label for="Location">Choose a location:</label>
    <select class = "selected" name="Location">
        <?php foreach($locations as $var): ?>
        <option class = "option" value="<?php echo $var ?>"
                <?php if($var == $location): ?>selected="selected"<?php endif; ?>>
                <?php echo $var ?>
        </option>
        <?php endforeach; ?>
    </select>
    <button class = "searchButton" type="submit" name="search-submit">
        <i class="material-icons">
                    search
        </i>
    </button>
</form>
</div>
<?php
    $select = "SELECT * FROM activities A";
    $where = " WHERE";
    $sql="";

    if($activityName != "Any")
        $where = $where." A.ActivityName = '$activityName'";

    if($location != "Any"){
        if($where != " WHERE")
            $where = $where." AND";
        $where = $where." A.location = '$location'";
    }

    $sql = $sql.$select;
    if($where != " WHERE")
        $sql = $sql.$where;
                  
    $result = mysqli_query($conn, $sql);

    ?>
    <div class="search-table">
    <table class='content-table'>
    <thead>
        <tr>
            <th>Activity Name</th>
            <th>Location</th>
            <th>Price</th>
            <th></th>
            <?php if(isset($_SESSION['permission']) && $_SESSION['permission'] == 2) {?>
                <th>Remove</th>
            <?php } ?>
        </tr>
    </thead>

    <?php
    while($row = mysqli_fetch_array($result))
    {
        echo "<tr>";
        echo "<td>" . $row['ActivityName'] . "</td>";
        echo "<td>" . $row['Location'] . "</td>";
        echo "<td>" . $row['Price'] . "</td>";
        
        if(isset($_SESSION['user'])) { ?>
           <td><form action="browseItem.php?itemID=<?php echo $row['ActivityID']; ?>&itemType=activity" method="POST">
               <div class = "more-button"><button type="submit" name="More">Learn More</button></div>
            </form>
            </td>
        <?php }
        
        if(isset($_SESSION['permission']) && $_SESSION['permission'] == 2) { ?>
            <td><form action="includes/manageActivity.inc.php?itemID=<?php echo $row['ActivityID']; ?>&itemType=activity" method="POST">
            <button class = "searchButton" type="submit" name="remove-activity-submit">
                <i class="material-icons">
                    remove
                </i>
            </button>
            </form>
            </td>
        <?php }
        
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    echo "</div>";

if(isset($_SESSION['permission']) && $_SESSION['permission'] == 2)
        echo
        '<div class="search-optionsAdmin">
            <h4>Add new activity listing:<h4>
            <br>
            <br>
            <form action="includes/manageActivity.inc.php" method="POST">
                <label for="activityName">Activity:</label>
                <div class="boxAdmin">
                    <input class = "inputAdmin" type="text" name="activityname" placeholder="Activity Name">
                </div>
                <label for="location">Location:</label>
                <div class="boxAdmin">
                    <input class = "inputAdmin" type="text" name="location" placeholder="Location">
                </div>
                <label for="price">Price:</label>
                <div class="boxAdmin">
                    <input class = "inputAdmin"type="text" name="price" placeholder="Price">
                </div>
                <button class = "searchButton" type="submit" name="add-activity-submit">
                    <i class="material-icons">
                        add
                    </i>
                </button>
            </form>
        </div>';

    mysqli_close($conn);

    require "footer.php";
?>