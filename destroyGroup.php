<?php 
require "request.php" ;
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <?php include "head.php" ?>
<body>
   <?php include "header.php"?> 
   <div class="container">
    <?php
    if (getInDB("ownerID", "group","id", $_SESSION["groupID"])["ownerID"] == $_SESSION["id"]){
        
    }
    ?>
    <form method="POST" action="">
        <input type="submit" value="Destroy current groupe" name="destroy">
    </form>
    <?php 
    destroy();
    ?>
   </div>
</body>
<?php include "footer.php" ?>
</html>
<?php 
function destroy(){
    if($_POST["destroy"]){
        destroyGroup($_SESSION["groupID"]) ;
        $_SESSION["groupID"] = NULL ;
    }
}

?>