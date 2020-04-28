<?php

require '../header.php';

echo "<div class = back>";
if(isset($_POST['editUser-submit'])) {
    
    $user = $_GET['user'];
    $userDataQ = "SELECT * FROM users WHERE username = '$user'";
    $userData = mysqli_fetch_array(mysqli_query($conn, $userDataQ));
        
    if(isset($_POST['editfirst']))
        $firstname = $_POST['editfirst'];
    else
        $firstname = $userData['FirstName'];
    
    if(isset($_POST['editlast']))
        $lastname = $_POST['editlast'];
    else
        $lastname = $userData['LastName'];
    
    if(isset($_POST['editusername']))
        $username = $_POST['editusername'];
    else
        $username = $userData['Username'];
    
    if(!isset($_POST['oldpass'])
        || !password_verify($_POST['oldpass'], $userData['Password'])
        || $_POST['editpass'] === "")
        $newPass = $userData['Password'];
    else
        $newPass = $_POST['editpass'];
    
    if(isset($_POST['editfunds']))
        $funds = $_POST['editfunds'];
    else
        $funds = $userData['Funds'];
    
    if(isset($_POST['editpermission']))
        $permission = $_POST['editpermission'];
    else
        $permission = $userData['PermissionLvl'];
    
    if($_SESSION['user'] === $user) {
    $updateQ = "UPDATE `users` SET `FirstName` = '$firstname', `LastName` = '$lastname', `Username` = '$username', `Password` = '".password_hash($newPass, PASSWORD_DEFAULT)."', `Funds` = '$funds', `PermissionLvl` = '$permission' WHERE `users`.`Username` = '$user'";
    $_SESSION['user'] = $username;
    }
    else {
        $updateQ = "UPDATE `users` SET `Funds` = '$funds', `PermissionLvl` = '$permission' WHERE `users`.`Username` = '$user'";
    }
    
    if ($conn->query($updateQ) === TRUE) {
        echo "<div class = msg><p>User updated Successfully!</p></div>";
        echo "<div class = msg><p>$updateQ</p></div>";
    } 
    else
        echo "Error: " . $updateQ . "<br>" . $conn->error;
        
}
echo "</div>";
//mysqli_stmt_close($stmt);

?>