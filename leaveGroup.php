<?php require "request.php";
session_start();
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
            leaveGroup() ;
        } else {
            echo"<p>You have to be in a group to acces to this Page</p><br>";
        }
        ?>
    </body>
    <?php include "footer.php" ;?>
</html>
<?php 
function leaveGroup(){
    if($_POST["leave"]){
        echo $_SESSION["id"];
        updateInDB("user","groupID",NULL,"id",$_SESSION["id"]);
        removeGroupMembers() ;
    }
}
function removeGroupMembers(){
    $members = json_decode(getInDB("members","group","id",$_SESSION["groupID"])["members"]) ;
    if (($key = array_search($_SESSION["id"], $members)) !== false) {
        unset($members[$key]);
    }
    updateInDB("group","members",json_encode($members), "id",$_SESSION["groupID"]);
}
?>