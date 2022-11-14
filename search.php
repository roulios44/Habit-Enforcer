<?php require "request.php";
session_start() ?>

<!DOCTYPE html>
<html>
    <?php include "head.php"?>
    <?php include "header.php"?>
    <body>
        <div class="search">
            <form action="" method="POST">
                <p>Search a user: <input type="text" name="searchUser"></p>
                <p><input type="submit" name="submit" value="search"></input></p>
            </form>
        </div>
        <?php beginSearch();?>
    </body>
    <?php include "footer.php"?>
</html>


<?php
function beginSearch(){
    if(!empty($_POST["search"]))inviteUser($_POST["search"]);
    $searchValue = $_POST['searchUser'] ;
    if(!empty($searchValue)){
        search($searchValue);
    }
}
function search(String $searchValue){
    $userFound = searchUser($searchValue) ;
    if($userFound === false)echo "<p>No user with this pseudo has been found.</p>" ;
    else{
        echo "<div class='container'>" ;
        for($i=0;$i<sizeOf($userFound);$i++){
            createUserResultCard($userFound[$i], $searchValue);
        }
        echo "</div>" ;
    }
}

function createUserResultCard(array $user,string $search){
    $username = $user["username"] ;
    $id = $user["id"];
    $inviteMessage = getInviteMessage($id);
    echo "<div class='userCard'>
     <p>$username</p>
    <form method='POST'>
            $inviteMessage
            <input type='hidden' value='$id' name='idUser'>
            <input type='hidden' value='$search' name='search'>
       </form>
    </div>" ;
}

function inviteUser(string $search){
    if(!empty($_POST["idUser"])){
        inviteUserGroup($_POST["idUser"], $_SESSION["groupID"]);
        search($search);
    }
}

function getInviteMessage(int $userID) : string{
    if (!$_SESSION["id"])return "<p>You have to be connected to invite some one to a group</p>" ;
    if (is_null($_SESSION["groupID"]))return "<p>You are not in a group, join one to invite a user to a group</p>" ;
    else if(getGroupID($userID))return "<p>Already in a groupe</p>" ;
    else if(alreadyInvited($userID,$_SESSION["groupID"]))return "<p>Already invited</p>" ;
    return "<p><input type='submit' value='invite'></p>";
}
?>