<?php 
    if (isset($_GET['cle']) && isset($_GET['id'])) {
        $bdd = new PDO('mysql:host=localhost;dbname=blog', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $req = $bdd->prepare('SELECT cle, id FROM membre WHERE cle=? AND id = ?');
        $req->execute(array($_GET['cle'], $_GET['id']));
        $donnees = $req->fetch();
        $cle = $donnees['cle'];
        $id = $donnees['id'];

        if (!isset($_POST['mdp']) && !isset($_POST['mdp_confirm']) && $id == $_GET['id'] && $cle == $_GET['cle'] || $_POST['mdp'] != $_POST['mdp_confirm']) {
            ?><!DOCTYPE html>
            <html>
            <head>
                <title>inscription</title>
                <meta charset="UTF-8" />
            </head>
            <body>
                <h1>Choisissez un nouveau mot de passe</h1>
                <form action="recuperation_mdp.php?cle=<?php echo $_GET['cle'] . '&id=' . $_GET['id'] ?>" method="POST">
                    <p><label>Mot de passe : <input type="password" name="mdp" id="mdp" required></label></p>
                    <p><label>Confirmez mot de passe : <input type="password" name="mdp_confirm" id="mdp_confirm" required></label></p>
                    <p><input type="submit" value="Modifier mot de passe"></p>
                    <?php if (@$_POST['mdp'] != @$_POST['mdp_confirm']){echo 'confirmation du mot de passe different';} ?>
                </form>
                </body>
            </html><?php
        }else {
            if ($_POST['mdp'] == $_POST['mdp_confirm']) {
                if ($_GET['cle'] == $cle && $_GET['id'] == $id) {
                    $mdp_hash = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
                    $req->closeCursor();
                    $req = $bdd->prepare('UPDATE membre SET mdp = :mdp WHERE cle = :cle AND id = :id');
                    $req->execute(array(
                        'mdp' => $mdp_hash,
                        'cle' => $cle,
                        'id' => $id
                    ));
                    $req->closeCursor();
                    $req = $bdd->exec('UPDATE membre SET cle=null WHERE id=' . $id);
                    echo "votre mot de passe a bien ete reintialisé cliquez <a href='http://localhost/blog/blog.php'>ici</a> pour revenir a la page d'accueil";
                } else {
                    echo "les identifiant de ne corespondent pas merci de ne pas modifier l'url !";
                }  
            }
        }

    } else {
        echo "l'url n'est pas complète merci de verifier le lien dans votre boite mail si le probleme persiste merci de contacter le support a l'adresse suivante : contact@monsite.fr";
    }
?>