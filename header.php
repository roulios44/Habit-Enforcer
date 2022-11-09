<div class="header">
    <div id="leftHeader">
        <p>left Header </p>
    </div>
    <div id="rightHeader">
        <p>right Header </p>
    </div>
    <form method="POST" id="deconnexionForm">
        <input type="submit" name="deconnect" value="deconnexion">
    </form>
    <?php 
        if (isset($_POST["deconnect"])) {
            session_unset();
            session_destroy();
            header('Location: signIn.php');
        }
    ?>
</div>
