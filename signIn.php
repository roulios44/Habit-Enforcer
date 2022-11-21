<?php require_once "request.php" ;

class Login extends Request{
    public function BeginSignIn(){
        if(empty($_POST["username"]) || empty($_POST["password"]))echo "<p>Please fill all fields please</p><br>";
        else $this->SignIn(strip_tags($_POST["username"]), strip_tags($_POST["password"]));
    }

    public function SignIn(String $username, String $password){
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

    public function generatePage(){
        if (is_null($_SESSION["id"])){
            echo '
            <div class="form">
            <h1>Login</h1>
            <form action="" method="POST">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name ="password" placeholder="Password">
            <div class="connectButton">
            <input type="submit" value="Connect">
            </div>
            </form>
            </div>
            <div class="connectMessage">
            <p>No Account? <a href="http://localhost/Habit-Enforcer/register.php">Sign in here</a></p><br>
            </div>
            ' ;
            $this->BeginSignIn();
        } else {
            header('Location: main.php') ;
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
        <?php
        $login = new Login;
        $login->generatePage() ?>
        
    </body>
    <?php include "footer.php" ;?>
</html>