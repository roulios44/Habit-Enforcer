<?php 
require "request.php" ;
session_start();

class DestroyGroup extends Request{
    public function destroy(){
        if($_POST["destroy"]){
            $this->destroyGroup($_SESSION["groupID"]) ;
            $_SESSION["groupID"] = NULL ;
        }
    }
    private function checkIfOwner() : bool{
        if ($this->getInDB("ownerID", "group","id", $_SESSION["groupID"])["ownerID"] == $_SESSION["id"])return true;
        return false ;
    }
    public function generatePage(){
        if ($this->checkIfOwner()){
            echo '
            <form method="POST" action="">
                <input type="submit" value="Destroy current groupe" name="destroy">
            </form>
            ' ;
            $this->destroy();
        } else {
            echo "<p>You are not the owner of the group</p>";
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">
    <?php include "head.php" ?>
<body>
   <?php include "header.php"?> 
   <div class="container">
    
    <?php 
    $destroy = new DestroyGroup;
    $destroy->generatePage();
    ?>
   </div>
</body>
<?php include "footer.php" ?>
</html>