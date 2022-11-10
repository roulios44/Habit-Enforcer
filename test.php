<?php 
require "request.php";
session_start() ;
?>
<!DOCTYPE html>
<html lang="en">
    <?php require "head.php" ?>
    <body>
    <?php require "header.php" ?>
        <?php 
        echo $_SESSION["id"] ;
        $test = getMembersGroup($_SESSION["groupID"]);
        print_r($test) ;
        ?>
</body>
<?php require "footer.php" ?>
</html>