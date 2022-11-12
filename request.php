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
    if (isset($_POST["description"],$_POST["difficulty"],$_POST["color"],$_POST["time"])){
        $description = $_POST["description"];
        $difficulty = $_POST["difficulty"];
        $color = $_POST["color"];
        $start = date("Y-m-d H:i:s");
        $time = $_POST["time"];
        $userID = $_SESSION["id"];
        $stmt = $con->prepare("INSERT INTO habit (description, difficulty, color,start, time, userID) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss", $description, $difficulty, $color, $start, $time, $userID);
        $stmt->execute();
    }else {
        echo "Please fill all the required fields to add an habit";
    }
}

function checkIfDone(int $id) : bool{
    $con = openDB();
    $stmt = $con->prepare("SELECT isDone FROM habit WHERE id = ?");
    $stmt->bind_param("s",$id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = mysqli_fetch_assoc($result)) {
        return $row['isDone'];
    }
}

function alreadyExist(String $toSearch,String $table,String $row ) : bool{
    $db = openDB();
    $sql = $db->prepare("SELECT count(*) AS TOTAL FROM `$table` WHERE $row = ?");
    $sql->bind_param("s",$toSearch);
    $sql->execute();
    $resultQuery = $sql->get_result();
    $result = mysqli_fetch_assoc($resultQuery) ;
    mysqli_close($db) ;
    if ($result['TOTAL']==0)return false;
    return true;
}
function checkPassword(String $username, String $password){
    $db = openDB();
    $sql = $db->prepare("SELECT password FROM user WHERE username = ?");
    $sql->bind_param("s",$username);
    $sql->execute();
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
    $arrayMembers = json_encode([$ownerID]) ;
    $db = openDB();
    $sql = $db->prepare("INSERT INTO `group` (name,ownerID,members) VALUES (?,?,?)");
    $sql->bind_param("sis", $groupName, $ownerID,$arrayMembers);
    $sql->execute();
    $groupID = $db->insert_id;
    mysqli_close($db) ;
    addUserGroup($groupID, $ownerID) ;
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
    mysqli_close($db);
    if (is_null($arrayGroupInvite))return false;
    if (in_array($groupId,$arrayGroupInvite))return true;
    return false;
}

function getID(string $username) : int {
    $db = openDB();
    $stmt = $db->prepare("SELECT id FROM user WHERE username = ?");
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $resultQuery = $stmt->get_result();
    mysqli_close($db);
    return $resultQuery->fetch_assoc()["id"];
}

function getGroupID(int $id){
    $db = openDB();
    $stmt = $db->prepare("SELECT groupID FROM user WHERE id = ?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $resultQuery = $stmt->get_result();
    mysqli_close($db);
    return $resultQuery->fetch_assoc()["groupID"];
}

function addUserGroup(int $groupID, int $userID){
    $con = openDB();
    // setting inviteGroup to [] to remove all invite (if he accept to be in a group, he can't go to another one)
    $stmt = $con->prepare("UPDATE user SET groupID = ?, inviteGroup = DEFAULT WHERE id = ?");
    $stmt->bind_param("ii", $groupID, $userID);
    $stmt->execute();
    mysqli_close($con) ;
    updateGroupMembers($groupID,$userID) ;
}

function getMembersGroup(int $idGroup) : array{
    $db = openDB();
    $stmt = $db->prepare("SELECT members FROM `group` WHERE id = ?");
    $stmt->bind_param("i",$idGroup);
    $stmt->execute();
    $resultQuery = $stmt->get_result();
    $idArray = json_decode(mysqli_fetch_assoc($resultQuery)["members"]);
    return $idArray ;
}

function updateGroupMembers(int $groupID, int $userID){
    $groupMember = getMembersGroup($groupID);
    if (!in_array($userID,$groupMember))array_push($groupMember, $userID);
    $con = openDB();
    $stmt = $con->prepare("UPDATE `group` SET members = ? WHERE id = ?");
    $stmt->bind_param("si", json_encode($groupMember) , $groupID);
    $stmt->execute();
    mysqli_close($con) ;
}

function getinvite(int $id) : array{
    $db = openDB();
    $stmt = $db->prepare("SELECT inviteGroup FROM user WHERE id = ?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $resultQuery = $stmt->get_result();
    $inviteArray = json_decode(mysqli_fetch_assoc($resultQuery)["inviteGroup"]);
    mysqli_close($db);
    return $inviteArray;
}

function getGroupInfo(int $groupID) : array{
    $db = openDB();
    $stmt = $db->prepare("SELECT name,ownerID,members FROM `group` WHERE id = ?");
    $stmt->bind_param("i",$groupID);
    $stmt->execute();
    $resultQuery = $stmt->get_result();
    mysqli_close($db);
    return mysqli_fetch_assoc($resultQuery);
}

function getUsername(int $userID) : string{
    $db = openDB();
    $stmt = $db->prepare("SELECT username FROM user WHERE id = ?");
    $stmt->bind_param("i",$userID);
    $stmt->execute();
    $resultQuery = $stmt->get_result();
    mysqli_close($db);
    return mysqli_fetch_assoc($resultQuery)["username"];
}
function getInDB(string $toSelect, string $table, string $rowToSearch, string|int $condition){
    $db = openDB();
    $sql = $db->prepare("SELECT $toSelect FROM `$table` WHERE $rowToSearch = ?");
    $sql->execute([$condition]);
    $resultQuery = $sql->get_result();
    mysqli_close($db) ;
    return mysqli_fetch_assoc($resultQuery) ;
}