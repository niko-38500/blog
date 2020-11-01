<?php
    $bdd = new PDO('mysql:host=localhost;dbname=blog', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $req = $bdd->prepare('SELECT count(*) FROM membre WHERE email=?');
    $req->execute(array($_POST['emailAjax']));
    $email = $req->fetchColumn();

    if ($email == 0) {
        echo 1;
    }
?>