<?php
/* Index*/
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
        <meta http-equiv="refresh" content="8; url='http://cumberland-ac.weebly.com/'" />
		<link rel="stylesheet" type="text/css" href="<?php echo auto_version('css/styles.css'); ?>" media="screen" />

    </head>
    <body>
	<div class="parent-container">
		<div class="page-banner">
			<img class="banner" src="img/main-banner.png"/>
		</div>
		<div class="nav-buttons">
			<!-- add modified timestamp (php) to file URLs to force cache refresh -->
			<img class = "btn btn-Med" src = "<?php echo auto_version('img/btn-Med.png'); ?>">
			<img class = "btn btn-Cal" src = "<?php echo auto_version('img/btn-Cal.png'); ?>">
			<img class = "btn btn-Champ" src = "<?php echo auto_version('img/btn-Champ.png'); ?>">
			<img class = "btn btn-Merch" src = "<?php echo auto_version('img/btn-Merch.png'); ?>">
			<img class = "btn btn-MemArea" src = "<?php echo auto_version('img/btn-MemArea.png'); ?>">
		</div>
        <div class="coming-soon">New website coming soon :) <br />
            <br />
            Click <a href="http://cumberland-ac.weebly.com/">here</a> if you aren't redirected in 8 seconds
        </div>
	</div>
    </body>
</html>
