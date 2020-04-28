<?php
require 'includes/dbh.inc.php';
require "header.php";
echo "<div class = back>";

$user = $_GET['user'];
//$canModify = ($_SESSION['permission'] == 2 || $_SESSION['user'] === $user);
if($_SESSION['user'] === $user)
    $userLabel = "Your";
else
    $userLabel = $user."'s";

echo "<div class = 'settings-head'>";
echo "<h3>$userLabel Profile Settings</h3>";
echo "</div>";

$userDataQuery = "SELECT * FROM users U WHERE U.username = '$user'";
$userData = mysqli_fetch_array(mysqli_query($conn, $userDataQuery));

echo "<div class = settingsDescription>";
echo "<p>Enter updated information in the fields below.<br>Hit the gavel to submit changes!</p>";
echo "</div>";
echo "<div class=settings-box>";

echo '<form action="includes/updateUser.php?user='.$user.'" method="POST">';
if($_SESSION['user'] === $user)
echo
        '<div class="signup-field">
            <input class = "inputAdmin" type="text" name="editfirst" placeholder="Enter New First Name" value='.$userData['FirstName'].'>
        </div>
        <br>
        <div class="signup-field">
            <input class = "inputAdmin" type="text" name="editlast" placeholder="Enter New Last Name" value='.$userData['LastName'].'>
        </div>
        <br>
        <div class="signup-field">
            <input class = "inputAdmin" type="text" name="editusername" placeholder="Enter New Username" value='.$userData['Username'].'>
        </div>
        <br>
        <div class="signup-field">
            <input class = "inputAdmin" type="text" name="oldpass" placeholder="Enter Old Password">
        </div>
        <br>
        <div class="signup-field">
            <input class = "inputAdmin" type="text" name="editpass" placeholder="Enter New Password">
        </div>
        <br>';

if($_SESSION['permission'] === 2)
    echo
        '<div class="signup-field">
            <input class = "inputAdmin" type="number" name="editfunds" placeholder="Enter New Funds Amount" value='.$userData['Funds'].'>
        </div>
        <br>';
    
$adminSelected = "";
$normalSelected = "";
if($userData['PermissionLvl'] == 2)
    $adminSelected = "selected";
else
    $normalSelected = "selected";

if($_SESSION['user'] !== $user && $_SESSION['permission'] == 2)
    echo
        '<div class = permission-options>
            <select class = "selected" name="editpermission">
                <option class = "option" value=0 '.$normalSelected.'>Normal User</option>
                <option class = "option" value=2 '.$adminSelected.'>Admin</option>
            </select>
        </div>';
    
echo
        '<button class = settingsApply type="submit" name="editUser-submit">
            <i class="material-icons">
                gavel
            </i>
        </button>
    </form>';
echo "</div>";
?>