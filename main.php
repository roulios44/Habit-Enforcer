<?php 
session_start(); ?>
<?php require_once "request.php" ?>
<?php 
class mainPage extends Request{

    public function checkIfConnected(){
        if(is_null($_SESSION["id"]))header('Location: signIn.php') ;
    }

    public function checkIfGroup(){
        if (isset($_POST["viewInvite"])) {
            header('Location: checkInvite.php');
            } else if (isset($_POST["createGroup"])) {
                header('Location: createGroup.php');
            } else if (isset($_POST["invite"])) {
                header('Location: search.php');
            }
    }
    public function rankUser(){
        $con = $this->openDB();
        $query = "SELECT `name` FROM `group` ORDER BY score";
        $result = mysqli_query($con, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div>".$row['name']."</div>";
        }
    }

    public function startCreateHabit(){
        
        $date = date("Y-m-d");
        $lastAddHabit = $this->getInDB("lastAddHabit", "user","id",$_SESSION["id"])["lastAddHabit"];
        $nbDaysBetween = (strtotime($date)-strtotime($lastAddHabit)/86400) ;
        if ($nbDaysBetween >1) {
            $this->createHabit();
        } else {
            echo "<div>Already added an habit</div>";  
        }
    }

    public function generateHabit(){
        $con = $this->openDB();
        $query = "SELECT description, id, color FROM habit WHERE userID = $_SESSION[id] ORDER BY color";
        $result = mysqli_query($con, $query);
        $nbRows = mysqli_num_rows($result);
        $IDArray = [];
        echo "<form id=sendDone method=post>";
        echo "<div id=allHabits>";
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($IDArray, $row['id']);
            $this->habitExpire($row['id']);
            if (isset($_POST['changeHabit'])) {
                $isDone = (isset($_POST["isDone_".$row['id']]) ? '1' : '0');
                $this->completeTask($isDone,$row['id']);
            } else if (isset($_POST["removeHabit".$row['id']])) {
                $this->deleteTask($row['id']);
                continue;
            }
            $done = $this->checkIfDone($row['id']);
            $sayDone = "Done";
            $check = "";
            if ($done) {
                $check = "checked";
                $sayDone = "Undone";
            }
            echo "<div class=habitStyle style=background-color:".$row['color']."> <input type=checkbox name=isDone_".$row['id']." id=isDone_".$row['id']." value=done ".$check.">".$row['description']." <input type=submit name=removeHabit".$row['id']." value=x> </div>";
        }
        echo "<input type=submit name=changeHabit>";
        echo "</div>";
        echo "</form>";
    }

    public function habitToDo(){
        $con = $this->openDB();
        $query = "SELECT description FROM habit WHERE isDone = False and userID = $_SESSION[id]";
        $result = mysqli_query($con, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div>".$row['description']."</div>";
        }
    }

    public function findAName(){
        $con = $this->openDB();
        $groupID = $this->getInDB("groupID","user","id",$_SESSION["id"])["groupID"];
        if ($groupID != null) {
            echo "<div id=totalScore> Total score =".$this->getInDB("score","group","id" ,$groupID)["score"]." </div>";
            echo "<form method=POST id=invite><input type=submit name=invite value=invite people></form>";
            $stmt = $con->prepare("SELECT username FROM user WHERE groupID = ?");
            $stmt->bind_param("s",$groupID);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div>".$row['username']."</div>";
            }
        } else {
                echo "<div id=totalScore> Total score =".$this->getInDB("score","user","id" ,$_SESSION["id"])["score"]." </div>";
                echo "<form method=POST id=viewInvite><input type=submit name=viewInvite value=invitations></form>";
                echo "<form method=POST id=createGroup><input type=submit name=createGroup value=Create&nbsp;group></form>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php include "head.php" ?>
<body>
    <?php include "header.php"?>
    <?php                 
        $main = new mainPage;
        $main->checkIfConnected();
        $main->checkIfGroup();
    ?>
    <div id="allColumns" name="allColumns" class="allColumns"> 
        <div id="ranking" name="ranking" class="ranking" class="aColumn">Ranking
            <?php
                $main->rankUser();
            ?>
            </div>
            <div id="habits" name="habits" class="habits" class="aColumn">Habits
                <button id="openModal">Create habit</button>
                <div id="modal" class="modal">
                    <?php echo "hi" ?>
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <form method="POST">
                            Description :<input type="text" id="description" name="description">
                            <div class="star-widget">Difficulty :
                                <input type="radio" name="difficulty" id="difficulty-5" value="5">
                                <label for="difficulty-5" class="fas fa-star"></label>
                                <input type="radio" name="difficulty" id="difficulty-4" value="4">
                                <label for="difficulty-4" class="fas fa-star"></label>
                                <input type="radio" name="difficulty" id="difficulty-3" value="3">
                                <label for="difficulty-3" class="fas fa-star"></label>
                                <input type="radio" name="difficulty" id="difficulty-2" value="2">
                                <label for="difficulty-2" class="fas fa-star"></label>
                                <input type="radio" name="difficulty" id="difficulty-1" value="1">
                                <label for="difficulty-1" class="fas fa-star"></label>
                            </div>
                            Color :<input type="color" name="color" value="#333333" list="colors">
                            Period of time : <select name="time" id="time">
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                            </select>
                            <input type="submit" id="submit" value = "Add habit">
                        </form>
                        <?php 
                    $main->startCreateHabit();
                    ?>
                </div>
            </div>
            <?php

            ?>
            <?php
                $main->generateHabit();
            ?>
        </div>
        <div id="toDo" name="toDo" class="toDo" class="aColumn">To Do
        <?php
                $main->habitToDo();
            ?>
        </div>
        <div id="group" name="group" class="group" class="aColumn">Group
            <?php 
                $main->findAName();
            ?>
        </div>
    </div>
<script type="text/javascript">
    var modal = document.getElementById("modal");
    var btn = document.getElementById("openModal");
    var span = document.getElementsByClassName("close")[0];
    btn.onclick = function() {
        modal.style.display = "block";
    }
    span.onclick = function() {
        modal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
</body>

<?php include "footer.php"?>

</html>