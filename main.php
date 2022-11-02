<!DOCTYPE html>
<html lang="en">
    <?php include "header.php" ?>
<body>
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
                    <form>
                    Description :<input type="text" id="description" name="description">
                    <div class="star-widget">Difficulty :
                    <input type="radio" name="rate" id="rate-5" value="5">
                    <label for="rate-5" class="fas fa-star"></label>
                    <input type="radio" name="rate" id="rate-4" value="4">
                    <label for="rate-4" class="fas fa-star"></label>
                    <input type="radio" name="rate" id="rate-3" value="3">
                    <label for="rate-3" class="fas fa-star"></label>
                    <input type="radio" name="rate" id="rate-2" value="2">
                    <label for="rate-2" class="fas fa-star"></label>
                    <input type="radio" name="rate" id="rate-1" value="1">
                    <label for="rate-1" class="fas fa-star"></label>
                    </div>
                    Color :<input type="color" value="#333333" list="colors">
                    Period of time : <select name="time" id="time">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    </select>
                    <input type="submit" id="submit" value = "Add habit">
                    </form>
                </div>
            </div>
        </div>
        <div id="toDo" name="toDo" class="toDo" class="aColumn">To Do
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