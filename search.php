<?php require "request.php" ?>
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
    $searchValue = $_POST['searchUser'] ;
    if(!empty($_POST['searchUser'])){
        search($searchValue);
    }
}
function search(String $searchValue){
    $userFound = searchUser($searchValue) ;
    if($userFound === false)echo "<p>No user with this pseudo has been found.</p>" ;
    else{
        echo "<div class='container'>" ;
        for($i=0;$i<sizeOf($userFound);$i++){
            createUserResultCard($userFound[$i]);
        }
        echo "</div>" ;
    }
}

function createUserResultCard(array $user){
    //TODO remove idGroup by the current User idGroup (if he get one) else dont echo invite button
    $idGroup = 1;
    $username = $user["username"] ;
    $id = $user["id"];
    $inviteMessage = "<p><input type='submit' value='invite'></p>";
    if(alreadyInvited($id,$idGroup))$inviteMessage = "<p>Already invited</p>" ;
    echo ("<div class='userCard'>
    $username
    <form action='' method='POST'>
            $inviteMessage
            <input type='hidden' value=$id name='idUser'>
       </form>
    </div>") ;
}
function inviteUser(){

}

?>