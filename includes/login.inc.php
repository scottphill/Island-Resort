<?php

//similar to signup, here we check less parameters and do less querrying.
if(isset($_POST["login-submit"])) {
    
    require 'dbh.inc.php';
    
    $username = $_POST['uid'];
    $password = $_POST["pwd"];
    
    if(empty($username) || empty($password)){
        header("Location: ../index.php?error=emptyfields");//links back to index page
        exit();
    }
    //Create PS for querrying username
    else {
        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){//initialize the statement
            header("Location: ../index.php?error=sqlerror");
            exit();
        }
        //bind param and perform querry.
        else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            //will return false if no user is found. If true, we must then make sure password is correct
            if($row = mysqli_fetch_assoc($result)) {
                //Here we need to check if the entered password matches the hashed password in the database. pwdCheck will be 0 or 1.
                $pwdCheck = password_verify($password, $row['Password']);
                
                if($pwdCheck == false) {
                    header("Location: ../index.php?error=wrongpwd");
                    exit();
                }
                //if true we must start a session for the user and log them in
                else if ($pwdCheck == true) {
                    session_start();
                    $_SESSION['user'] = $row['Username'];
                    $_SESSION['permission'] = $row['PermissionLvl'];
                        
                    header("Location: ../index.php?login=success");
                    exit();
                }
                else {
                    header("Location: ../index.php?error=wrongpwd");
                    exit();
                }     
            }
            else {
                header("Location: ../index.php?error=nouser");
                exit();
            }
        }
    }
}

else {
    header("Location: ../index.php");
    exit();
}