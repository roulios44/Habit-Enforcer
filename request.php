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
    $hashPassword = password_hash($pwd,PASSWORD_DEFAULT);
    $currentDate = date("Y-m-d");
    $con = openDB();
    $stmt = $con->prepare("INSERT INTO user (username, email, password,lastConnection) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $username, $email, $hashPassword, $currentDate);
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
        $userID = $_SESSION["id"];
        $description = strip_tags($_POST["description"]);
        $difficulty =strip_tags($_POST["difficulty"]);
        $color = strip_tags($_POST["color"]);
        $start = date("Y-m-d");
        $time = strip_tags($_POST["time"]);
        $stmt = $con->prepare("INSERT INTO habit (description, difficulty, color,start, time, userID) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss", $description, $difficulty, $color, $start, $time, $userID);
        $stmt->execute();
        updateDateHabit($userID);
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
    $verify = password_verify($password, $result["password"]) ;
    echo $verify ;
    if($verify)echo "true" ;
    else echo "false" ;
    if($verify)return true ;
    return false;
}
function searchUser(String $usernameSearch):array | bool{
    $db = openDB();
    $usrSearch = "%$usernameSearch%";
    $sql = $db->prepare("SELECT username,id FROM user WHERE username LIKE ?");
    $sql->bind_param("s", $usrSearch);
    $sql->execute();
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
    addUserGroup($groupID, $ownerID);
    deleteTask("userID", $ownerID);
    resetScore($ownerID);
}

function resetScore($id) {
    $con = openDB();
    $stmt = $con->prepare("UPDATE user SET score = 0 WHERE id = $id");
    $stmt->execute();
}

function completeTask(int $done, int $id) {
    $con = openDB();
    $stmt = $con->prepare("SELECT isDone, userID, difficulty FROM habit WHERE id = ?");
    $stmt->bind_param("s",$id);
    $stmt->execute();
    $resultQuery = $stmt->get_result();
    $row = $resultQuery->fetch_assoc();
    $stmt = $con->prepare("UPDATE habit SET isDone = ? WHERE id = ?");
    $stmt->bind_param("ss",$done, $id);
    $stmt->execute();
    if ($row['isDone'] != $done) {
        $score = defineScoreUpdate($done, $row['difficulty'],"instantManaging");
        $stmt = $con->prepare("UPDATE user SET score = score+? WHERE id = ?");
        $stmt->bind_param("ss",$score, $row['userID']);
        $stmt->execute();
        addNewScore($row['userID']);
    }
    mysqli_close($con) ;
}

function defineScoreUpdate($done, $difficulty, $situation){
    $arr = [10,20,30,40,50];
    if ($done == 1 && $situation == "instantManaging") {
        return $arr[$difficulty-1];
    } else if ($done == 0 && $situation == "withTimeManaging") {
        $reverse = array_reverse($arr);
        return -$reverse[$difficulty-1];
    } else {
        return -$arr[$difficulty-1];
    }
}

function alreadyInvited(int $id,int $groupId){
    $db = openDB();
    $sql = $db->prepare("SELECT inviteGroup FROM user WHERE id = ?");
    $sql->bind_param("s",$id);
    $sql->execute();
    $resultQuery = $sql->get_result();
    $arrayGroupInvite = json_decode($resultQuery->fetch_assoc()['inviteGroup']);
    mysqli_close($db);
    if (is_null($arrayGroupInvite))return false;
    if (in_array($groupId,$arrayGroupInvite))return true;
    return false;
}

function addUserGroup(int $groupID, int $userID){
    $con = openDB();
    // setting inviteGroup to [] to remove all invite (if he accept to be in a group, he can't go to another one)
    $stmt = $con->prepare("UPDATE user SET groupID = ?, inviteGroup = DEFAULT WHERE id = ?");
    $stmt->bind_param("ii", $groupID, $userID);
    $stmt->execute();
    mysqli_close($con) ;
    updateGroupMembers($groupID,$userID);
    deleteTask("userID", $userID);
    resetScore($userID);
}

