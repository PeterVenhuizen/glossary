<?php 
	require_once('assets/config.php'); 

    unset($_SESSION['user']); 

    header("Location: " . $config['absolute_path']); 
    die();
