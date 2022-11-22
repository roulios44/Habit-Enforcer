<?php require "request.php";
session_start() ;
class checkInvite extends Request{
    function showAllInvite(){
        $allInvite = json_decode($this->getInDB("inviteGroup","user","id",$_SESSION["id"])["inviteGroup"]) ;
        echo "<div class='container'>" ;
        foreach($allInvite as $invite){
            $this->generateInviteBox($invite);
        }
        echo "</div>" ;
    }
    
    function generateInviteBox(int $groupID){
        $infoGroup = $this->getGroupInfo($groupID) ;
        $ownerName = $this->getInDB("username","user","id",$infoGroup["ownerID"])["username"] ;
        echo "
        <div class=userCard>
        <h1>" .$infoGroup["name"] ."</h1><br>
        <p>owner : $ownerName</p><br>
        <p>number of members : ".sizeof(json_decode($infoGroup["members"]))."</p>
        <form method='POST'>
            <p><input type='submit' value='Join this group'></p>
            <input type='hidden' value=$groupID name='groupID'>
        </form>
        </div>
        ";
        $this->acceptInvite();
    }
    
    function acceptInvite(){
        if (isset($_POST["groupID"])){
            $_SESSION["groupID"] = strip_tags($_POST["groupID"]) ;
            $this->addUserGroup(strip_tags($_POST["groupID"]),$_SESSION["id"]);
            header('Location: main.php') ;
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <?php include "head.php"?>
    <?php include "header.php"?>
    <body>
        <?php 
        $checkInvite = new checkInvite ;
        $checkInvite->showAllInvite() ?>
    </body>
    <?php include "footer.php"?>
</html>