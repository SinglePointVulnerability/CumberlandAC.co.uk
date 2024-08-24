<?php
	if(session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$callingPage = "welfareOfficers.php";
    require 'secure/authenticate.php';
	require 'modules/fileAutoVersionFunction.php';
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
			<!-- <img class="banner-marketing" src="media/marketing/2024-07-24_FoR-advert.png" onclick="location.href='media/marketing/2024-07-24_FoR-leaflet.pdf'"/> -->
		</div>
		<div class="main-page-content">
			<?php require 'modules/navButtonDiv2.php'; ?>
			
			<div id="lvl2-btns" class="nav-buttons">
			</div>
			<div id="txt-id" class="txt">
				<h1>Club Welfare Officers</h1>
			</div>
			<div class="txt">
				<p>If you have any concerns or need to speak to someone in confidence about grievances, harassment, or bullying, please contact one of our Welfare Officers below:</p>
			</div>
			<div class="officer">
				<div class="officer-name">Shaun Cavanagh</div>
				<div class="officer-contact">Phone: </div>
				<div class="officer-contact">Email: shauncavanagh47@gmail.com</div>
			</div>

			<div class="officer">
				<div class="officer-name">Stuart Buchanan</div>
				<div class="officer-contact">Phone: </div>
				<div class="officer-contact">Email: buchie61@yahoo.co.uk</div>
			</div>

			<div class="officer">
				<div class="officer-name">Andrea Challenger</div>
				<div class="officer-contact">Phone: </div>
				<div class="officer-contact">Email: andrea.challenger@sky.com</div>
			</div>

			<div class="officer">
				<div class="officer-name">Victoria Gee</div>
				<div class="officer-contact">Phone: </div>
				<div class="officer-contact">Email: </div>
			</div>
		</div>
<?php
	include 'modules/floatingMenu.php';
?>
	</div>
    </body>
</html>
