        <?php include 'functions.php'; ?>
        <!-- http://code.tutsplus.com/tutorials/an-introduction-to-cookies--net-12482 -->
        <header id="page_header" style="background-color: <?php echo $colors[array_rand($colors)]; ?>;">
        	<style>
        	<?php
        		if (!isset($_SESSION['user'])) { echo "#a_logout { display: none; }"; }
        		else { echo "#a_login { display: none; }"; }
        	?>
        	</style>
            <nav>
            	<a href="index.php"><img src="assets/img/menu_icon.png"></a>
                <a href="add_post.php"><img src="assets/img/add_icon.png"></a>
                <a href="list.php"><img src="assets/img/list_icon.png"></a>
                <a href="login.php" id="a_login"><img src="assets/img/login_icon.png"></a>
				<a href="logout.php" id="a_logout"><img src="assets/img/logout_icon.png"></a>
            <form id="search" method="POST" action="search.php">
            	<input type="search" id="search_field" name="search" placeholder="Search...">
            </form>
            </nav>
        </header>
