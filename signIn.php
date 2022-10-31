<!DOCTYPE html>
<html lang="en">
    <?php
    include "head.php"
    ?>
    <body>
        <?php include "header.php" ?>
        <div class="connect">
            <form action="">
                <p>Username: <input type="text" name="username"></p>
                <p>Password: <input type="password" name ="password"></p>
                <p><input type="submit" value="Connect"></p>
            </form>
        </div>
        <div class="connectMessage">
            <p>No Account? <a href="http://localhost/Habit-Enforcer/register.php">Sign in here</a></p><br>
        </div>
    </body>
</html>