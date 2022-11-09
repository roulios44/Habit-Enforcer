<?php

use function PHPSTORM_META\type;

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
    mysqli_close($con) ;
}

function refreshLastConnection(int $userID) {
    $con = openDB();
    $currentDate = date("Y-m-d H:i:s");
    $stmt = $con->prepare("UPDATE user SET lastConnection = ? WHERE id = ?");
    $stmt->bind_param("ss", $currentDate, $userID);
    $stmt->execute();
    mysqli_close($con) ;
}

function createHabit() {
    $con = openDB();
    // $description = $_GET["description"];
    // $difficulty = $_GET["difficulty"];
    // $color = $_GET["color"];
    // $start = date("Y-m-d H:i:s");
    // $time = $_GET["time"];
    // //TO DO
    // $userID = 1;
    // $stmt = $con->prepare("INSERT INTO habit (description, difficulty, color,start, time, userID) VALUES (?,?,?,?,?,?)");
    // $stmt->bind_param("ssssss", $description, $difficulty, $color, $start, $time, $userID);
    // $stmt->execute();
}

function alreadyExist(String $toSearch,String $table,String $row ) : bool{
    $db = openDB();
    $sql = $db->prepare("SELECT count(*) AS TOTAL FROM `$table` WHERE $row = ?");
    $sql->execute([$toSearch]);
    $resultQuery = $sql->get_result();
    $result = mysqli_fetch_assoc($resultQuery) ;
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
    $sql = $db->prepare("SELECT username,id FROM user WHERE username LIKE ?");
    $sql->execute((["%$usernameSearch%"]));
    $resultQuery = $sql->get_result();
    if ($resultQuery->num_rows<=0){
        mysqli_close($db) ;
        return false;
    }
    $resultArray = [] ;
    while($row = $resultQuery->fetch_assoc()){
        array_push($resultArray, $row) ;
    }
    mysqli_close($db) ;
    return $resultArray;
}
function dbGroupCreate(String $groupName, int $ownerID){
    $db = openDB();
    $sql = $db->prepare("INSERT INTO `group` (name,ownerID) VALUES (?,?)");
    $sql->bind_param("si", $groupName, $ownerID);
    $sql->execute();
    mysqli_close($db) ;
}
function completeTask(int $done, int $id) {
    $con = openDB();
    $stmt = $con->prepare("UPDATE habit SET isDone = ? WHERE id = ?");
    $stmt->bind_param("ss",$done, $id);
    $stmt->execute();
    mysqli_close($con) ;
}

function alreadyInvited(int $id,int $groupId){
    $db = openDB();
    $sql = $db->prepare("SELECT inviteGroup FROM user WHERE id = ?");
    $sql->execute(([$id]));
    $resultQuery = $sql->get_result();
    $arrayGroupInvite = json_decode($resultQuery->fetch_assoc()['inviteGroup']);
    if (is_null($arrayGroupInvite))return false;
    if (in_array($groupId,$arrayGroupInvite))return true;
    return false;
}