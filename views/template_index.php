<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title><?= $title ?></title>
        <link rel="stylesheet" href="../public/css/index.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap" rel="stylesheet">
    </head>
    <body>
        <div id="header_container">
            <header>
                <img alt="logo" id='logo' src="../public/images/logo3.png" />
                <?php if (!isset($_SESSION['id'])) { ?>
                    <div id="sign">
                        <a id="sign_in">connexion</a>
                        <a href="#" id="sign_up">inscription</a>
                    </div>
                    <div id="signIn">
                        <h2>Se connecter</h2>
                        <span id="wrong_pass" style="display: none;">Email ou mot de passe incorrect</span>

                        <form method="POST" onsubmit="return sendData();">
                            <label>e-mail : <br><input type="email" name="email" class="input" id="email" placeholder="email" /></label>
                            <label>mot de passe : <br><input type="password" name="mdp" id="mdp" class="input" placeholder="Mot de passe"></label>
                            <label>connexion automatique ? <input type="checkbox" name="connexion_auto" id="connexion_auto"></label>
                            <p><a href="http://localhost/blog/recup_mdp.php">Mot de passe oublié ?</a></p>
                            <button id="submit" type="submit">Connexion</button>
                        </form>

                    </div>
                <?php } else { ?>
                    <div id="user_account">
                        <h3>Mon compte</h3>
                        <div id="account_management">
                            <a href="#">Profil</a>
                            <a href="#">Vos poste</a>
                            <hr width="100%">
                            <a href="#"><?= $notification ?></a>
                            <a href="#"><?= $privateMessage ?></a>
                            <hr width="100%">
                            <a href="../models/deconnexion.php">Déconnexion</a>
                        </div>
                    </div>
                <?php } ?>
            </header>
            <nav>
                <ul>
                    <li><a class="nav_list" href="#">HOME</a></li>
                    <li><a class="nav_list" href="#">PRESENTATION</a></li>
                    <li><a class="nav_list" href="#">ARTICLES</a></li>
                    <li><a class="nav_list" href="#">CONTACT</a></li>
                </ul>
            </nav>
        </div>
        <div id="banner_after_header">
            <h1>Bienvenue !</h1>
            <p>Ce blog à pour vocation de vous faire découvrir mes voyages mais aussi vous donner des astuce pour voyager a moindre coup, ce site dispose également d'un espace communautaire pour echanger entre vous de manière totalement random comme par example sur vos astuce voyage ou même vos recette de crêpe preferé c'est vous qui voyais :p</p>
        </div>
        <article>
            <h1 style="color: white;">Dérniere article</h1>
           
        </article>
        <script src="../public/js/login_ajax.js"></script>
        <script src="../public/js/index.js"></script>
    </body>
</html>