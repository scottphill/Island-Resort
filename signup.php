<?php
    require "header.php";
?>
<div class = "back">
    <main>
        <div class="signup-head">
            <h3>Signup</h3>
            <?php
                if(isset($_GET['error'])){
                    if($_GET['error'] == "emptyfields") {
                        echo '<p class="signuperror">Fill out all fields!</p>';
                    }
                }
            ?>
        </div>
        <div class="signup-box">
            <form action="includes/signup.inc.php" method="post">
                <div class="signup-field">
                    <input class = "inputAdmin" type="text" name="fname" placeholder = "First Name">
                </div>
                <br>
                <div class="signup-field">
                    <input class = "inputAdmin" type="text" name="lname" placeholder = "Last Name">
                </div>
                <br>
                <div class="signup-field">
                    <input class = "inputAdmin" type="text" name="funds" placeholder = "Funds">
                </div>
                <br>
                <div class="signup-field">
                    <input class = "inputAdmin" type="text" name="uid" placeholder = "Username">
                </div>
                <br>
                <div class="signup-field">
                    <input class = "inputAdmin" type="password" name="pwd" placeholder = "Password">
                </div>
                <br>
                <div class="signup-field">
                    <input class = "inputAdmin" type="password" name="pwd-repeat" placeholder = "Repeat Password">
                </div>
                <br>
                <div class = signup-button>
                <button class = searchButton2 type="submit" name='signup-submit'>SIGNUP</button>
                </div>
            </form>
            <div class = signup-icons>
            <ul>
                <div class = signup-icon1>
                <li class="material-icons">create</li>
                </div>
                <div class = signup-icon2>
                <li class="material-icons">attach_money</li>
                </div>
                <div class = signup-icon3>
                <li class="material-icons">account_box</li>
                </div>
                <div class = signup-icon4>
                <li class="material-icons">vpn_key</li>
                </div>
            </ul>
            </div>
        </div>
    </main>
</div>
<?php
    require "footer.php";
?>