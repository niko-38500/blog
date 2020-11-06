$(document).ready(function() {
    let nav = document.querySelector('nav');
    let stick = nav.offsetHeight;
    let signInBtn = document.querySelector('#sign_in');

// fix the navbar on the top on scrolling

    window.addEventListener("scroll", function() {
        scroll();
    });

    function scroll () {
        if (window.pageYOffset >= stick) {
            nav.classList.add("sticky");
        } else {
            nav.classList.remove("sticky");
        }
    }

// hide and show login box

    signInBtn.addEventListener('click', function() {
        $('#signIn').slideToggle();
    });

// regex for password strength

    let force0 = document.querySelector("#force_mdp");
    let force1 = document.querySelector("#force_mdp1");
    let force2 = document.querySelector("#force_mdp2");
    let force3 = document.querySelector("#force_mdp3");
    const mdpOk = new RegExp('[a-zA-Z0-9-+!*$@%_]{7}');
    const mdpMoyen = new RegExp(/(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\W)/g);
    const mdpFort = new RegExp(/(?=.{8,})(?=.*[A-Z].*[A-Z])(?=.*[a-z])(?=.*[0-9].*[0-9].*[0-9])(?=.*\W.*\W)/g);
    const mdp = document.querySelector('#set_mdp');
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

    // verify in real time if pseudo and email is already used in database

    function verifPseudo(){
        $.ajax({
            url: '../models/verifPseudoAjax.php',
            type: 'post',
            data: {'pseudoAjax': inputPseudo},
            success: function(data){
                if (data == 0){
                    $("#set_pseudo").after("<p style='color: red' class='is_pseudo_exist'>Nom d'utilisateur déja pris.</p>");
                } else {
                    $('#set_pseudo').after("<p style='color: green' class='is_pseudo_exist'>Nom d'utilisateur libre.</p>");
                }
            }, error: function(data) {
                console.log('erreur : ' + JSON.parse(data));
            } 
        });

    };

    function verifEmail () {
        $.ajax({
            url: '../models/verifEmailAjax.php',
            type: 'post',
            data: { 'emailAjax': inputEmail },
            success: function(data) {
                if (data == 0) {
                    $("#set_email").after("<p style='color: red' class='is_email_exist'>Email déja pris.</p>");
                } else {
                    $('#set_email').after("<p style='color: green' class='is_email_exist'>Email libre.</p>");
                }
            }, error: function (data) {
                console.log("erreur : " + data);
            }
        });
    }

    $('#set_pseudo').keyup(function(e) {
        inputPseudo = e.target.value;
        $('.is_pseudo_exist').remove();
        verifPseudo();
    });

    $('#set_email').keyup(function(e) {
        inputEmail = e.target.value;
        $(".is_email_exist").remove();
        verifEmail();
    });

    $("#set_mdp_confirm").change(function() {
        $("#mdpJs").remove();
        let mdp = $("#set_mdp").val();
        let mdpConfirm = $("#set_mdp_confirm").val();
        if (mdp != mdpConfirm) {
            $("#set_mdp_confirm").after('<p id="mdpJs" style="color: red">Confirmation du mot de passe different</p>');
        }
    });
});