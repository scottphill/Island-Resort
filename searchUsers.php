<?php
    require "header.php";
    require 'includes/dbh.inc.php';//need this line for access to $conn

    if(isset($_POST["search-submit"]))
        $search = mysqli_real_escape_string($conn, $_POST['search']);
    else
        $search = ''
?>
<div class = "back">
    
<form action="searchUsers.php" method="POST">
    <div class="searchBo">
    <input class="searchInput" type="text" name="search" placeholder="Search">
    <button class="searchButton" type="submit" name="search-submit">
        <i class="material-icons">
            search
        </i>
    </button>
    </div>
</form>

<?php
    require 'includes/dbh.inc.php';//need this line for access to $conn

    $sql = "SELECT * FROM users WHERE username LIKE '%$search%' OR firstname LIKE '%$search%' OR lastname LIKE '%$search%'";
    $result = mysqli_query($conn, $sql);

?>
<div class="search-table">
<?php
    echo "<table class='content-table'>
    <thead>
        <tr>
            <th>FirstName</th>
            <th>LastName</th>
            <th>Username</th>
            <th></th>
        </tr>
    </thead>";

    while($row = mysqli_fetch_array($result))
    {
        echo "<tr>";
        echo "<td>" . $row['FirstName'] . "</td>";
        echo "<td>" . $row['LastName'] . "</td>";
        echo "<td>" . $row['Username'] . "</td>";
        if(isset($_SESSION['user'])) { ?>
        <td>
            <form action="userProfile.php?user=<?php echo $row['Username']; ?>" method="POST">
                <div class = "more-button"><button type="submit" name="More">See More</button></div>
            </form>
        </td>
    <?php }
        echo "</tr>";
    }
    echo "</table>";
?>
</div>
</div>
<?php

    mysqli_close($conn);
?>

<?php
    require "footer.php";
?>