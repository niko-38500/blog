let mainCover = document.querySelector("#header_container");
let secondeCover = document.querySelector('article');
let nav = document.querySelector('nav');
let stick = mainCover.offsetHeight;
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

// scrolling parallax effect

window.addEventListener("scroll", () => {
    mainCover.style.backgroundPositionY = window.scrollY / 2.5 + "px";
});

window.addEventListener("scroll", () => {
    if (window.pageYOffset >= 600) {
        secondeCover.style.backgroundPositionY = (-window.scrollY + 600) / 4 + "px";
    }
});

// hide and show login box

signInBtn.addEventListener('click', function() {
    $('#signIn').slideToggle();
});
