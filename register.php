<?php require("request.php") ;?>
<!DOCTYPE html>
<html lang="en">
<?php include "head.php"?>
<body>
    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">
        <div class="signup">
            <form action="" method="POST">  
                <label for="chk" aria-hidden="true">Sign up</label>
				<input type="username" name="SignUpusername" placeholder="Username" >
				<input type="text" name="SignUpmail" placeholder="Email" >
				<input type="password" name="SignUppassword" placeholder="Password" >
				<button>Sign up</button>
            </form>
        </div>
        <div class="login">
			<form action="" method="POST">
				<label for="chk" aria-hidden="true">Sign In</label>
				<input type="username" name="SignInpusername" placeholder="username" required="">
				<input type="password" name="SignInpassword" placeholder="password" required="">
				<button type="submit" value="connect">Sign In</button>
			</form>
		</div>
        <?php beginRegister()?>
        <?php BeginSignIn()?>
    </div>
</body>
</html>


<?php
function beginRegister(){
    if($_SERVER["REQUEST METHOD"] == "POST"){
        $SignUpusername = strip_tags($_POST["SignUpusername"]);
        $SignUppassword = strip_tags($_POST["SignUppassword"]);
        $SignUpmail = strip_tags($_POST["SignUpmail"]);
        if(empty($SignUpusername) || empty($SignUppassword) || empty($SignUpmail)){
            echo "Please fill all fields please<br>";
        } else {
            register();
        }
    }
}
function register(){
    if($_SERVER["REQUEST METHOD"] == "POST"){
        $alreadyUse = false;
        $SignUpusername = strip_tags($_POST["SignUpusername"]);
        $SignUppassword = strip_tags( $_POST["SignUppassword"]);
        $SignUpmail = strip_tags($_POST["SignUpmail"]);
        if ((alreadyExist($SignUpusername,"SignUpuser","SignUpusername"))){
            echo "SignUpusername '$SignUpusername' is already use, please chose another one <br>" ;
            $alreadyUse = true;
        }
        if (alreadyExist($SignUpmail,"SignUpuser","SignUpemail")){
            echo "mail '$SignUpmail' is already use for a account <br>";
            $alreadyUse = true;
        }
        if(!$alreadyUse){
            addUserDB($SignUpusername,$SignUpmail,$SignUppassword);
            header('Location: signIn.php');
        }
    }
}

function BeginSignIn(){
    if($_SERVER["REQUEST METHOD"] == "POST"){
        $SignInusername = $_POST["SignInusername"];
        $SignInpassword = $_POST["SignInpassword"];
        if(empty($SignInusername) || empty($SignInpassword)){
            echo "<p>Please fill all fields please</p><br>";
        } else {
            SignIn($SignInusername, $SignInpassword);
        }
    }
}

function SignIn(String $SignInusername, String $SignInpassword){
    if (!alreadyExist($SignInusername,"SignInuser", "SignInusername"))echo "This username is unknow of our website, you can create a account with this one";
    else {
        if (checkPassword($SignInusername,$SignInpassword)){
            session_start();
            $_SESSION["SignInusername"] = $SignInusername;
            $_SESSION["id"] = getID($SignInusername);
            $_SESSION["groupID"] = getGroupID($_SESSION["id"]) ;
            header('Location: main.php');
        } else {
            echo "<p>Wrong password, try again</p>" ;
        }

    }
}   
?>