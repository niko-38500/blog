<?php
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $bdd = new PDO('mysql:host=localhost;dbname=blog', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $req = $bdd->prepare('SELECT * FROM membre WHERE email=?');
        $req->execute(array($_POST['email']));
        $donnees = $req->fetch();

        if (isset($donnees['id'])) {
            if (empty($donnees['cle'])) {
                $cle = md5(microtime(TRUE)*100000);
                $req->closeCursor();
                $req = $bdd->prepare("UPDATE membre SET cle='$cle' WHERE  id = ?");
                $req->execute(array($donnees['id']));
                $id = $donnees['id'];

                $to = $_POST['email'];
                $subject = "recupèrer votre mot de passe";
                $message = "pour changer votre mot de passe cliquez sur ce lien : http://localhost/blog/recuperation_mdp.php?id=" . $id . "&cle=" . $cle;
                $sent = mail($to, $subject, $message);
                if ($sent){
                    echo 'le mail de recupeartion a été envoyé avec success merci de verifier votre boite de reception ou vos spam et de cliquer sur le lien pour changer votre mot de passe';
                } else {
                    echo "une erreur c'est produite lors de l'envoi du mail, merci de reesayer si le probleme persiste merci de contacter le support a l'adresse suivante : contact@monsite.fr";
                }
            } else {
                echo 'une erreur est survenu merci de verifier votre boite mail ainsi que les spam ou reesayez plus tard';
            }
        } else {
            echo "Désoler votre email n'existe pas dans la base de données merci de verifier votre saisie.";
        }
    }

    if (!isset($_POST['email'])) {
    ?>
    <!DOCTYPE html />
    <html>
        <head>
            <meta charset="UTF-8" />
            <title>recuperation du mot de passe</title>
        </head>
        <body>
        <h1>Page de recuperation du mot de passe :</h1>
        <form action="recup_mdp.php" method="POST">
            <p><label>E-mail : <input type="email" name="email" id="email" required></label></p>
            <p><input type="submit" value="Récuperer mot de passe"></p>
        </form>
        </body>
    </html>

    <?php
    }
    ?>