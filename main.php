<!DOCTYPE html>
<html lang="en">
    <?php include "head.php" ?>
<body>
    <?php include "header.php"?>
    <div id="allColumns" name="allColumns" class="allColumns"> 
        <div id="ranking" name="ranking" class="ranking" class="aColumn">Ranking
            <?php
                include 'request.php';
                $con = openDB();
                $query = "SELECT username FROM user";
                $result = mysqli_query($con, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div>".$row['username']."</div>";
                }
            ?>
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
                    <!-- TODO si le mec veut add une habit pas complete, lui indiquer !-->
                    <?php createHabit() ?>
                </div>
            </div>
            <?php
                $con = openDB();
                $query = "SELECT description, id FROM habit";
                $result = mysqli_query($con, $query);
                $nbRows = mysqli_num_rows($result);
                $IDArray = [];
                echo "<form id=sendDone method=post>";
                echo "<div id=allHabits>";
                while ($row = mysqli_fetch_assoc($result)) {
                    array_push($IDArray, $row['id']);
                    $done = checkIfDone($row['id']);
                    $sayDone = "Done";
                    $check = "";
                    if ($done) {
                        $check = "checked";
                        $sayDone = "Undone";
                    }
                    echo "<div> <input type=hidden name=hidDone_".$row['id']." value=".$sayDone." ><input type=checkbox onclick=this.form.submit() name=isDone id=isDone value=".$row['id']." ".$check.">".$row['description']." </div>";
                }
                echo "</div>";
                echo "</form>";
            ?>
            <?php
                foreach ($IDArray as $id) {
                    echo $_POST["hidDone_$id"];
                    if(isset($_POST["hidDone_$id"])){
                        if ($_POST["hidDone_$id"]== "Done") {
                            completeTask(1,$id);
                        }
                        if ($_POST["hidDone_$id"]== "Undone") {
                            completeTask(0, $id);
                        }
                    }
                }
            ?>
        </div>
        <div id="toDo" name="toDo" class="toDo" class="aColumn">To Do
        <?php
                $con = openDB();
                $query = "SELECT description FROM habit WHERE isDone = False";
                $result = mysqli_query($con, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div>".$row['description']."</div>";
                }
            ?>
        </div>
        <div id="group" name="group" class="group" class="aColumn">Group
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