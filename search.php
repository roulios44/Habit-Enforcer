<?php require "request.php";
session_start() ;

class Search extends Request{
    public function beginSearch(){
        if(!empty($_POST["search"]))$this->inviteUser($_POST["search"]);
        if(!empty($_POST['searchUser'])){
            $this->search(strip_tags($_POST['searchUser']));
        }
    }
    private function search(String $searchValue){
        $userFound = $this->searchUser($searchValue) ;
        if($userFound === false)echo "<p>No user with this pseudo has been found.</p>" ;
        else{
            echo "<div class='container'>" ;
            for($i=0;$i<sizeOf($userFound);$i++){
                if($userFound[$i]["id"] == $_SESSION["id"])continue ;
                $this->createUserResultCard($userFound[$i], $searchValue);
            }
            echo "</div>" ;
        }
    }
    
    private function createUserResultCard(array $user,string $search){
        $username = $user["username"] ;
        $id = $user["id"];
        $inviteMessage = $this->getInviteMessage($id);
        echo "<div class='userCard'>
         <p>$username</p>
        <form method='POST'>
                $inviteMessage
                <input type='hidden' value='$id' name='idUser'>
                <input type='hidden' value='$search' name='search'>
           </form>
        </div>" ;
    }
    
    private function inviteUser(string $search){
        if(!empty($_POST["idUser"])){
            $this->inviteUserGroup(strip_tags($_POST["idUser"]), $_SESSION["groupID"]);
            $this->search($search);
        }
    }
    
    private function getInviteMessage(int $userID) : string{
        if (!$_SESSION["id"])return "<p>You have to be connected to invite some one to a group</p>" ;
        if (is_null($_SESSION["groupID"]))return "<p>You are not in a group, join one to invite a user to a group</p>" ;
        else if($this->getInDB("groupID","user","id",$userID)["groupID"])return "<p>Already in a groupe</p>" ;
        else if($this->alreadyInvited($userID,$_SESSION["groupID"]))return "<p>Already invited</p>" ;
        return "<p><input type='submit' value='invite'></p>";
    }
}
?>

<!DOCTYPE html>
<html>
    <?php include "head.php"?>
    <?php include "header.php"?>
    <?php
        if(!$_SESSION['id']){
            echo "<p>You have to be connected to acces this</p>";
        } 
        else if (!$_SESSION["groupID"]){
            echo"<p>You have to be in a group to acces to this Page</p><br>";
        }
    ?>
    <body>
        <div class="search">
            <form action="" method="POST">
                <p>Search a user: <input type="text" name="searchUser"></p>
                <p><input type="submit" name="submit" value="search"></input></p>
            </form>
        </div>
        <?php 
        $search = new Search ;
        $search->beginSearch();?>
    </body>
    <?php include "footer.php"?>
</html>
