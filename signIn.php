<?php require_once "request.php" ;
class Login extends Request{
    function BeginSignIn(){
        $username = strip_tags($_POST["username"]);
        $password = strip_tags($_POST["password"]);
        if(empty($username) || empty($password)){
            echo "<p>Please fill all fields please</p><br>";
        } else {
            $this->SignIn($username, $password);
    }
}

function SignIn(String $username, String $password){
    if (!$this->alreadyExist($username,"user", "username"))echo "This username is unknow of our website, you can create a account with this one";
    else {
        if ($this->checkPassword($username,$password)){
            session_start();
            $_SESSION["username"] = strip_tags($username) ;
            $_SESSION["id"] = $this->getInDB("id","user","username",strip_tags($username))["id"];
            $_SESSION["groupID"] = $this->getInDB("groupID","user","id",$_SESSION["id"])["groupID"] ;
            header('Location: main.php');
        } else {
            echo "<p>Wrong password, try again</p>" ;
        }
    }
}

function generatePage(){
    if (is_null($_SESSION["id"])){
        echo '
        <div class="form">
        <form action="" method="POST">
        <p>Username: <input type="text" name="username"></p>
        <p>Password: <input type="password" name ="password"></p>
        <p><input type="submit" value="Connect"></p>
        </form>
        <?php BeginSignIn()?>
        </div>
        <div class="connectMessage">
        <p>No Account? <a href="http://localhost/Habit-Enforcer/register.php">Sign in here</a></p><br>
        </div>
        ' ;
        $this->BeginSignIn();
    } else {
        echo "You are already connected" ;
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php
    include "head.php"
    ?>
    <body>
        <?php include "header.php" ;
        $login = new Login;
        $login->generatePage() ?>
        
    </body>
    <?php include "footer.php" ;?>
</html>