<?php require "request.php";
session_start() ?>

<!DOCTYPE html>
<html>
    <?php include "head.php"?>
    <?php include "header.php"?>
    <body>
        <?php showAllInvite() ?>
    </body>
    <?php include "footer.php"?>
</html>

<?php 
function showAllInvite(){
    $allInvite = json_decode(getInDB("inviteGroup","user","id",$_SESSION["id"])["inviteGroup"]) ;
    echo "<div class='container'>" ;
    foreach($allInvite as $invite){
        generateInviteBox($invite);
    }
    echo "</div>" ;
}

function generateInviteBox(int $groupID){
    $infoGroup = getGroupInfo($groupID) ;
    $ownerName = getInDB("username","user","id",$infoGroup["ownerID"])["username"] ;
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
    acceptInvite();
}

function acceptInvite(){
    if ($_POST["groupID"])addUserGroup(strip_tags($_POST["groupID"]),$_SESSION["id"]);
}
?>