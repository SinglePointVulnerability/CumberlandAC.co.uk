<?php
	if(session_status() === PHP_SESSION_NONE) {
		session_start();
	}

function auto_version($file='')
{
	// script to force refresh of a file if it's been modified
	// since it was last cache'd in the user's browser
    if(!file_exists($file))
        return $file;
 
    $mtime = filemtime($file);
    return $file.'?'.$mtime;
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
				Up and coming club social events
			</h1>
		</div>
		<div class="txt">
			<p>
				Check here for dates and venues for club social events such as the annual presentation evening, Annual General Meetings (AGM), pie and peas night, club BBQ and more
			</p>
			<ul>
				<li>Presentation Evening for the 2023 Championship year - <b>Saturday 27th January @ Golf Club</b>, Whitehaven</li>
				<li>2024 AGM</li>
				<li>Pie and Peas Night</li>
				<li>Club handicap challenge</li>
			</ul>
		</div>
<?php
	include 'modules/floatingMenu.php';
?>
	</div>
    </body>
</html>