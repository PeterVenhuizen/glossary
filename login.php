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
		<meta name="viewport" content="initial-scale=1">
        <script src="assets/js/functions.js"></script>
	</head>
	
	<body>      
		
		<?php include('header.php'); ?>
        
		<main>
            
            <form method="POST" action="login.php">
            	
            	<label for="login_username" class="form_label">Username:</label>
            	<input type="text" name="login_username">

            	<br>
            	
            	<label for="login_password" class="form_label">Password:</label>
            	<input type="password" name="login_password">
            	
            	<br>
            	
            	<input type="submit" name="login_submit" value="Login">
            	
            </form>
            
            <?php
				if (isset($_POST['login_submit'])) { 
	
					try {
						$stmt = $db->prepare("SELECT * FROM books_users WHERE username = :username");
						$stmt->execute(array(':username' => $_POST['login_username']));
					} catch (PDOException $ex) { }

					$login_ok = false; 

					$row = $stmt->fetch();
					if ($row) { 
						$check_password = hash('sha256', $_POST['login_password'] . $row['salt']); 
						for($round = 0; $round < 65536; $round++) { 
							$check_password = hash('sha256', $check_password . $row['salt']); 
						} 
						 
						if ($check_password === $row['password']) { 
							$login_ok = true; 
						} 
					} 

					if ($login_ok) { 
						unset($row['salt']); 
						unset($row['password']);

						$_SESSION['user'] = $row; 
						header("Location: " . $config['absolute_path']);
					} else {
				?>
					<script>
						alert("Inloggen mislukt.");
						window.location.href = document.referrer;
					</script>
				<?php	
					}
				}
            ?>
            
            <?php #include('footer.php'); ?>
            
		</main>
        
	</body>
</html>
