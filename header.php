<?php
    session_start();

if(isset($_SESSION['permission'])) {
    $perm = $_SESSION['permission'];
}
else
    $perm = -1;

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Oasis - Island Resort</title>
        <link rel="stylesheet" href="/Project4/style.css">
    </head>
<body>
    
</body>
    <header>
            <nav class="nav-header-main">
                <a class="header-logo" href="/Project4/index.php">
                    <img src="/Project4/img/greenv2oasis.png" alt="logo" class="logo">
                </a>
                <ul>
                  <li><a href="index.php">Home</a></li>
                     <?php if($perm == 2) {
                        echo "<li><a href='/Project4/searchUsers.php'>Search Users</a></li>";
                    } ?>
                  <li><a href="/Project4/searchCottages.php">Search Cottages</a></li>
                  <li><a href="/Project4/searchActivities.php">Search Activities</a></li>
                </ul>
            </nav>
 
            <div class = "header-login">
                <?php
                //if not logged in you should only see login and signup
                if(!isset($_SESSION['user'])) {
                    echo '<form action="includes/login.inc.php" method="POST">
                            <input type="text" name=uid placeholder="Username">
                            <input type="pwd" name=pwd placeholder="Password">
                            <button type="submit" name="login-submit">Login</button>
                        </form>
                        <a href="/Project4/signup.php" class="header-signup">Signup</a>';
                    }
                    //if logged in you should only see logout
                    else if(isset($_SESSION['user'])){ 
                        require 'includes/dbh.inc.php';
                        $u = $_SESSION['user'];
                        $sql = "SELECT * FROM users WHERE username = '$u'";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_array($result);
                ?>
                        <h5>Funds: <?php echo $row['Funds']?> </h5>
                        <?php
                    
                        echo '
                            <a href="/Project4/userProfile.php?user='.$_SESSION['user'].'">
                                <button class = profileIcon type="submit" name="myprofile-submit">
                                    <i class="material-icons2">
                                        account_circle
                                    </i>
                                </button>
                                </a>';
                        
                        echo '<form action="includes/logout.inc.php" method="POST">
                            <button type="submit" name="logout-submit">Logout</button>
                            </form>';

                    }
                    ?>
            </div>
    </header>
</html>
