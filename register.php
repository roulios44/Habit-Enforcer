<!DOCTYPE html>
<html lang="en">
<?php include "head.php"?>
<body>
    <?php include "header.php"?>
    <div class="form">
        <form action="register.php" method="POST">
            <p>Username: <input type="text" name="username"></p>
            <p>Password: <input type=text name = password></p>
            <p>E-mail Adress: <input type=text name = mail></p>
            <p><input type="submit" name="submit" value="Create your profile !"></input></p>
        </form>
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
    if(!$alreadyUse){
        addUserDB($username,$mail,$password);
        header('Location: signIn.php');
    }
}
?>