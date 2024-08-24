<?php
	if(session_status() === PHP_SESSION_NONE) {
		session_start();
	}
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
				Documents
			</h1>
			<i>Important documents to ensure we dot the i's and cross the t's</i>
		</div>
		<div class="txt">
			<p>
				Below are links to our membership form and other documents that
				outline how we function as a club, including the rules under
				which we operate; they're here to protect club members, club
				officers and generally help things run smoothly.
			</p>
			<p>
				<s>Some of the documents are private to Cumberland AC members
				only, if you can't see the document you expect, please make
				sure you're logged in to the <b>Members Area</b>.</s><br>
				If you can't see the document you expect, please contact the
				club secretary at <b>wilson01925@hotmail.com</b>.
			</p>
			<ul>
				<li><a href="media/docs/Membership-Form.pdf">Membership Form</a></li>
				<li><a href="media/docs/CAC-Club-Constitution.pdf">Club Constitution</a></li>
				<li><a href="media/docs/CAC-Grievance-And-Disciplinary-Policy.pdf">Grievance and Disciplinary Policy</a></li>
				<li><a href="media/docs/CAC-Privacy-Notice.pdf">Club Privacy Notice</a></li>
				<li><a href="media/docs/CAC-Club-Inclusion-Policy.pdf">Club Inclusion Policy</a></li>				
				<li>Club Committee - Who's Who</li>
			</ul>
		</div>
<?php
	include 'modules/floatingMenu.php';
?>
	</div>
    </body>
</html>