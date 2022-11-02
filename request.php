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
    $sql = $db->prepare("SELECT count(*) AS TOTAL FROM `$table` WHERE $row = ?");
    $sql->execute([$toSearch]);
    $resultQuery = $sql->get_result();
    $result = mysqli_fetch_assoc($resultQuery) ;
    print_r($result['TOTAL']);
    mysqli_close($db) ;
    if ($result['TOTAL']==0)return false;
    return true;
}
function checkPassword(String $username, String $password){
    $db = openDB();
    $sql = $db->prepare("SELECT password FROM user WHERE password = ?");
    $sql->execute(([$username]));
    $resultQuery = $sql->get_result();
    $result = mysqli_fetch_assoc($resultQuery) ;
    mysqli_close($db) ;
    if($result["password"] == $password)return true ;
    return false;
}
function searchUser(String $usernameSearch):array | bool{
    $db = openDB();
    $sql = $db->prepare("SELECT username,id FROM user WHERE username = ?");
    $sql->execute(([$usernameSearch]));
    $resultQuery = $sql->get_result();
    if ($resultQuery->num_rows<=0){
        mysqli_close($db) ;
        return false;
    }
    $result = mysqli_fetch_assoc($resultQuery) ;
    mysqli_close($db) ;
    return $result;
}
function dbGroupCreate(String $groupName, int $ownerID){
    $db = openDB();
    $stmt = $db->prepare("INSERT INTO group (name,ownerID) VALUES (?,?)");
    $stmt->bind_param("ss", $groupName, $ownerID);
    $stmt->execute();
    mysqli_close($db) ;
}