<div class="header">
    <div id="leftHeader">
        <p>left Header </p>
    </div>
    <div id="rightHeader">
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
            if (isset($_POST["deleteAccount"])) {
                require_once "request.php";
                deleteAccount($_SESSION['id']);
            }
            session_unset();
            session_destroy();
            header('Location: signIn.php');
        }
    ?>
</div>