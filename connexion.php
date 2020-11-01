<?php
    $bdd = new PDO('mysql:host=localhost;dbname=blog', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $req = $bdd->prepare('SELECT id, pseudo, mdp, email_valide FROM membre WHERE email=?');
    $req->execute(array($_POST['email']));
    @$donnees = $req->fetch();

    @$verify_mdp = password_verify($_POST['mdp'], $donnees['mdp']);

    if (!$donnees) {
        echo 'Email ou mot de passe incorect';
    } else {
        if ($verify_mdp){
            session_start();
            $_SESSION['pseudo'] = $donnees['pseudo'];
            $_SESSION['id'] = $donnees['id'];
            $_SESSION['email_valide'] = $donnees['email_valide'];
            if($_POST['connexion_auto']) {
                setcookie('email', $_POST['email'], time() + 365*24*3600, null, null, false, true);
                setcookie('mdp', $donnees['mdp6'], time() + 365*24*3600, null, null, false, true);
            }
            header('location: blog.php');
        } else {
            echo 'email ou mot de passe incorect';
        }
    }
?>