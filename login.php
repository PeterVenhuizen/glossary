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
            	
                <label><input type="checkbox" name="login_remember">Remember me</label>
                <input type="hidden" name="referer" value="<?php $_SERVER['REQUEST_URI']; ?>" />
                
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

						$_SESSION['user'] = $row['username'];
                        
                        if (isset($_POST['login_remember'])) {
                            $digest = sha1(strval(rand(0,microtime(true)) + $row['username'] + strval(microtime(true))));
                            try {
                                $stmt = $db->prepare("UPDATE books_users SET digest=:digest WHERE username=:username");
                                $stmt->execute(array(':digest' => $digest, ':username' => $row['username']));
                                setcookie( 'rememberme', $digest, time()+3600*24*7, '/', '', false, true);
                            } catch (PDOException $ex) { die(); }
                        }
                        
						header("Location: " . $config['absolute_path']);
                        
					} else {
				?>
					<script>
						alert("Inloggen mislukt.");
						window.location.href = document.referrer;
					</script>
				<?php	
					}
                
                # Check if login cookie exists
                } else if (isset($_COOKIE['rememberme'])) {
                    
                    # Check database
                    try {
                        $stmt = $db->prepare("SELECT username FROM books_users WHERE digest=:digest");
                        $stmt->bindValue('digest', $_COOKIE['rememberme']);
                        $stmt->execute();
                    } catch (PDOException $ex) { die(); }
                    
                    # Check if digest was found
                    if ($stmt->rowCount() == 1) {
                        $row = $stmt->fetch();
                        $_SESSION['user'] = $row['username'];
                        
                        # Try to redirect to previous page
                        $url = (isset($_SESSION['lastpage'])) ? $_SESSION['lastpage'] : '/';
                        unset($_SESSION['lastpage']);
                        header ("Location: " . $url);
                    } else {
                        unset($_COOKIE['rememberme']);   
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
