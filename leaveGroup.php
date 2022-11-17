<?php require "request.php";
session_start();

class leaveGroup extends Request{
    public function leaveGroup(){
        if($_POST["leave"]){
            echo $_SESSION["id"];
            $this->updateInDB("user","groupID",NULL,"id",$_SESSION["id"]);
            $this->removeGroupMembers() ;
        }
    }
    private function removeGroupMembers(){
        $members = json_decode($this->getInDB("members","group","id",$_SESSION["groupID"])["members"]) ;
        if (($key = array_search($_SESSION["id"], $members)) !== false) {
            unset($members[$key]);
        }
        $this->updateInDB("group","members",json_encode($members), "id",$_SESSION["groupID"]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <?php
    include "head.php"
    ?>
    <body>
        <?php include "header.php" ?>
        <?php
        if(!gettype($_SESSION["id"])){
            echo "<p>You have to be connected to acces this</p>" ;
        }
        if(gettype($_SESSION["groupID"]) == "integer" ){
            echo "
            <form method='POST' action=''>
                <input type='submit' value='Leave group' name='leave'>
            </form>
            ";
            $leaveGroup = new leaveGroup ;
            $leaveGroup->leaveGroup() ;
        } else {
            echo"<p>You have to be in a group to acces to this Page</p><br>";
        }
        ?>
    </body>
    <?php include "footer.php" ;?>
</html>