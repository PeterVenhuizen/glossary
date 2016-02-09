<!DOCTYPE html>

<?php 
	require_once('assets/config.php'); 
	ini_set('display_errors', 1);error_reporting(E_ALL); 	
?>

<html>
	<head>
		<meta charset="UTF-8">
		<meta content="Glossary" name="Keywords">
		<meta content="Glossary" name="Description">
		<title>Glossary</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="assets/css/reset.css">
		<link rel="stylesheet" type="text/css" href="assets/css/style.css">
        <link href='https://fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'>
		<meta name="viewport" content="initial-scale=1">
        <script src="assets/js/functions.js"></script>
        <style>
        	.small_square { width: 15px; height: 15px; display: inline-block; margin: 1px; cursor: pointer; }
        </style>
	</head>
	
	<body>      
		
		<?php include('header.php'); ?>
        
		<main>
            
            <?php
                
				$sorted_colors = cf_sort_hex_colors($colors);

				foreach ($sorted_colors as &$c) {
					echo '	<div style="background-color: ' . $c . ';" class="small_square"></div>';
				}

            ?>
            
            <?php #include('footer.php'); ?>
            
		</main>
        
	</body>
</html>
