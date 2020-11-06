<?php

session_start();

require('../models/login_model.php');

$title = "index";
$notification = "Aucune notification";
$privateMessage = "Aucun message";

require('../views/template_index.php');