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
        <!--<meta http-equiv="refresh" content="15; url='http://cumberland-ac.weebly.com/'" />-->
		<link rel="stylesheet" type="text/css" href="<?php echo auto_version('css/styles.css'); ?>" media="screen" />

		<script>
		function displayResult(a) {
			var btnSet1 = "<img class = \"btn btn-lvl2 btn-Newsletter\" src = \"<?php echo auto_version('img/btn-Newsletter.png'); ?>\" onClick=\"location.href='newsletter.php'\">\n" +
						  "<img class = \"btn btn-lvl2 btn-Gallery\" src = \"<?php echo auto_version('img/btn-Gallery.png'); ?>\" onClick=\"location.href='gallery.php'\">\n" +
						  "<img class = \"btn btn-lvl2 btn-Documents\" src = \"<?php echo auto_version('img/btn-Documents.png'); ?>\" onClick=\"location.href='documents.php'\">\n" +
						  "<div class = \"btn\"></div>\n" +
						  "<div class = \"btn\"></div>\n";
						  
			var btnSet2 = "<img class = \"btn btn-lvl2 btn-Training\" src = \"<?php echo auto_version('img/btn-Training.png'); ?>\"  onClick=\"location.href='training.php'\">\n" +
						  "<img class = \"btn btn-lvl2 btn-SocialEvents\" src = \"<?php echo auto_version('img/btn-SocialEvents.png'); ?>\" onClick=\"location.href='social-events.php'\">\n" +
						  "<img class = \"btn btn-lvl2 btn-Races\" src = \"<?php echo auto_version('img/btn-Races.png'); ?>\"  onClick=\"location.href='races.php'\">" +
						  "<div class = \"btn\"></div>\n" +
						  "<div class = \"btn\"></div>\n";		
																 
			if (a == "btnMed") {
				document.getElementById("lvl2-btns").innerHTML = btnSet1;
			}
			else if (a == "btnCal") {
				document.getElementById("lvl2-btns").innerHTML = btnSet2;
			}
			else {
				document.getElementById("lvl2-btns").innerHTML = "";
			}	
		}
		</script>
    </head>
    <body>
	<div class="parent-container">
		<div class="page-banner">
			<img class="banner" src="img/main-banner.png" onclick="location.href='index.php'"/>
		</div>
		<div class="nav-buttons">
			<!-- add modified timestamp (php) to file URLs to force cache refresh -->
			<img class = "btn btn-lvl1 btn-Med" src = "<?php echo auto_version('img/btn-Med.png'); ?>" onClick="displayResult('btnMed')">
			<img class = "btn btn-lvl1 btn-Cal" src = "<?php echo auto_version('img/btn-Cal.png'); ?>" onClick="displayResult('btnCal')">
			<img class = "btn btn-lvl1 btn-Champ" src = "<?php echo auto_version('img/btn-Champ.png'); ?>" onClick="location.href='club-championship.php'">
			<img class = "btn btn-lvl1 btn-Merch" src = "<?php echo auto_version('img/btn-Merch.png'); ?>" onClick="location.href='merchandise.php'">
			<img class = "btn btn-lvl1 btn-MemArea" src = "<?php echo auto_version('img/btn-MemArea.png'); ?>" onClick="displayResult('')">
		</div>
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
	</div>
    </body>
</html>