<?php 
require_once "request.php";
session_start() ;
class Register extends Request {
    public function generatePage(){
        if (!is_null($_SESSION["id"])){
            echo "<p>You are already connected</p>" ;
        } else {
            echo "
            <div class='form'>
            <form action='register.php' method='POST'>
                <p>Username: <input type='text' name='username'></p>
                <p>Password: <input type='text' name = 'password'></p>
                <p>E-mail Adress: <input type='text' name = 'mail'></p>
                <p><input type='submit' name='submit' value='Create your profile !'></input></p>
            </form>
            <?php beginRegister() ;?>
        </div>
            " ;
            $this->beginRegister();
        }
    }
    
    public function beginRegister(){
        $username = strip_tags($_POST["username"]);
        $password = strip_tags($_POST["password"]);
        $mail = strip_tags($_POST["mail"]);
        if(empty($username) || empty($password) || empty($mail)){
            echo "Please fill all fields please<br>";
        } else {
            $this->register();
        }
    }
    public function register(){
        $alreadyUse = false;
        $username = strip_tags($_POST["username"]);
        $password = strip_tags( $_POST["password"]);
        $mail = strip_tags($_POST["mail"]);
        if ($this->alreadyExist($username,"user","username")){
            echo "username '$username' is already use, please chose another one <br>" ;
            $alreadyUse = true;
        }
        if ($this->alreadyExist($mail,"user","email")){
            echo "mail '$mail' is already use for a account <br>";
            $alreadyUse = true;
        }
        if(!$alreadyUse){
            $this->addUserDB($username,$mail,$password);
            header('Location: signIn.php');
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<?php include "head.php" ;?>
<body>
    <?php include "header.php"?>
    <?php 
    $register = new Register;
    $register->generatePage() ;
    ?>
</body>
<?php include "footer.php"?>
</html>