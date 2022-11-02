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
    require "request.php";
    $userFound = searchUser($searchValue) ;
    if($userFound === false)echo "<p>No user with this pseudo has been found.</p>" ;
    else{
        echo "<p>" ;
        print_r($userFound);
        echo "</p>" ;
    }
}
?>