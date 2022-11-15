<?php
require "request.php";
session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <?php include "head.php" ?>
<body>
    <?php include "header.php" ?>
    <form action="" method="POST">
        <p>Name of the group :</p>
        <p><input type=text name=groupName></p>
        <p><input type=submit value="Create Group"></p>
    </form>
    <?php beginGroupCreation()?>
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

?>