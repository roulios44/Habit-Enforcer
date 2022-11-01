<!DOCTYPE html>
<html lang="en">
<?php include "head.php"?>
<body>
    <?php include "header.php"?>
</body>
<div class="register">
    <form action="register.php" method="POST">
        <p>Username: <input type="text" name="username"></p>
        <p>Password: <input type=text name = password></p>
        <p>E-mail Adress: <input type=text name = mail></p>
        <p><input type="submit" name="submit" value="Create your profile !"></input></p>
    </form>
</div>
<?php 
$username = $_POST["username"];
$password = $_POST["password"];
$mail = $_POST["mail"];
if(empty($username) || empty($password) || empty($mail)){
    echo "Please fill all fields please\n";
} else {
    register();
}
?>
<?php include "footer.php"?>
</html>




<?php
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