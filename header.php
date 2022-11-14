<div class="header">
    <div class="leftHeader">
        <p>left Header </p>
        <div>
            <?php disconnect() ?>
        </div>
    </div>
    <div class="rightHeader">
        <p>right Header </p>
    </div>
</div>

<?php
function disconnect(){
    if (session_status() === PHP_SESSION_ACTIVE){
        echo "<form method=POST id=deconnexionForm><input type=submit name=deconnect value=deconnexion></form>";
    }
    if (isset($_POST["deconnect"])) {
        session_unset();
        session_destroy();
        header('Location: signIn.php');
    }
}
?>