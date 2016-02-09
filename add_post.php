<!DOCTYPE html>

<?php require_once('assets/config.php'); ?>

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
        <script type="text/javascript" src="assets/ckeditor/ckeditor.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {	
                
                var w = $('#form_post').width();
                
				CKEDITOR.timestamp='ABCD'; // Reload all .js and .css files
				CKEDITOR.config.width = w;
				CKEDITOR.config.height = '500px';
				CKEDITOR.config.allowedContent = true;
				CKEDITOR.config.extraPlugins = 'youtube';
				CKEDITOR.config.youtube_width = '600';
				CKEDITOR.config.youtube_height = '450';
				CKEDITOR.config.youtube_related = false;
				CKEDITOR.config.forcePasteAsPlainText = true;
				CKEDITOR.replace( 'form_body',
                {
					filebrowserBrowseUrl: "assets/ckeditor/plugins/image_browser.php",
					filebrowserUploadUrl: "upload.php"
				});
                
			});
        </script>
        <style>
            
        </style>
		<meta name="viewport" content="initial-scale=1">
	</head>
	
	<body>      
		
		<?php 
			include('header.php'); 
			if(!isset($_SESSION['user'])) { 
				echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=login.php">';
				die();
			} else {
        ?>
		
		<main>
            
            <form action="" method="POST" id="form_post">
            
                <label for="form_topic" class="form_label">Topic:</label>
                <input type="text" name="form_topic" id="form_topic">
                
                <textarea class="ckeditor" name="form_body" id="form_body"></textarea>
                
                <label for="form_keywords" class="form_label">Keywords:</label>
                <input type="text" name="form_keywords" id="form_keywords">
                
                <input type="submit" name="form_submit" id="form_submit" value="Submit">
                
            </form>
            
            <?php
                if (isset($_POST['form_submit'])) {
                    $topic = $_POST['form_topic'];
                    $keywords = strtolower($_POST['form_keywords']);
                    $body = ( get_magic_quotes_gpc() ? htmlspecialchars(stripslashes($_POST['form_body'])) : htmlspecialchars($_POST['form_body']) );

                    $query = 'INSERT INTO glossary (topic, tags, body) VALUES (:topic, :keywords, :body)';
                    $query_params = array(':topic' => $topic, ':keywords' => $keywords, ':body' => $body);
                    try {
                        $stmt = $db->prepare($query);
                        $stmt->execute($query_params);
                    } catch (PDOException $ex) { die($ex->getMessage()); }
                    
                    # ADD LABELS
                    $tags = explode(';', $keywords);
                    
                    # Remove used tags and colors
                    try {
                        $stmt = $db->prepare('SELECT * FROM tags');
                        $stmt->execute();
                    } catch (PDOException $ex) { die(); }
                    if ($stmt->rowCount() > 0) {
                        foreach ($stmt as $row) {
                            
                            # Remove color
                            if (in_array($row['color'], $colors)) { unset($colors[array_search($row['color'], $colors)]); }
                            
                            # Remove tag
							if (in_array($row['tag'], $tags)) { unset($tags[array_search($row['tag'], $tags)]); }

                        }
                    }
                    
                    # Add new labels
                    foreach ($tags as &$tag) {
                        $query = 'INSERT INTO tags (tag, color) VALUES (:tag, :color)';
                        $query_params = array(':tag' => $tag, ':color' => $colors[array_rand($colors)] );
                        try { 
                            $stmt = $db->prepare($query);
                            $stmt->execute($query_params);
                        } catch (PDOException $ex) { die(); }
                    }
                    echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=index.php">';
                }
            ?>
            
		</main>
	<?php
		}
	?>
        
	</body>
</html>