<?php require "request.php";
session_start() ?>

<!DOCTYPE html>
<html>
    <?php include "head.php"?>
    <?php include "header.php"?>
    <body>
        <?php inviteUser() ?>
    </body>
    <?php include "footer.php"?>
</html>

<?php
function inviteUser(){
    if(($_POST["idUser"])){
        inviteUserGroup($_POST["idUser"], $_SESSION["groupID"]);
        print_r(getInvite($_POST["idUser"])) ;
    }
}
?>