
<?php
require "request.php" ;
session_start(); 

class Profil extends Request{
    function printProfil(){
        $infoUser = $this->getInDB("*", "user", "id", $_SESSION["id"]) ;
        if($_SESSION["id"]){
            echo "<h1>Welcome   " . $infoUser["username"] . "</h1> ";
        }else{
            echo "<p>You are not connected</p>" ;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include "head.php" ?>
<body>
<?php include "header.php" ;
$profil = new Profil;
$profil->printProfil() ;
?>
</body>
<?php include "footer.php" ?>
</html>