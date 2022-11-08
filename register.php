<!DOCTYPE html>
<html lang="en">
<?php include "head.php"?>
<body>
    <?php include "header.php"?>
    <div class="form">
        <form action="register.php" method="POST">
            <h2>Create a New Account</h2>
            <div class="input">
                <p>Username | <input class="form_name" type="text" name="username"></p>
                <p>Password | <input class="form_pass"type=text name = password></p>
                <p>E-mail Adress | <input  class="form_mail" type=text name = mail></p>
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