<?php
require "request.php" ;
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<?php include "head.php" ?>
<body>
<?php include "header.php" ?>
    <?php 
    printProfile();
    ?>
</body>
<?php include "footer.php" ?>
</html>

<?php 
function printProfile(){
    $infoUser = getInDB("*", "user", "id", $_SESSION["id"]) ;
    if($_SESSION["id"]){
        echo "<h1>Welcome   " . $infoUser["username"] . "</h1> ";
    }else{
        echo "<p>You are not connected</p>" ;
    }
}
?>