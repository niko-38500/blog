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
