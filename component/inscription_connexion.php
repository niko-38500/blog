<?php
    if (isset($_COOKIE['email']) && isset($_COOKIE['mdp']) && !isset($_SESSION['id'])) {
        $_POST['email'] = $_COOKIE['email'];
        $_POST['mdp'] = $_COOKIE['mdp'];
        header('location: connexion_auto.php');
    }
?>

<div>
    <button id="btn_connexion">connexion</button>
    <?php if ($_SERVER['REQUEST_URI'] != '/blog/inscription.php') {?>
        <button id="btn_inscription">inscription</button>
    <?php } ?>
</div>
<div id="connexion" style="display:none">
    <form action="connexion.php" method="POST">
        <p><label>e-mail : <input type="email" name="email" /></label></p>
        <p><label>mot de passe : <input type="password" name="mdp"></label></p>
        <p><label>connexion automatique ? <input type="checkbox" name="connexion_auto" id=""></label></p>
        <p><a href="http://localhost/blog/recup_mdp.php">Mot de passe oublié ?</a></p>
        <p><input type="submit" value="Connexion"></p>
    </form>
</div>

<script>
    // bouton connexion
    let connexion = document.querySelector("#btn_connexion")
    let popup_connexion = document.querySelector("#connexion");
    connexion.addEventListener("click", function() {
        if (popup_connexion.style.display == "none") {
            popup_connexion.style.display = "block";
        } else {
            popup_connexion.style.display = "none";
        }
    });
    // bouton inscription
    let inscription = document.querySelector("#btn_inscription");
    inscription.addEventListener("click", function() {
        window.location = "inscription.php";
    });
</script>