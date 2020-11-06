<?php ob_start(); ?>

    <div id="bloc_signup">

        <h1 id="title_signup">Page d'inscription</h1>

            <form action="inscription.php" method="POST" id="form_signup">

                <label>Pseudo : <input type="text" name="set_pseudo" class="input_signup" id="set_pseudo" value="<?php if(isset($_POST['set_pseudo'])){echo $_POST['set_pseudo'];} ?>" required></label>

                <label>E-mail : <input type="email" name="set_email" class="input_signup" id="set_email" value="<?php if(isset($_POST['set_email'])){echo $_POST['set_email'];} ?>" required></label>

                <label>Mot de passe : <input type="password" name="set_mdp" class="input_signup" id="set_mdp" required></label>

                <label>Confirmez mot de passe : <input type="password" name="set_mdp_confirm" class="input_signup" id="set_mdp_confirm" required></label>

                <div id="bloc_force_mdp">
                    <span id="force_mdp" class="forceMdp"></span>
                    <span id="force_mdp1" class="forceMdp"></span>
                    <span id="force_mdp2" class="forceMdp"></span>
                    <span id="force_mdp3" class="forceMdp"></span>
                </div>

                <span><input type="submit" value="Inscription" id="submit_signup"></span>

            </form>
    </div>

<?php $content = ob_get_clean(); ?>