function updateGroupMembers(int $groupID, int $userID){
    $groupMember = json_decode(getInDB("members","group","id",$groupID)["members"]);
    if (!in_array($userID,$groupMember))array_push($groupMember, $userID);
    $con = openDB();
    $stmt = $con->prepare("UPDATE `group` SET members = ? WHERE id = ?");
    $stmt->bind_param("si", json_encode($groupMember) , $groupID);
    $stmt->execute();
    mysqli_close($con) ;
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
function getInDB(string $toSelect, string $table, string $rowToSearch, string|int $condition){
    $db = openDB();
    $sql = $db->prepare("SELECT $toSelect FROM `$table` WHERE $rowToSearch = ?");
    $sql->bind_param("s", $condition);
    $sql->execute();
    $resultQuery = $sql->get_result();
    mysqli_close($db) ;
    return mysqli_fetch_assoc($resultQuery) ;
}

function updateGroupScore($groupID) {
    $groupScore = 0;
    $con = openDB();
    $sql = $con->prepare("SELECT `score` FROM user WHERE groupID = $groupID");
    $sql->execute();
    $result = $sql->get_result();
    while ($row = mysqli_fetch_assoc($result)) {
        $groupScore+=$row['score'];
    }
    $sql2 = $con->prepare("UPDATE `group` SET score = ? WHERE id = ?");
    $sql2->bind_param("ss", $groupScore, $groupID);
    $sql2->execute();
}

function inviteUserGroup(int $userID, int $groupID){
    $invites = json_decode(getInDB("inviteGroup","user","id",$userID)["inviteGroup"]) ;
    if(gettype($invites)!= "NULL"){
        if(!in_array($groupID,$invites)){
            array_push($invites,$groupID) ;
            $db = openDB();
            $sql = $db->prepare("UPDATE user SET inviteGroup = ? WHERE id = ?") ;
            $sql->bind_param("s",[json_encode($invites),$userID]);
            $sql->execute();
        }
    }
}
function habitExpire() {
    $db = openDB();
    $date = date("Y-m-d");
    $stmt = $db->prepare("SELECT `start`, `time`, isDone, difficulty, userID FROM habit");
    $stmt->execute();
    $resultQuery = $stmt->get_result();
    while ($row = mysqli_fetch_assoc($resultQuery)) {
        $habitDate = $row['start'];
        $nbDaysBetween = (strtotime($date)-strtotime($habitDate))/86400;
        if (($row['time']=="daily" && $nbDaysBetween >= 1) ||($row['time']=="weekly" && $nbDaysBetween >= 7)) {
            if ($row['isDone'] == 0) {
                $score = defineScoreUpdate($row['isDone'], $row['difficulty'], "withTimeManaging")*$nbDaysBetween;
                $stmt = $db->prepare("UPDATE user SET score = score+? WHERE id = ?");
                $stmt->bind_param("ss",$score, $row['userID']);
                $stmt->execute();
                addNewScore($row['userID']);
            }
            resetTime($date);
        }
    }
}

function addNewScore($id) {
    $db = openDB();
    $score = getInDB("score", "user","id", $id);
    $date = date("Y-m-d H:i:s");
    $stmt = $db->prepare("UPDATE score SET score = ? WHERE userID = ? AND `date` = ?");
    $stmt->bind_param("sss",$score['score'], $id, $date);
    $stmt->execute();
}

function resetTime($date) {
    $db = openDB();
    $stmt = $db->prepare("UPDATE habit SET `start` = ?, isDone = 0");
    $stmt->bind_param("s", $date);
    $stmt->execute();
}

function deleteTask($condition, $id) {
    $db = openDB();
    $stmt = $db->prepare("DELETE FROM habit WHERE $condition = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
}

function deleteAccount($id) {
    $db = openDB();
    $stmt = $db->prepare("DELETE FROM user WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    mysqli_close($db) ;
}
function updateInDB(string $table, string $rowToUpdate,mixed $newValue, string $tableCondition ,string $condition){
    $db = openDB();
    echo "UPDATE `$table` SET `$rowToUpdate` = '$newValue' WHERE $tableCondition = $condition <br>";
    $sql = $db->prepare("UPDATE `$table` SET `$rowToUpdate` = ? WHERE $tableCondition = ?");
    $sql->execute([$newValue,$condition]);
    mysqli_close($db);
}
function updateDateHabit($id) {
    $date = date("Y-m-d");
    $db = openDB();
    $stmt = $db->prepare("UPDATE user SET lastAddHabit = ? WHERE id = ?");
    $stmt->bind_param("ss", $date,$id);
    $stmt->execute();
}
function getRankings() {
    $con = openDB();
    $query = "SELECT `name` FROM `group` ORDER BY score";
    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div>".$row['name']."</div>";
    }
}