<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <?php include "head.php" ?>
<body>
    <?php include "header.php"?>
    <?php include 'request.php';?>
    <?php                 
        if (isset($_POST["viewInvite"])) {
        header('Location: checkInvite.php');
        } else if (isset($_POST["createGroup"])) {
            header('Location: createGroup.php');
        }
    ?>
    <div id="allColumns" name="allColumns" class="allColumns"> 
        <div id="ranking" name="ranking" class="ranking" class="aColumn">Ranking
            <?php getRankings();?>
            </div>
        <div id="habits" name="habits" class="habits" class="aColumn">Habits
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
                    <?php addHabit();
                    function addHabit() {
                        $date = date("Y-m-d");
                        $stmt = $con->prepare("SELECT lastAddHabit FROM user WHERE id = $_SESSION[id]");
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $nbDaysBetween = (strtotime($date)-strtotime(mysqli_fetch_assoc($result)['lastAddHabit']))/86400;
                        if ($nbDaysBetween >1) {
                            createHabit();
                        } else {
                            echo "Already added an habit";
                        }
                    }?>
                </div>
            </div>
            <?php
                $con = openDB();
                $query = "SELECT description, id, color FROM habit WHERE userID = $_SESSION[id] ORDER BY color";
                $result = mysqli_query($con, $query);
                $nbRows = mysqli_num_rows($result);
                $IDArray = [];
                echo "<form id=sendDone method=post>";
                echo "<div id=allHabits>";
                while ($row = mysqli_fetch_assoc($result)) {
                    array_push($IDArray, $row['id']);
                    habitExpire($row['id']);
                    if (isset($_POST['changeHabit'])) {
                        $isDone = (isset($_POST["isDone_".$row['id']]) ? '1' : '0');
                        completeTask($isDone,$row['id']);
                    } else if (isset($_POST["removeHabit".$row['id']])) {
                        deleteTask($row['id']);
                        continue;
                    }
                    $done = checkIfDone($row['id']);
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
            ?>
        </div>
        <div id="toDo" name="toDo" class="toDo" class="aColumn">To Do
        <?php
                $con = openDB();
                $query = "SELECT description FROM habit WHERE isDone = False and userID = $_SESSION[id]";
                $result = mysqli_query($con, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div>".$row['description']."</div>";
                }
            ?>
        </div>
        <div id="group" name="group" class="group" class="aColumn">Group
            <div id=totalScore> Total score = <?= getInDB("score","user","id" ,$_SESSION["id"])["score"];?> </div>
            <?php 
                $con = openDB();
                $groupID = getInDB("groupID","user","id",$_SESSION["id"])["groupID"];
                if ($groupID != null) {
                    echo "<form method=POST id=invite><input type=submit name=invite value=invite people></form>";
                    $stmt = $con->prepare("SELECT username FROM user WHERE groupID = ?");
                    $stmt->bind_param("s",$groupID);
                    $stmt->execute();
                    $result2 = $stmt->get_result();
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div>".$row['username']."</div>";
                    }
                } else {
                        echo "<form method=POST id=viewInvite><input type=submit name=viewInvite value=invitations></form>";
                        echo "<form method=POST id=createGroup><input type=submit name=createGroup value=Create&nbsp;group></form>";
                }
            ?>
        </div>
    </div>
<script>
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
