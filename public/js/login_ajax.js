// ajax request for the user sign in

function sendData () {
    var email = $("#email").val();
    var mdp = $("#mdp").val();
    var connexion_auto = $("#connexion_auto").val();

    $.ajax({
        type: "post",
        url: "../models/login_model.php",
        data: { 
            'emailAjax': email,
            'mdpAjax': mdp,
            'connexion_auto': connexion_auto
        },
        success: function(data) {
            if (data != "error") {
                window.location.reload();
            } else {
                $("#wrong_pass").show();
            }
        }, error : function (data) {
            console.log(data);
        }
    });

    return false
}
