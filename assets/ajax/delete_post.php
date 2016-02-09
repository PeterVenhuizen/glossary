<?php
    require_once('../config.php');
    require('../../functions.php');

	if(!isset($_SESSION['user'])) { 
		echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=login.php">';
		die();
	} else {

		if (isset($_POST['id'])) {

            # Remove post
		    try {
		        $stmt = $db->prepare("DELETE FROM glossary WHERE id = :id");
                $stmt->bindValue('id', $_POST['id']);
		        $stmt->execute();
		    } catch (PDOException $ex) { }
			
            remove_lonely_tags($db);
		}
	}
?>