<?php
function openDB() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    try {
        $con = mysqli_connect($servername, $username, $password, "habit");
    } catch (Exception $e){
        echo $e;
        die("couldn't connect to DB");
    }
    return $con;
}

function addUserDB(String $username, String $email, String $pwd) {
    $currentDate = date("Y-m-d H:i:s");
    $con = openDB();
    $stmt = $con->prepare("INSERT INTO user (username, email, password,lastConnection) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $username, $email, $pwd, $currentDate);
    $stmt->execute();
}
function alreadyExist(String $toSearch,String $table,String $row ) : bool{
    $db = openDB();
    $sql = $db->prepare("SELECT count(*) as TOTAL FROM $table WHERE $row = ?");
    $sql->execute([$toSearch]);
    $result = $sql->get_result();
    mysqli_close($db) ;
    if ($result->current_field==0)return false;
    return true;
}