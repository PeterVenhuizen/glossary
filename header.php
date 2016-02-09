        <?php include 'functions.php'; ?>
        <!-- http://www.bitrepository.com/php-autologin.html -->
        <header id="page_header" style="background-color: <?php echo $colors[array_rand($colors)]; ?>;">
            <nav>
            	<a href="index.php"><img src="assets/img/menu_icon.png"></a>
                <a href="add_post.php"><img src="assets/img/add_icon.png"></a>
                <a href="list.php"><img src="assets/img/list_icon.png"></a>
                <a href="login.php"><img src="assets/img/login_icon.png"></a>
            	<ul>
            		<li><a href="add_post.php">Add post</a></li>
            		<li><a href="list.php">List</a></li>
            	</ul>
            <form id="search" method="POST" action="search.php">
            	<input type="search" id="search_field" name="search" placeholder="Search...">
            </form>
            </nav>
        </header>
