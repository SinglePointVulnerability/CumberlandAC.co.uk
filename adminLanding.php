<?php
	session_start();
	// index
	require 'modules/fileAutoVersionFunction.php';
    include('secure/authenticate.php');
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
			</p>
			<p>
				Depending on what your role is, you should see a link below, which allows you to manage and maintain a part of the Cumberland AC membership
			</p>
			<p>
				I think your role is: <?php echo $_SESSION["role"]; ?>
			</p>
		</div>
		<div class="txt">
		<?php
			if ($_SESSION["role"] == "site admin") {
				echo "<a href=\"addRaceTime.php\">Add race times</a><br>";
				echo "<a href=\"updateMemberDetails.php\">Update member details</a><br>";
			}
			if ($_SESSION["role"] == "race admin") {
				echo "<a href=\"addRaceTime.php\">Add race times</a><br>";
			}
			if ($_SESSION["role"] == "membership admin") {
				echo "<a href=\"updateMemberDetails.php\">Update member details</a><br>";
			}
		?>
		</div>
		<div class="txt">
			<table>
				<tr>
					<td><a href="index.php">Back</a></td>
					<td><a href="logout.php">Logout</a></td>
				</tr>
				<span id="errorBox"></span>
			</table>
		</div>
	</div>

    </body>
</html>
