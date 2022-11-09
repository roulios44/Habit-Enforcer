<?php require "request.php"?>
<!DOCTYPE html>
<html lang="en">
    <?php
    include "head.php"
    ?>
    <body>
        <?php include "header.php" ?>
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
    </body>
    <?php include "footer.php" ;?>
</html>

<?php 
function BeginSignIn(){
    $username = $_POST["username"];
    $password = $_POST["password"];
    if(empty($username) || empty($password)){
        echo "<p>Please fill all fields please</p><br>";
    } else {
        SignIn($username, $password);
    }
}

function SignIn(String $username, String $password){
    if (!alreadyExist($username,"user", "username"))echo "This username is unknow of our website, you can create a account with this one";
    else {
        if (checkPassword($username,$password)){
            session_start();
            $_SESSION["username"] = $username;
            $_SESSION["id"] = getID($username);
            header('Location: main.php');
        } else {
            echo "<p>Wrong password, try again</p>" ;
        }

    }
}
?>