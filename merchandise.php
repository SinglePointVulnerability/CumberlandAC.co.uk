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
				Club Merchandise
			</h1>
		</div>
		<div class="txt">
			<p>
				Purchase of our official club kit is managed by Gillian, Brenda
				and Paula. If you want any official kit you can contact them at
				cacclubkit@yahoo.com. They hold a stock of racing kit (e.g.
				vests, t-shirts and shorts) in various sizes. They also
				occasionally place orders for special non-stock items (e.g. the
				O'Neill's hoodies) that require a minimum order size. Members
				will be sent an e-mail to gauge interest if we are considering
				placing an order for non-stock items.
			</p>
			<p>
				There is also a selection of unofficial club branded items
				available to buy >> <a href="https://pbteamwear.co.uk/collections/cumberland-ac">here</a> << from PB merchandise
			</p>
		</div>
<?php
	include 'modules/floatingMenu.php';
?>
	</div>
    </body>
</html>