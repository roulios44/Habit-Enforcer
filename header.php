<?php 
require_once "request.php" ;
class Header extends Request{

    public function generateHeader(){
        if (session_status() === PHP_SESSION_ACTIVE){
            echo "<div id=manageAccount>";
            echo "<form method=POST id=deconnexionForm><input type=submit name=deconnect value=Deconnexion></form>";
            echo "<form method=POST id=deleteAccount><input type=submit name=deleteAccount value=Delete&nbsp;account></form>";
            echo "</div>";
        }
        if (isset($_POST["deconnect"]) || isset($_POST["deleteAccount"])) {
            if (isset($_POST["deleteAccount"])) {
                $this->deleteAccount($_SESSION['id'],$_SESSION["groupID"]);
                $this->deleteTask("userID",$_SESSION['id']);
            }
            session_unset();
            session_destroy();
            header('Location: signIn.php');
        }
    }
}
$header = new Header ;
?>
<div class="header">
    <div class="leftHeader">
        <p>Bonjour   <?php echo $_SESSION["username"] ;?>   </p>

        <div>
        <form action="main.php">
            <input type="submit" value="HOME">
        </form>
        </div>
    </div>
    <div class="rightHeader">        
        <?php $header->generateHeader() ;?>
    </div>
    <?php 
        
    ?>
</div>

<?php
function disconnect(){
    if ($_SESSION["id"]){
        if (session_status() === PHP_SESSION_ACTIVE){
            echo "<form method=POST id=deconnexionForm><input type=submit name=deconnect value=deconnexion></form>";
        }
        if (isset($_POST["deconnect"])) {
            session_unset();
            session_destroy();
            header('Location: signIn.php');
        }
    }
}
?>
