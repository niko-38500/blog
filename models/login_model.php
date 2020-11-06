<?php


try {
    $bdd = new PDO('mysql:host=localhost;dbname=blog', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (Exception $e) {
    die('erreur : ' . $e->getMessage());
}

if (!isset($_COOKIE['mdp']) && !isset($_COOKIE["email"])) {

    if (isset($_POST["emailAjax"]) && isset($_POST["mdpAjax"]) && !empty($_POST["emailAjax"]) && !empty($_POST["mdpAjax"])) {
        $req = $bdd->prepare('SELECT email, id, pseudo, mdp, email_valide FROM membre WHERE email=?');
        $req->execute(array($_POST['emailAjax']));
        $data = $req->fetch();

        @$verify_mdp = password_verify($_POST['mdpAjax'], $data['mdp']);

        if (!$data) {
            echo 'error';
        } else {

            if ($verify_mdp){

                session_start();
                $_SESSION['pseudo'] = $data['pseudo'];
                $_SESSION['id'] = $data['id'];
                $_SESSION['email_valide'] = $data['email_valide'];

                if(@$_POST['connexion_auto']) {

                    setcookie('email', $data['email'], time() + 365*24*3600, "/", null, false, true);
                    setcookie('mdp', $data['mdp'], time() + 365*24*3600, "/", null, false, true);
                }

            } else {

                echo 'error';
            }
        }

        $req->closeCursor();
    }

} else if (!isset($_SESSION['id']) && isset($_COOKIE['mdp']) && isset($_COOKIE["email"]) && !empty($_COOKIE['mdp']) && !empty($_COOKIE["email"])) {

    $req = $bdd->prepare('SELECT email, id, pseudo, mdp, email_valide FROM membre WHERE email=?');
    $req->execute(array($_COOKIE['email']));
    $data = $req->fetch();

    if ($data['mdp'] == $_COOKIE['mdp']) {
        
        // session_start();
        $_SESSION['pseudo'] = $data['pseudo'];
        $_SESSION['id'] = $data['id'];
        $_SESSION['email_valide'] = $data['email_valide'];
        
    } else {
        echo 'Erreur';
    }

    $req->closeCursor();
}