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
				<br>
				Depending on what your role is, you'll see a menu in the top-right of this page, which allows you to manage and maintain parts of the Cumberland AC membership
				<br>
				Your role is: <?php echo $_SESSION["role"]; ?>
			</p>
		</div>

		<div class = "floating-content">
			<form id = "admin-actions" method = "post" action = "adminLanding.php">
				<table>
					<tr>
						<td class = "txt"><b><?php echo $_SESSION["name"]; ?></b></td>
					</tr>
					<tr>
						<td class = "txt">Role: <b><?php echo $_SESSION["role"]; ?></b></td>
					</tr>
					<tr>
						<td class = "txt"><br><b>Actions</b></td>
					</tr>
		<?php
			if ($_SESSION["role"] == "site admin") {
				echo '<tr>' . PHP_EOL;
				echo '<td><button type = "submit" formaction = "addRaceTime.php">Add race time</button></td>' . PHP_EOL;
				echo '</tr>' . PHP_EOL;
				echo '<tr>' . PHP_EOL;
				echo '<td><button type = "submit" formaction = "updateMemberDetails.php">Member details</button></td>' . PHP_EOL;
				echo '</tr>' . PHP_EOL;
			}
			if ($_SESSION["role"] == "race admin") {
				echo '<tr>' . PHP_EOL;
				echo '<td><button type = "submit" formaction = "addRaceTime.php">Add race time</button></td>' . PHP_EOL;
				echo '</tr>' . PHP_EOL;
				echo '<tr>' . PHP_EOL;
				echo '<td><button type = "submit" formaction = "updateMemberDetails.php">Member details</button></td>' . PHP_EOL;
				echo '</tr>' . PHP_EOL;
			}
			if ($_SESSION["role"] == "membership admin") {
				echo '<tr>' . PHP_EOL;
				echo '<td><button type = "submit" formaction = "updateMemberDetails.php">Member details</button></td>' . PHP_EOL;
				echo '</tr>' . PHP_EOL;
			}
		?>
					<tr>
						<td><button type = "submit" formaction = "logout.php">Logout</button></td>
					</tr>
				</table>
			</form>
		</div>
	</div>

    </body>
</html>
