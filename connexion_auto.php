<?php
$bdd = new PDO('mysql:host=localhost;dbname=blog', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$req = $bdd->prepare('SELECT id, pseudo, mdp FROM membre WHERE email=?');
$req->execute(array($_COOKIE['email']));
$donnees = $req->fetch();

if (!$donnees) {
    echo 'email ou mot de passe incorect, veuillez reesayer';
} else {
    if ($donnees['mdp'] == $_COOKIE['mdp']) {
        session_start();
        $_SESSION['pseudo'] = $donnees['pseudo'];
        $_SESSION['id'] = $donnees['id'];
        header('location: blog.php');
    } else {
        echo 'Erreur';
    }
}
?>