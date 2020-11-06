<?php

if (!isset($_SESSION['id'])) {

    if (isset($_POST['set_pseudo'], $_POST['set_mdp'], $_POST['set_email']) && !empty($_POST['set_pseudo']) && !empty($_POST['set_mdp']) && !empty($_POST['set_email'])) {
        
        // API request to verify if user email exist
        
        $apiKey = '91MWHW2HQ9E2OLRFSU08';
        $params['format'] = 'json';
        $params['email']  = $_POST['set_email'];
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

        // verify if email and pseudo already exist

        $bdd = new PDO('mysql:host=localhost;dbname=blog', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        if ($_POST['set_mdp'] == $_POST['set_mdp_confirm']) {
            $hashed_mdp = password_hash($_POST['set_mdp'], PASSWORD_DEFAULT);
            $req = $bdd->prepare('SELECT count(*) FROM membre WHERE pseudo=?');
            $req->execute(array($_POST['set_pseudo']));
            $donnees = $req->fetchColumn();
            
            if ($donnees != 0) {
                echo '<p>pseudo non-disponible.</p>';
            } else {
                $req->closeCursor();
                $req = $bdd->prepare('SELECT count(*) FROM membre WHERE email=?');
                $req->execute(array($_POST['set_email']));
                $donnees = $req->fetchColumn();

                if ($donnees != 0) {
                    echo '<p>email déja enregister <em>si vous avez oublié votre mot de passe cliquez : <a href="http://localhost/blog/recuperation_mdp.php">ici</a></em></p>';
                } else {
                    if ($data->is_verified == "True") {

                        // add user to the database

                        $cle = md5(microtime(TRUE)*100000); // creat a unique key to validat account
                        $req->closeCursor();
                        $req = $bdd->prepare('INSERT INTO membre(pseudo, mdp, email, date_inscription, cle) VALUES(:pseudo, :mdp, :email, NOW(), :cle)');
                        $req->execute(array(
                            'pseudo' => $_POST['set_pseudo'],
                            'mdp' => $hashed_mdp,
                            'email' => $_POST['set_email'],
                            'cle' => $cle
                        ));

                        $req->closeCursor();

                        $req = $bdd->prepare('SELECT id FROM membre WHERE email=?');
                        $req->execute(array($_POST['set_email']));
                        $donnees = $req->fetch();
                        $id = $donnees['id'];

                        // send email to verify user email

                        $to = $_POST['set_email'];
                        $subject = 'une dérnière étape pour la creation de votre compte !';
                        $message = "vous venez de cree a compte sur notre site bienvenue ! merci de cliquer sur ce lien pour activer votre compte => http://localhost/blog/pages/validation_compte.php?id=" . $id . "&cle=" . $cle;
                        $message = wordwrap($message, 100);

                        mail($to, $subject, $message);

                        header('location: index.php?account_set=true');
                    } else {
                        echo "Une erreur est surevenue, votre adresse email semble ne pas exister merci de renseigner une adresse email valide.";
                    }
                    
                }
            }
        } else {
            echo '<p>Mot de passe et confirmation differents</p>';
        }

    }
}