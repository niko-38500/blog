<?php 
    session_start();

    $_SESSION = array();
    session_destroy();

    header('location: blog.php');

    setcookie('email', '');
    setcookie('mdp', '');
?>