<!DOCTYPE html>
<html lang="en">
<?php include "head.php"?>
<body>
    <?php include "header.php"?>
    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">
        <div class="signup">
            <form>
                <label for="chk" aria-hidden="true">Sign up</label>
				<input type="text" name="txt" placeholder="User name" required="">
				<input type="email" name="email" placeholder="Email" required="">
				<input type="password" name="pswd" placeholder="Password" required="">
				<button>Sign up</button>
            </form>
        </div>
        <div class="login">
            <form>
                <label for="chk" aria-hidden="true">Login</label>
				<input type="email" name="email" placeholder="Email" required="">
				<input type="password" name="pswd" placeholder="Password" required="">
				<button>Login</button>
            </form>
        </div>
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