<!DOCTYPE html>
<html lang="en">
    <?php include "head.php" ?>
<body>
    <?php
    include 'request.php';
    $datetime = date("Y-m-d H:i:s");
    $con = openDB();
    addUserDB("oui", "oui@hotmail.com", "non");
    $query = "SELECT username FROM user";
    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        print_r($row);
    }
    ?>
</body>
<?php include "footer.php"?>

</html>