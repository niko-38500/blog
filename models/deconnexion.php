<?php 
    session_start();

    $_SESSION = array();
    session_destroy();

    setcookie('email', "", -1, "/");
    setcookie('mdp', "", -1, "/");

    header('location: ' . $_SERVER['HTTP_REFERER']);