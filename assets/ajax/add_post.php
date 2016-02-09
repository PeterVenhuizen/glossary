<?php 
	require_once('../config.php');
    
    $topic = mysql_real_escape_string($_POST['topic']);
    $keywords = mysql_real_escape_string($_POST['keywords']);
    $body = ( get_magic_quotes_gpc() ? htmlspecialchars(stripslashes($_POST['body'])) : htmlspecialchars($_POST['body']) );
    
    $query = 'INSERT INTO notes (topic, keywords, body) VALUES (:topic, :keywords, :body)';
    $query_params = array(':topic' => $topic, ':keywords' => $keywords, ':body' => $body);
    try {
        $stmt = $db->prepare($query);
        $stmt->execute($query_params);
    } catch (PDOException $ex) { die($ex->getMessage()); }

?>