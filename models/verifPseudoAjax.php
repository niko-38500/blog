<?php 
    $bdd = new PDO('mysql:host=localhost;dbname=blog', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $req = $bdd->prepare('SELECT count(*) FROM membre WHERE pseudo=?');
    $req->execute(array($_POST['pseudoAjax']));
    $pseudo = $req->fetchColumn();

    if ($pseudo == 0) {
        echo 1;
    }
?>