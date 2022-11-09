<!DOCTYPE html>
<html lang="en">
    <?php require "head.php" ?>
    <body>
    <?php require "header.php" ?>
    <?php 
    require "request.php";
    $test = alreadyInvited(45,4);
    if ($test === true)echo "true";
    else echo "false" ;
    ?>
</body>
<?php require "footer.php" ?>
</html>