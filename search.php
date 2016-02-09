<!DOCTYPE html>

<?php 
	require_once('assets/config.php'); 
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
	</head>
	
	<body>      
		
		<?php include('header.php'); ?>
        
		<main>
            
            <?php
                
                if (isset($_POST['search'])) {
                               
		            try {
		                $val = mysql_real_escape_string($_POST['search']);
		                if (strlen($val) <= 4) {
		                	$stmt = $db->prepare("SELECT * FROM glossary WHERE topic LIKE ? OR tags LIKE ? ORDER BY added DESC");
		                	$stmt->execute(array("%" . $val . "%", "%" . $val . "%"));
		                } else {
		                	# http://www.mullie.eu/mysql-as-a-search-engine
				            $stmt = $db->prepare("SELECT * FROM glossary WHERE MATCH(topic, tags, body) AGAINST (:search IN BOOLEAN MODE) ORDER BY added DESC");
				            $stmt->bindValue('search', $val . '*');
				            $stmt->execute();
				        }
		                if ($stmt->rowCount() > 0) {
		                    foreach ($stmt as $row) {
		                        
		                        # Get tag colors
		                        $keywords = explode(';', $row['tags']);
		                        $tags = '';
		                        foreach ($keywords as &$key) {
		                            $color = $mysqli->query("SELECT color FROM tags WHERE tag = '$key' LIMIT 1")->fetch_object()->color;
		                            $tags .= '<a class="tag" style="background-color:' . $color . ';" href="view.php?cat=' . str_replace(' ' , '_', $key) . '">' . $key . '</a>';
		                        }
		                        
		                        echo '  <article>
		                                    <header>
		                                        <h2><a href="view.php?single=' . $row['id'] . '">' . $row['topic'] . '</a></h2>
		                                        <div class="options">
		                                            <img src="assets/img/options2.png" alt="options" class="view_options">
		                                            <div class="option edit_icon" value="' . $row['id'] . '"></div>
		                                            <div class="option delete_icon" value="' . $row['id'] . '"></div>
		                                        </div>
		                                    </header>
		                                    <time pubdate datetime="' . $row['added'] . '">' . time_elapsed_string($row['added']) . '</time>
		                                    <div class="body">
		                                        <p>' . html_entity_decode($row['body']) . '</p>
		                                    </div>
		                                    <footer>' . $tags . '<div class="to_top">Back to top &#9650;</div></footer>
		                                </article>';
		                        
		                    }
		                } else {
		                	echo '	<article>
										<div class="body">
											No results found for <strong>' . $_POST['search'] . '</strong>.
										</div>
									</article>';
		                }
		            } catch (PDOException $ex) { die(); }
		            
				}

            ?>
            
            <?php include('footer.php'); ?>
            
		</main>
        
	</body>
</html>
