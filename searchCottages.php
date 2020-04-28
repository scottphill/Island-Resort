<body>
<?php
    require "header.php";
    require 'includes/dbh.inc.php';//need this line for access to $conn

    if(isset($_POST["search-submit"])) {
        $location = mysqli_real_escape_string($conn, $_POST['Location']);
        $size = mysqli_real_escape_string($conn, $_POST['Size']);
        $occupancy = mysqli_real_escape_string($conn, $_POST['Occupancy']);
    }
    else{
        $location = "Any";
        $size = "Any";
        $occupancy = "Any";
    }

    $locations = array('Any', 'Banana Bay', 'Moon River', 'Kiko Rock', 'Cliffside', 'Beach Front');
    $sizes = array('Any', 'Large', 'Medium', 'Small');
    $occupancies = array('Any', 'Available', 'Not Available');
?>
<div class = "back">
<div class="search-options">
<form action="searchCottages.php" method="POST">
    <label for="Location">Choose a location:</label>
    <select class = "selected" name="Location">
        <?php foreach($locations as $var): ?>
        <option class = "option" value="<?php echo $var ?>"
                <?php if($var == $location): ?>selected="selected"<?php endif; ?>>
                <?php echo $var ?>
        </option>
        <?php endforeach; ?>
    </select>
    <label for="Size">Choose a size:</label>
    <select class = "selected" name="Size">
        <?php foreach($sizes as $var): ?>
        <option class = "option" value="<?php echo $var ?>"
                <?php if($var == $size): ?>selected="selected"<?php endif; ?>>
                <?php echo $var ?>
        </option>
        <?php endforeach; ?>
    </select>
    <label for="Occupancy">Choose availability:</label>
    <select class = "selected" name="Occupancy">
        <?php foreach($occupancies as $var): ?>
        <option class = "option" value="<?php echo $var ?>"
                <?php if($var == $occupancy): ?>selected="selected"<?php endif; ?>>
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
    
$select = "SELECT";
$where = " WHERE";
$sql="";

if("$occupancy" == "Available"){
    $select = $select." * FROM cottages C";
    $where = $where." houseID NOT IN (SELECT houseID_fk FROM `stays-in`)";
}
else if("$occupancy" == "Not Available"){
    $select = $select." C.HouseID, C.Location, C.Size, C.Price FROM cottages C, `stays-in` S";
    $where = $where." C.houseID = S.houseID_fk";
}
else{
    $select = $select." * FROM cottages C";
}

if($location != "Any"){
    if($where != " WHERE")
        $where = $where." AND";
    $where = $where." C.location = '$location'";
}

if($size != "Any"){
    if($where != " WHERE")
        $where = $where." AND";
    $where = $where." C.size = '$size'";
}

$sql = $sql.$select;
if($where != " WHERE")
    $sql = $sql.$where;

$result = mysqli_query($conn, $sql);
echo "<div class = query><p>$sql</p></div>";
?>
<div class="search-table">
<table class='content-table'>
<thead>
    <tr>
        <th>HouseID</th>
        <th>Location</th>
        <th>Size</th>
        <th>Price</th>
        <th></th>
        <?php if(isset($_SESSION['permission']) && $_SESSION['permission'] == 2) {?>
                <th>Remove</th>
        <?php } ?>
    </tr>
</thead>

<?php
while($row = mysqli_fetch_array($result))//row is an array containing the value at each index
{

    echo "<tr>";
    echo "<td>" . $row['HouseID'] . "</td>";
    echo "<td>" . $row['Location'] . "</td>";
    echo "<td>" . $row['Size'] . "</td>";
    echo "<td>" . $row['Price'] . "</td>";


    if(isset($_SESSION['user'])) { ?>
       <td><form action="browseItem.php?itemID=<?php echo $row['HouseID']; ?>&itemType=cottage" method="POST">
           <div class = "more-button"><button type="submit" name="More">Learn More</button></div>
        </form>
        </td>
    <?php }
    
    if(isset($_SESSION['permission']) && $_SESSION['permission'] == 2) { ?>
            <td><form action="includes/manageCottage.inc.php?itemID=<?php echo $row['HouseID']; ?>&itemType=cottage" method="POST">
            <button class = "searchButton" type="submit" name="remove-cottage-submit">
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
            <h4>Add new cottage listing:<h4>
            <br>
            <br>
            <form action="includes/manageCottage.inc.php" method="POST">
                <label for="location">Location:</label>
                <div class="boxAdmin">
                    <input class = "inputAdmin" type="text" name="location" placeholder="Location">
                </div>
                <label for="size">Size:</label>
                <select class = "selected" name="size">
                    <option class = "option" value="Small">Small</option>
                    <option class = "option" value="Medium">Medium</option>
                    <option class = "option" value="Large">Large</option>
                </select>
                <br>
                <br>
                <br>
                <label for="price">Price:</label>
                <div class="boxAdmin">
                    <input class = "inputAdmin" type="text" name="price" placeholder="Price">
                </div>
                <button class = "searchButton" type="submit" name="add-cottage-submit">
                    <i class="material-icons">
                        add
                    </i>
                </button>
            </form>
        </div>';
?>
<?php
mysqli_close($conn);

require "footer.php";
?>
