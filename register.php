<!DOCTYPE html>
<html lang="en">
<?php include "head.php"?>
<body>
    <?php include "header.php"?>
    <div class="form">
        <form action="register.php" method="POST">
            <h2>Create a New Account</h2>
            <br>
            <div class="input">
                <div class="username">
                    <p>Username |<input class="form_name" type="text" name="username"></p>
                </div>
                <div class="password">
                    <p>Password |<input class="form_name" type="password" name="password"></p>
                </div>
                <div class="email">
                    <p> email |<input class="form_name" type="password" name="confirm_password"></p>
                </div>
            </div>
        </form>
        <p><input class="submit" type="submit" name="submit" value="Create your profile !"></input></p>
    </div>
    <?php beginRegister() ;?>
</body>
<?php include "footer.php"?>
</html>




<?php
function beginRegister(){
    $username = $_POST["username"];
    $password = $_POST["password"];
    $mail = $_POST["mail"];
    if(empty($username) || empty($password) || empty($mail)){
        echo "Please fill all fields please<br>";
    } else {
        register();
    }
}
function register(){
    require("request.php") ;
    $alreadyUse = false;
    $username = $_POST["username"];
    $password = $_POST["password"];
    $mail = $_POST["mail"];
    if ((alreadyExist($username,"user","username"))){
        echo "username '$username' is already use, please chose another one <br>" ;
        $alreadyUse = true;
    }
    if (alreadyExist($mail,"user","email")){
        echo "mail '$mail' is alreadu use for a account <br>";
        $alreadyUse = true;
    }
    if(!$alreadyUse)addUserDB($username,$mail,$password);
}
?>