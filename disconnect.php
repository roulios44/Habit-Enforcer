<?php require "request.php";
session_start() ?>

<!DOCTYPE html>
<html>
    <?php include "head.php"?>
    <?php include "header.php"?>
    <body>
        <?php disconnect() ?>
    </body>
    <?php include "footer.php"?>
</html>

<?php
function disconnect(){
    echo $_SESSION["id"] ;
    if($_SESSION["id"]){
        echo "
        <form method='POST'>
            <input type=submit value='Disconnect' name='logOut'>
        </form>
        ";
        if ($_POST["logOut"]){
            session_destroy();
        }
    }else {
        echo "<p>You are not connected</p>" ;
    }
}
?>