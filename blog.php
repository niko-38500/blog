<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>blog php</title>
</head>
<body>
    <?php 
    if(isset($_GET['account_set']))    {echo 'bravo votre compte a ete cree veuillez verifier vos mail pour valider votre compte';}
    if (isset($_GET['acount_validated'])) {echo 'bravo votre compte est validé vous pouvez vous connecter :D';};

    if (isset($_SESSION['id'])) {
        include 'component/user_connected.php';
    }  else {
        include 'component/inscription_connexion.php';
    } 
    ?>
</body>
</html>