let inputPseudo;
let inputEmail;

$(document).ready(function(){
    function verifPseudo(){
        $.ajax({
            url: 'reqAjax/verifPseudoAjax.php',
            type: 'post',
            data: {'pseudoAjax': inputPseudo},
            success: function(data){
                if (data == 0){
                    $("#pseudo").after("<p style='color: red' class='isPseudoExist'>nom d'utilisateur déja pris.</p>");
                } else {
                    $('#pseudo').after("<p style='color: green' class='isPseudoExist'>nom d'utilisateur libre.</p>");
                }
            }, error: function(data) {
                console.log('erreur : ' + JSON.parse(data));
            } 
        });

    };

    function verifEmail () {
        $.ajax({
            url: 'reqAjax/verifEmailAjax.php',
            type: 'post',
            data: { 'emailAjax': inputEmail },
            success: function(data) {
                if (data == 0) {
                    $("#email").after("<p style='color: red' class='isEmailExist'>Email déja pris.</p>");
                } else {
                    $('#email').after("<p style='color: green' class='isEmailExist'>Email libre.</p>");
                }
            }, error: function (data) {
                console.log("erreur : " + data);
            }
        });
    }

    $('#pseudo').keyup(function(e) {
        inputPseudo = e.target.value;
        $('.isPseudoExist').remove();
        verifPseudo();
    });

    $('#email').keyup(function(e) {
        inputEmail = e.target.value;
        $(".isEmailExist").remove();
        verifEmail();
    });

    $("#mdp_confirm").change(function() {
        $("#mdpJs").remove();
        let mdp = $("#mdp").val();
        let mdpConfirm = $("#mdp_confirm").val();
        if (mdp != mdpConfirm) {
            $("#mdp_confirm").after('<p id="mdpJs" style="color: red">Confirmation du mot de passe different</p>');
        }
    });
});
