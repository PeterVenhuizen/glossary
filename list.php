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
	</head>
	
	<body>      
		
		<?php include('header.php'); ?>
        
		<main>
            
            <article>
            
            <?php
                
                # Get all tags
                $tag_count = array();
                try {
                    $stmt = $db->prepare("SELECT tag, color FROM tags");
                    $stmt->execute();
                    if ($stmt->rowCount() > 0) { 
                        foreach ($stmt as $row) { $tag_count[$row['tag']] = array($row['color'], 0); }   
                    }
                } catch (PDOException $ex) { die(); }

				try { 
					$stmt = $db->prepare("SELECT id, topic, tags FROM glossary ORDER BY topic");
					$stmt->execute();
					if ($stmt->rowCount() > 0) {
						foreach ($stmt as $row) {
							
	                        $keywords = explode(';', $row['tags']);
	                        $tags = '';
	                        foreach ($keywords as &$key) {
	                            $color = $mysqli->query("SELECT color FROM tags WHERE tag = '$key' LIMIT 1")->fetch_object()->color;
	                            $tags .= '<a class="tag" style="background-color:' . $color . ';" href="view.php?cat=' . str_replace(' ' , '_', $key) . '">' . $key . '</a>';
	                        }
	                        
	                        echo '	<div class="list">
	                        			<h2><a href="view.php?single=' . $row['id'] . '">' . $row['topic'] . '</a></h2>
	                        			' . $tags . '
	                        		</div>';
							
						}
					}
				} catch (PDOException $ex) { die(); }

				/*
                # Count tags
                try {
                    $stmt = $db->prepare("SELECT id, topic, tags FROM glossary");
                    $stmt->execute();
                    if ($stmt->rowCount() > 0) {
                        foreach ($stmt as $row) {
                            $tags = explode(';', $row['tags']);
                            foreach ($tags as &$tag) {
                                if (isset($tag_count[$tag])) { $tag_count[$tag][1]++; }   
                            }
                        }
                    }
                } catch (PDOException $ex) { die(); }

                # Output tags
                foreach ($tag_count as $k => $v) {
                    echo '  <div class="tag_overview">
                                <h3 style="background-color: ' . $v[0] . ';">' . $k . '</h3>
                                <div class="tag_group">';
                    for ($x = 0; $x < $v[1]; $x++) { echo '<div class="square" style="background-color: ' . $v[0] . ';"></div>'; }
                    echo '      </div>
                            </div>';
                }
                */

            ?>
            
            </article>
            
		</main>
        
	</body>
</html>
