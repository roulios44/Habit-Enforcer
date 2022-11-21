<?php
require "request.php";
session_start();
class groupCreate extends Request{
    function beginGroupCreation(){
        if(empty($_POST["groupName"]))echo "Please enter a name of group";
        elseif(strlen($_POST["groupName"])>25)echo "Max size of name : 25 characters" ;
        else{
            $this->createGroup(strip_tags($_POST["groupName"]));
        }
    }
    
    function createGroup(String $groupName){
        if ($this->alreadyExist($groupName,"group","name")){
            echo "this group name already exist";
        } else {
            $idGroup = $this->dbGroupCreate($groupName,$_SESSION["id"]);
            $_SESSION["groupID"] = $idGroup ;
            header('Location: main.php') ;
        }
    }
    
    function generatePage(){
        if (!is_null($_SESSION["groupID"])){
            echo "<p>You are already in a group</p>" ;
        }else if(!is_null($_SESSION["id"])){
            echo "
            <form action'' method='POST'>
                <p>Name of the group :</p>
                <p><input type=text name=groupName></p>
                <p><input type=submit value='Create Group'></p>
            </form>
            ";
            $this->beginGroupCreation();
        } else echo "<p>You have to be connected to acces this page</p>" ;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <?php include "head.php" ?>
<body>
    <?php include "header.php" ?>
    <?php 
    $createGroup = new groupCreate ;
    $createGroup->generatePage() ?>
</body>
<?php include "footer.php" ?>
</html>
