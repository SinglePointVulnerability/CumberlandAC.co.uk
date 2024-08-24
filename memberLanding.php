<?php
	if(session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	// index
	$callingPage = "memberLanding.php";
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
				Member Landing Page
			</h1>
		</div>
		<div class="txt">
			<p>
				Hi, <b><?php echo $_SESSION["name"]; ?></b>, this is the members 'hang out'!
				<br>
				You'll see a menu in the top-right of this page, which allows you to navigate to members-only exclusive content 
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
