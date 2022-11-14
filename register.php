<?php require("request.php") ;?>
<!DOCTYPE html>
<html lang="en">
<?php include "head.php"?>
<body>
    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">
        <div class="signup">
            <form>
                <label for="chk" aria-hidden="true">Sign up</label>
				<input type="text" name="username" placeholder="User name" >
				<input type="text" name="mail" placeholder="Email" >
				<input type="text" name="password" placeholder="Password" >
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
        <?php beginRegister() ;?>
    </div>
</body>
</html>




<?php
function beginRegister(){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        echo "HI bitch <br>" ;
        echo $_POST["username"] ;
        $username = $_POST["username"];
        $mail = $_POST["mail"];
        $password = $_POST["password"];
        if(empty($username) || empty($mail) || empty($password)){
            echo "Please fill all fields please<br>";
        } else {
            register($username, $mail, $password);
        }
    }
}
function register(){
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