<div class="header">
    <div class="leftHeader">
        <p>left Header </p>
    </div>
    <div class="rightHeader">
        <p>right Header </p>
    </div>
    <?php 
        if (session_status() === PHP_SESSION_ACTIVE){
            echo "<div id=manageAccount>";
            echo "<form method=POST id=deconnexionForm><input type=submit name=deconnect value=Deconnexion></form>";
            echo "<form method=POST id=deleteAccount><input type=submit name=deleteAccount value=Delete&nbsp;account></form>";
            echo "</div>";
        }
        if (isset($_POST["deconnect"]) || isset($_POST["deleteAccount"])) {
            include "request.php";
            if (isset($_POST["deleteAccount"])) {
                deleteAccount($_SESSION['id']);
                deleteTask("userID",$_SESSION['id']);
            }
            refreshLastConnection($_SESSION['id']);
            session_unset();
            session_destroy();
            header('Location: signIn.php');
        }
    ?>
</div>
