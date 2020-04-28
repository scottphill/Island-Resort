<?php

if(isset($_POST["signup-submit"])){

    require 'dbh.inc.php';

    $firstname = $_POST["fname"];
    $lastname = $_POST["lname"];
    $funds = $_POST["funds"];
    $username = $_POST['uid'];
    $password = $_POST["pwd"];
    $re_password = $_POST["pwd-repeat"];
  
    
    //If any of the inputs are empty do not move location
    if(empty($username) || empty($password) || empty($firstname) || empty($lastname) || empty($funds) || empty($re_password)){
        header("Location: ../signup.php?error=emptyfields");//links back to signup page
        exit();
    }
    else if ($password != $re_password) {
        header("Location: ../signup.php?error=passwordmatch");
        exit();
    }

    else {
        //here we create a prepared statement. The ? is just a placeholder that will be added to later.
        $sql = "SELECT Username FROM users WHERE Username=?";
        $stmt = mysqli_stmt_init($conn);//connect statement to db (listed in dbh.inc.php)
        if(!mysqli_stmt_prepare($stmt, $sql)){//initialize the statement
            header("Location: ../signup.php?error=sqlerror");
            exit();
        }
        
        //Now that a prepared staement is made we must bind a parameter to it so it can actually run the query with that specific parameter. In this case, the username.
        else {
            mysqli_stmt_bind_param($stmt, "s", $username);//binds the entered uid
            mysqli_stmt_execute($stmt);//runs the entered uid through the database
            mysqli_stmt_store_result($stmt);//if there is a match/no math it is stored
            $resultCheck = mysqli_stmt_num_rows($stmt);//sets variable to number of matches

            //if there were 0 matches we know the username is not taken, otherwise it is.
            if($resultCheck > 0) {
                header("Location: ../signup.php?error=usertaken");
                exit();
            }
            //Once again make a prepared statement but this time we want to insert all the entered atributes    
            else {
                $sql = "INSERT INTO users (FirstName, LastName, Funds, Password, Username) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql)){
                  header("Location: ../signup.php?error=sqlerror");
                  exit();
                }
                //Now that the statement has been initialized we should bind the parameters. However, we should also hash the password.    
                else {
                    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);//default hashing in PhP is regularly updated

                    mysqli_stmt_bind_param($stmt, "ssiss", $firstname, $lastname, $funds, $hashedPwd, $username);//makes sure this is same order as prepared statement
                    mysqli_stmt_execute($stmt);
                    header("Location: ../signup.php?signup=success");
                    exit();
                }
            }
        }
    }
//Make sure we dont waste resources on website   
mysqli_stmt_close($stmt);
mysqli_stmt_close($conn);
}
//If a user gains access to this page without signup button they'll be send back regardless
else{
  header("Location: ../signup.php");
  exit();
}