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
				Gallery
			</h1>
			<i>Photos, short videos and other interesting media from club events</i>
		</div>
		<div class="txt">
			<p>
				Wow, such empty!
				<br>Sorry, since I'm such a new website we haven't got anything to show :(
				<br>We'll start making memories soon, and this space will fill up :)
			</p>
		</div>
<?php
	include 'modules/floatingMenu.php';
?>
	</div>
    </body>
</html>