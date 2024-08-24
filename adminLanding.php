<?php
	if(session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$callingPage = "adminLanding.php";
	require 'modules/fileAutoVersionFunction.php';
	if(!isset($_SESSION["loggedin"])) {
		include('secure/authenticate.php');
	}
?>
<html>
    <head>
        <!--<meta http-equiv="refresh" content="15; url='http://cumberland-ac.weebly.com/'" />-->
		<link rel="stylesheet" type="text/css" href="<?php echo auto_version('css/styles.css'); ?>" media="screen" />
		
		<?php require 'modules/navButtonScript.php'; ?>

    </head>
    <body>
	<div class="parent-container">
		<div class="page-banner">
			<img class="banner" src="img/main-banner.png" onclick="location.href='index.php'"/>
		</div>
		
		<?php require 'modules/navButtonDiv2.php'; ?>
		
		<div id="lvl2-btns" class="nav-buttons">
		</div>
		<div id="txt-id" class="txt">
			<h1>
				Admin Landing Page
			</h1>
		</div>
		<div class="txt">
			<p>
				Hi, <b><?php echo $_SESSION["name"]; ?></b>, this is the admins 'hang out'!
				<br>
				Depending on what your role is, you'll see a menu in the top-right of this page, which allows you to manage and maintain parts of the Cumberland AC membership
				<br>
				Your role is: <b><?php echo $_SESSION["role"]; ?></b>
			</p>
		</div>

<?php
	include 'modules/floatingMenu.php';
?>
	</div>

    </body>
</html>
