<?php
require "request.php";
session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <?php include "head.php" ?>
<body>
    <?php include "header.php" ?>
    <?php generatePage() ?>
</body>
<?php include "footer.php" ?>
</html>

<?php
function beginGroupCreation(){
    $groupName = strip_tags($_POST["groupName"]);
    if(empty($groupName))echo "Please enter a name of group";
    elseif(strlen($groupName)>25)echo "Max size of name : 25 characters" ;
    else{
        createGroup($groupName);
    }
}

function createGroup(String $groupName){
    if (alreadyExist($groupName,"group","name")){
        echo "this group name already exist";
    } else {
        $idGroup = dbGroupCreate($groupName,$_SESSION["id"]);
        $_SESSION["groupID"] = $idGroup ;
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
        beginGroupCreation();
    } else echo "<p>You have to be connected to acces this page</p>" ;
}

?>