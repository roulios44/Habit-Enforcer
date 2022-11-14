<div class="header">
    <div id="leftHeader">
        <p>left Header </p>
    </div>
    <div id="rightHeader">
        <p>right Header </p>
    </div>
    <?php 
        if (session_status() === PHP_SESSION_ACTIVE){
            echo "<form method=POST id=deconnexionForm><input type=submit name=deconnect value=deconnexion></form>";
        }
        if (isset($_POST["deconnect"])) {
            session_unset();
            session_destroy();
            header('Location: signIn.php');
        }
    ?>
</div>