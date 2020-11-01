<?php
session_start();

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $bdd = new PDO('mysql:host=localhost;dbname=blog', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $req = $bdd->prepare('SELECT id, cle FROM membre WHERE cle=? AND id=?');
    $req->execute(array($_GET['cle'], $_GET['id']));
    $donnees = $req->fetch();

    if ($_GET['cle'] == $donnees['cle']) {
        $req->closeCursor();
        $req = $bdd->prepare('UPDATE membre SET email_valide = :validation_mail WHERE cle = :cle_validation');
        $req->execute(array(
            "cle_validation" => $_GET['cle'],
            "validation_mail" => 1
        ));

        $req->closeCursor();
        $req = $bdd->prepare('UPDATE membre SET cle=null WHERE id=?');
        $req->execute(array($donnees['id']));

        echo 'Bravo votre compte est maintenant validé ! cliquez <a href="http://localhost/blog/blog.php?acount_validated=true">ici</a> pour revenir au menu principal';
    }
}
?>