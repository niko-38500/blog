<?php
if ($_SESSION["email_valide"] == 1) {
    echo 'bonjour ' . $_SESSION['pseudo'];
} else {
    echo 'bonjour ' . $_SESSION['pseudo'] . " veuillez valider votre compte avant de profité de toutes les fonctionnalité du site ;)";
}
?> 

<button>Deconnexion</button>

<script>
    let btn_deco = document.querySelector('button');

    btn_deco.addEventListener("click", function() {
        window.location = "deconnexion.php";
    });
</script>