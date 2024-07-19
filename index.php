<?php
	session_start();
	// index
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
			<img class="banner-marketing" src="media/marketing/2024-07-24_FoR-advert.png" onclick="location.href='media/marketing/2024-07-24_FoR-leaflet.pdf'"/>
		</div>
		<div class="main-page-content">
			<?php require 'modules/navButtonDiv2.php'; ?>
			
			<div id="lvl2-btns" class="nav-buttons">
			</div>
			<div id="txt-id" class="txt">
				<h1>
					About Us
				</h1>
			</div>
			<div class="txt">
				<p>
					We are a friendly, small running club based in Workington, Cumbria. Affiliated in 1983 we have been running for 40 years.
					<br>
					The club normally meets every Tuesday at Workington Leisure Centre, Workington at 5:50pm for a 6:00pm start. 
					<br>
					The main group is organised such that runners of a wide range of abilities can run together.
					​<br>
					Training sessions take place on Thursday evenings; details of current training can be found on the Training page.
					<br>
					Thinking of joining us? Find our membership form <a href = "documents.php"><b>here</b></a>
				</p>
			</div>
			<div class="txt">
				<h1>
					Link to old site
				</h1>
				<p>
					If there's something you can't find here, or you just want to browse the old website, click <a href="http://cumberland-ac.weebly.com/">here</a>
				</p>
			</div>
		</div>
	</div>
    </body>
</html>
