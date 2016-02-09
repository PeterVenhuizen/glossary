<?php
    require_once('../config.php');
    require('../../functions.php');

	#if(!isset($_SESSION['user'])) { 
	#	header("Location: " . $config['absolute_path']);
	#	die();
	#} else {

		if (isset($_POST['id'])) {

			$id = mysql_real_escape_string($_POST['id']);

            # Remove post
		    try {
		        $stmt = $db->prepare("DELETE FROM glossary WHERE id = :id");
                $stmt->bindValue('id', $id);
		        $stmt->execute();
		    } catch (PDOException $ex) { $ex->getMessage(); }
			
            remove_lonely_tags($db);
		}
	#}
?>
