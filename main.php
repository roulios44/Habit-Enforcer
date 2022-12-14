<?php 
session_start(); ?>
<?php require_once "request.php" ?>
<?php 
class mainPage extends Request{

    public function checkIfConnected(){
        if(is_null($_SESSION["id"]))header('Location: signIn.php') ;
    }

    public function userNavigation(){
        if (isset($_POST["viewInvite"])) {
            header('Location: checkInvite.php');
            } else if (isset($_POST["createGroup"])) {
                header('Location: createGroup.php');
            } else if (isset($_POST["invite"])) {
                header('Location: search.php');
            }
    }
    public function rankGroup(){
        echo "<p>Top 5 Groups</p>";
        $con = $this->openDB();
        $query = "SELECT `name` FROM `group` ORDER BY score";
        $result = mysqli_query($con, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div><p>".$row['name']."</p></div>";
        }
    }

    public function startCreateHabit(){
        $date = date("Y-m-d");
        $lastAddHabit = $this->getInDB("lastAddHabit", "user","id",$_SESSION["id"])["lastAddHabit"];
        $nbDaysBetween = ((strtotime($date)-strtotime($lastAddHabit))/86400) ;
        if ($nbDaysBetween >=1) {
            $this->createHabit();
        } else {
            echo "<div><p>Already added an habit</p></div>";  
        }
    }

    public function generateHabit(){
        $con = $this->openDB();
        $query = "SELECT description, id, color FROM habit WHERE userID = $_SESSION[id] ORDER BY color";
        $result = mysqli_query($con, $query);
        echo "<form id=sendDone method=post>";
        echo "<div id=allHabits>";
        while ($row = mysqli_fetch_assoc($result)) {
            if (isset($_POST['changeHabit'])) {
                $isDone = (isset($_POST["isDone_".$row['id']]) ? '1' : '0');
                $this->completeTask($isDone,$row['id']);
            } else if (isset($_POST["removeHabit".$row['id']])) {
                $this->deleteTask("id",$row['id']);
                continue;
            }
            $done = $this->checkIfDone($row['id']);
            $check = "";
            if ($done) {
                $check = "checked";
            }
            echo "<div class=habitStyle style=background-color:".$row['color']."> <input type=checkbox name=isDone_".$row['id']
            ." id=isDone_".$row['id']." value=done ".$check.">".$row['description']." <input type=submit name=removeHabit".$row['id']." value=x> </div>";
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
            echo "<div><p>".$row['description']."</p></div>";
        }
    }

    public function groupInfo(){
        $con = $this->openDB();
        $userInfo = $this->getInDB("groupID, lastConnection","user","id",$_SESSION["id"]);
        if ($userInfo['groupID'] != null) {
            $this->updateGroupScore($userInfo['groupID']);
            $totalGroupScore = $this->getInDB("score","group","id" ,$userInfo['groupID'])['score'];
            if ($totalGroupScore <0) {
                $this->destroyGroup($userInfo['groupID']);
                $_SESSION["groupID"] = null;
                header('Location: main.php');
            }
            echo "<div id=totalScore> Total score =".$totalGroupScore." </div>";
            echo "<form method=POST id=invite><input type=submit name=invite value=invite people></form>";
            $stmt = $con->prepare("SELECT username, id, lastConnection FROM user WHERE groupID = ?");
            $stmt->bind_param("s",$userInfo['groupID']);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = mysqli_fetch_assoc($result)) {
                $previousScore = $this->getInDB("score", "score", "userID = $row[id] AND `date`", $userInfo['lastConnection']);
                $score = $this->getInDB("score", "user", "id", $row["id"]);
                if ($previousScore == null) {
                    $previousScore['score'] = 0;
                }
                $score['score'] = $score['score'] - $previousScore['score'];
                $lost = "won";
                if ($score['score'] < 0) {
                    $lost = "lost";
                    $score['score'] = abs($score['score']);
                }
                if ($row['username'] == $_SESSION['username']) {
                    echo "<div> You ".$lost." ".$score['score']." point(s) since last connection</div>";
                } else {
                    echo "<div>".$row['username']." ".$lost." ".$score['score']." point(s) since last connection</div>";
                }
            }
            $this->refreshLastConnection($_SESSION['id']);
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
        $main->userNavigation();
    ?>
    <div id="allColumns" name="allColumns" class="allColumns"> 
        <div id="ranking" name="ranking" class="ranking" class="aColumn"><h1>Ranking</h1>
            <?php
                $main->rankGroup();
            ?>
            </div>
            <div id="habits" name="habits" class="habits" class="aColumn"><h1>Habits</h1>
                <button id="openModal">Create habit</button>
                <div id="modal" class="modal">
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
                </div>
                <?php 
                    $main->startCreateHabit();
                    ?>
            </div>
            <?php
                $main->generateHabit();
            ?>
        </div>
        <div id="toDo" name="toDo" class="toDo" class="aColumn"><h1>TODO</h1>
        <?php
                $main->habitToDo();
            ?>
        </div>
        <div id="group" name="group" class="group" class="aColumn"><h1>Group</h1>
            <?php 
                $main->groupInfo();
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