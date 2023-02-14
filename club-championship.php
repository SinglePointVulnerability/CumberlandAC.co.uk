<?php
	session_start();
	
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
						  "<img class = \"btn btn-lvl2 btn-Documents\" src = \"<?php echo auto_version('img/btn-Documents.png'); ?>\"  onClick=\"location.href='documents.php'\">\n" +
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
				Cumberland AC Championship, 2023
			</h1>
		</div>
		<div class="txt">
			<p>
				Below is the list of races in each of the club championships
				for the 2023 season (some dates still to be confirmed)
			</p>
			<h2>
				<a href="openChampionshipResults.php">Open Championship</a>
			</h2>
			<h3>
				Short races <i>(under 10 miles)</i>
			</h3>
			<table>
				<tr>
					<th>Race</th>
					<th>Distance</th>
					<th>Date</th>
					<th>Organiser</th>
				</tr>
				<tr>
					<td>Carlisle Resolution</td>
					<td>6.2mi (10km)</td>
					<td>22nd January</td>
					<td>Sport in Action</td>
				</tr>
				<tr>
					<td>Lorton</td>
					<td>6.2mi (10km)</td>
					<td>11th March</td>
					<td>Friends of Lorton School Events</td>
				</tr>
				<tr>
					<td>Millom Lighthouse</td>
					<td>6.2mi (10km)</td>
					<td>May (TBC)</td>
					<td>Millom Striders</td>
				</tr>
				<tr>
					<td>Millom Sea Wall 7 Miler</td>
					<td>7mi (11.3km)</td>
					<td>2nd July</td>
					<td>Laurie Gribbin</td>
				</tr>
				<tr>
					<td>Lambfoot Loop</td>
					<td>6.2mi (10km)</td>
					<td>July (TBC)</td>
					<td>DAC</td>
				</tr>
				<tr>
					<td>Netherhall 10k</td>
					<td>6.2mi (10km)</td>
					<td>2nd August</td>
					<td>NAC</td>
				</tr>
				<tr>
					<td>Wigton 10k</td>
					<td>6.2mi (10km)</td>
					<td>October (TBC)</td>
					<td>Wigton Road Runners</td>
				</tr>
			</table>
			<h3>
				Long races <i>(10 miles and over)</i>
			</h3>
			<table>
				<tr>
					<th>Race</th>
					<th>Distance</th>
					<th>Date</th>
					<th>Organiser</th>
				</tr>
				<tr>
					<td>North Lakes Half Marathon</td>
					<td>13.1mi (21km)</td>
					<td>2nd January</td>
					<td>Events Up North</td>
				</tr>
				<tr>
					<td>Netherhall 10m</td>
					<td>10mi (16.1km)</td>
					<td>26th February</td>
					<td>CAC</td>
				</tr>
				<tr>
					<td>Coniston 14</td>
					<td>14mi (22.5km)</td>
					<td>25th March</td>
					<td>Coniston 14</td>
				</tr>
				<tr>
					<td>3 Village 10</td>
					<td>10mi (16.1km)</td>
					<td>2nd April</td>
					<td>Sport in Action</td>
				</tr>
				<tr>
					<td>Keswick Half Marathon`</td>
					<td>13.1mi (21km)</td>
					<td>24th September</td>
					<td>Keswick Rugby Club</td>
				</tr>
				<tr>
					<td>Great Cumbrian Run</td>
					<td>13.1mi (21km)</td>
					<td>1st October</td>
					<td>Better</td>
				</tr>
				<tr>
					<td>Brampton to Carlisle</td>
					<td>10mi (16.1km)</td>
					<td>19th November</td>
					<td>Border Harriers</td>
				</tr>
				<tr>
					<td>Any Marathon Race</td>
					<td>26.2mi (42.2km)</td>
					<td>Before 31st December</td>
					<td>Various</td>
				</tr>
			</table>
			<h2>
				<a href="shortChampionshipResults.php">Short Championship</a>
			</h2>
			<table>
				<tr>
					<th>Race</th>
					<th>Distance</th>
					<th>Date</th>
					<th>Organiser</th>
				</tr>
				<tr>
					<td>Carlisle Resolution</td>
					<td>3.1mi (5km)</td>
					<td>22nd January</td>
					<td>Sport in Action</td>
				</tr>
				<tr>
					<td>X-Border (Carlisle to Gretna)</td>
					<td>6.2mi (10km)</td>
					<td>5th February</td>
					<td>Innovation Sports Ltd</td>
				</tr>
				<tr>
					<td>Barepot</td>
					<td>3.1mi (5km)</td>
					<td>March (TBC)</td>
					<td>CAC</td>
				</tr>
				<tr>
					<td>Round the Houses</td>
					<td>4.5mi (7.2km)</td>
					<td>April (TBC)</td>
					<td>KAC</td>
				</tr>
				<tr>
					<td>Barepot</td>
					<td>3.1mi (5km)</td>
					<td>May (TBC)</td>
					<td>CAC</td>
				</tr>
				<tr>
					<td>Castle Series - Carlisle</td>
					<td>3.1mi (5km)</td>
					<td>31st May</td>
					<td>Sport in Action</td>
				</tr>
				<tr>
					<td>Festival of Running - Kirkbride</td>
					<td>6.2mi (10km)</td>
					<td>2nd July</td>
					<td>Sport in Action</td>
				</tr>
				<tr>
					<td>Whitehaven Harbourside</td>
					<td>3.1mi (5km)</td>
					<td>July (TBC)</td>
					<td>CAC</td>
				</tr>
				<tr>
					<td>Festival of Running - Workington</td>
					<td>3.1mi (5km)</td>
					<td>28th August</td>
					<td>CAC</td>
				</tr>
				<tr>
					<td>Castle Series - Carlisle</td>
					<td>3.1mi (5km)</td>
					<td>6th September</td>
					<td>Sport in Action</td>
				</tr>
				<tr>
					<td>River Run</td>
					<td>6.2mi (10km)</td>
					<td>November (TBC)</td>
					<td>DH Runners</td>
				</tr>
				<tr>
					<td>Ulverston Pudding Run</td>
					<td>6.2mi (10km)</td>
					<td>December (TBC)</td>
					<td></td>
				</tr>
			</table>
			<h2>
				<a href="MTChallengeResults.php">Multi-Terrain Challenge</a>
			</h2>
			<table>
				<tr>
					<th>Race</th>
					<th>Distance</th>
					<th>Date</th>
					<th>Organiser</th>
				</tr>
				<tr>
					<td>Bampton Trail</td>
					<td>4.3mi (7km)</td>
					<td>8th January</td>
					<td>Fellside Events</td>
				</tr>
				<tr>
					<td>Two Riggs</td>
					<td>5.6mi (9km)</td>
					<td>18th February</td>
					<td>Kong</td>
				</tr>
				<tr>
					<td>Jarret's Jaunt</td>
					<td>5.9mi (9.5km)</td>
					<td>March (TBC)</td>
					<td>CFR</td>
				</tr>
				<tr>
					<td>Isel Cross</td>
					<td>5.5mi (8.9km)</td>
					<td>April (TBC)</td>
					<td>DAC</td>
				</tr>
				<tr>
					<td>Hay O' Trail</td>
					<td>3.7mi (6km)</td>
					<td>May (TBC)</td>
					<td>DAC</td>
				</tr>
				<tr>
					<td>Two Tops Dash</td>
					<td>6mi (9.7km)</td>
					<td>31st May</td>
					<td>NAC</td>
				</tr>
				<tr>
					<td>Blencathra</td>
					<td>8.1mi (13km)</td>
					<td>2nd July</td>
					<td>Eden Runners</td>
				</tr>
				<tr>
					<td>Buttermere Horseshoe - Darren Holloway Memorial Race 
					(short)</td>
					<td>13mi (21km)</td>
					<td>July (TBC)</td>
					<td>CFR</td>
				</tr>
				<tr>
					<td>Barrow Fell - Keswick Show</td>
					<td>4mi (6.5km)</td>
					<td>28th August</td>
					<td>KAC</td>
				</tr>
				<tr>
					<td>Loweswater Show</td>
					<td>2.6mi (4.2km)</td>
					<td>6th September</td>
					<td>CFR</td>
				</tr>
				<tr>
					<td>Keswick XC</td>
					<td>4.3 - 5.6mi (7 - 9km)</td>
					<td>November (TBC)</td>
					<td>DH Runners</td>
				</tr>
				<tr>
					<td>Workington XC</td>
					<td>4.3 - 5.6mi (7 - 9km)</td>
					<td>December (TBC)</td>
					<td></td>
				</tr>
			</table>

		</div>
	<div class="admin-login-banner">
		<a href="login-form.php">Add Race Times / Admin Login</a>
	</div>
	</div>
    </body>
</html>