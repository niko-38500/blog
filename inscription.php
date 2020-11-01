<?php
include 'component/inscription_connexion.php';

if (isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['email']) && !empty($_POST['pseudo']) && !empty($_POST['mdp']) && !empty($_POST['email'])) {
    $apiKey = '91MWHW2HQ9E2OLRFSU08';
    $params['format'] = 'json';
    $params['email']  = $_POST['email'];
    $query = '';

    foreach($params as $key=>$value){
        $query .= '&' . $key . '=' . rawurlencode($value);
    }

    $try = 0;
    do {
        ////////////
        //For https request, please make sure you have enabled php_openssl.dll extension.
        //
        //How to enable https
        //- Uncomment ;extension=php_openssl.dll by removing the semicolon in your php.ini, and restart the apache.
        //
        //In case you have difficulty to modify the php.ini, you can always make the http request instead of https.
        ////////////
        $result = file_get_contents('https://api.mailboxvalidator.com/v1/validation/single?key=' . $apiKey . $query);
    } while(!$result && $try++ < 3);
    $data = json_decode($result);

    $bdd = new PDO('mysql:host=localhost;dbname=blog', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    if ($_POST['mdp'] == $_POST['mdp_confirm']) {
        $hashed_mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
        $req = $bdd->prepare('SELECT count(*) FROM membre WHERE pseudo=?');
        $req->execute(array($_POST['pseudo']));
        $donnees = $req->fetchColumn();
        
        if ($donnees != 0) {
            echo '<p>pseudo non-disponible.</p>';
        } else {
            $req->closeCursor();
            $req = $bdd->prepare('SELECT count(*) FROM membre WHERE email=?');
            $req->execute(array($_POST['email']));
            $donnees = $req->fetchColumn();

            if ($donnees != 0) {
                echo '<p>email déja enregister <em>si vous avez oublié votre mot de passe cliquez : <a href="http://localhost/blog/recuperation_mdp.php">ici</a></em></p>';
            } else {
                if ($data->is_verified == "True") {
                    $cle = md5(microtime(TRUE)*100000); // cree une cle unique pour validé le compte
                    $req->closeCursor();
                    $req = $bdd->prepare('INSERT INTO membre(pseudo, mdp, email, date_inscription, cle) VALUES(:pseudo, :mdp, :email, NOW(), :cle)');
                    $req->execute(array(
                        'pseudo' => $_POST['pseudo'],
                        'mdp' => $hashed_mdp,
                        'email' => $_POST['email'],
                        'cle' => $cle
                    ));

                    $req->closeCursor();

                    $req = $bdd->prepare('SELECT id FROM membre WHERE email=?');
                    $req->execute(array($_POST['email']));
                    $donnees = $req->fetch();
                    $id = $donnees['id'];

                    $to = $_POST['email'];
                    $subject = 'une dérnière étape pour la creation de votre compte !';
                    $message = "vous venez de cree a compte sur notre site bienvenue ! merci de cliquer sur ce lien pour activer votre compte => http://localhost/blog/validation_compte.php?id=" . $id . "&cle=" . $cle;
                    $message = wordwrap($message, 100);

                    mail($to, $subject, $message);

                    header('location: blog.php?account_set=true');
                } else {
                    echo "Une erreur est surevenue, votre adresse email semble ne pas exister merci de renseigner une adresse email valide.";
                }
                
            }
        }
    } else {
        echo '<p>Mot de passe et confirmation differents</p>';
    }

}
?>
<!DOCTYPE html>
<html>
<head>
    <title>inscription</title>
    <meta charset="UTF-8" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="style_inscription.css">
</head>
<body>
    <h1>Page d'inscription</h1>
    <form action="inscription.php" method="POST">
        <p><label>Pseudo : <input type="text" name="pseudo" id="pseudo" value="<?php if(isset($_POST['pseudo'])){echo $_POST['pseudo'];} ?>" required></label></p>
        <p><label>E-mail : <input type="email" name="email" id="email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];} ?>" required></label></p>
        <p><label>Mot de passe : <input type="password" name="mdp" id="mdp" required></label></p>
        <p><label>Confirmez mot de passe : <input type="password" name="mdp_confirm" id="mdp_confirm" required></label></p>
        <div id="bloc_force_mdp"><span id="force_mdp" class="forceMdp"></span>
        <span id="force_mdp1" class="forceMdp"></span>
        <span id="force_mdp2" class="forceMdp"></span>
        <span id="force_mdp3" class="forceMdp"></span></div>
        <p><input type="submit" value="Inscription"></p>
    </form>
    <script src="reqAjax/script.js"></script>
    <script>
    $(document).ready(function() {
        let force0 = document.querySelector("#force_mdp");
        let force1 = document.querySelector("#force_mdp1");
        let force2 = document.querySelector("#force_mdp2");
        let force3 = document.querySelector("#force_mdp3");
        const mdpOk = new RegExp('[a-zA-Z0-9-+!*$@%_]{7}');
        const mdpMoyen = new RegExp(/(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\W)/g);
        const mdpFort = new RegExp(/(?=.{8,})(?=.*[A-Z].*[A-Z])(?=.*[a-z])(?=.*[0-9].*[0-9].*[0-9])(?=.*\W.*\W)/g);
        const mdp = document.querySelector('#mdp');
        const force = document.querySelector('#bloc_force_mdp');

        function removeClasses () {
            const cls = ["mdpInvalide", "mdpOkk", "mdpMoyen", "mdpFort"];
            force0.classList.remove(...cls);
            force1.classList.remove(...cls);
            force2.classList.remove(...cls);
            force3.classList.remove(...cls);
        }

        function addClass () {
            force0.className = "forceMdp";
            force1.className = "forceMdp";
            force2.className = "forceMdp";
            force3.className = "forceMdp";
        }

        mdp.addEventListener("keyup", function(e) {
            let target = e.target.value;
            let txt = document.createElement('div');
            txt.setAttribute("id", "txt_force");

            if (mdpOk.test(target) == false) {
                $("#txt_force").remove();
                txt.append("votre mot de passe doit contenir au moins 6 caratère");
                addClass();
                removeClasses();
                force0.className = "mdpInvalide";
                force.after(txt);
            } else if (mdpFort.test(target)) {
                $("#txt_force").remove();
                txt.append("mot de passe fort !");
                addClass();
                removeClasses();
                force0.className = "mdpFort";
                force1.className = "mdpFort";
                force2.className = "mdpFort";
                force3.className = "mdpFort";
                force.after(txt);
            } else if (mdpMoyen.test(target)) {
                $("#txt_force").remove();
                txt.append("mot de passe correct");
                addClass();
                removeClasses();
                force0.className = "mdpMoyen";
                force1.className = "mdpMoyen";
                force2.className = "mdpMoyen";
                force.after(txt);
            } else if (mdpOk.test(target)) {
                $("#txt_force").remove();
                txt.append("mot de passe faible");
                addClass();
                removeClasses();
                force0.className = "mdpOkk";
                force1.className = "mdpOkk";
                force.after(txt);
            } 
            
            
        });
    });
    </script>
</body>
</html>