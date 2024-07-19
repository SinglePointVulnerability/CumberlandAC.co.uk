<?php
	session_start();
	include('secure/DBconn.php');	
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
			<img class="banner-marketing" src="media/marketing/2024-07-24_FoR-advert.png" onclick="location.href='media/marketing/2024-07-24_FoR-leaflet.pdf'"/>
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
				Cumberland AC Championship, 2024
			</h1>
		</div>
		<div class="txt">
			<p>
				Below is the list of races in each of the club championships
				for the 2024 season (some dates still to be confirmed)
			</p>
			<h2>
				<a href="openChampionshipResults.php">Open Championship</a> and <a href="ageGradedChampionshipResults.php">Age-Graded Championship</a>
			</h2>
			<h3>
				Short races <i>(under 10 miles)</i>
			</h3>
<?php
    // an array of chronological race dates, race names and race championship categories for the current year
    $arrChronologicalRacesOpenShort = array();
	$arrChronoRaceOpenShort = array();
	
	$sqlChronologicalRacesOpenShort = require('sql/viewChronologicalRacesOpenShort.php');

	// Get the races
	if(mysqli_multi_query($conn,$sqlChronologicalRacesOpenShort))
	{
		do{
			if($result=mysqli_store_result($conn)){
				while($row=mysqli_fetch_assoc($result)){
					array_push($arrChronologicalRacesOpenShort,
						array(
							  "RaceDate" => $row["RaceDate"],
							  "RaceDist" => $row["RaceDist"],
							  "RaceName" => $row["RaceName"],
							  "RaceOrganiser" => $row["RaceOrganiser"]
							 )
					);
				}
				mysqli_free_result($result);
			}
			if(mysqli_more_results($conn)) {
				// do nothing
			}
		} while(mysqli_more_results($conn) && mysqli_next_result($conn));
	}    
	else {
		echo "Oops! I couldn't find any Open Championship Short races for $RaceYear!";
	}

	echo "<table>\n";
	echo "<tr>\n";
	echo "<th>Race</th>\n";
	echo "<th>Distance</th>\n";
	echo "<th>Date</th>\n";
    echo "<th>Organiser</th>\n";
	echo "</tr>\n";
	
	//loop through the races array
	foreach($arrChronologicalRacesOpenShort as $arrChronoRaceOpenShort)
	{
		echo "<tr>";			
		echo "<td>";
		echo $arrChronoRaceOpenShort["RaceName"];
		echo "</td>";
		echo "<td>";
		echo round($arrChronoRaceOpenShort["RaceDist"],1,PHP_ROUND_HALF_UP) . "Km (" 
		   . round(($arrChronoRaceOpenShort["RaceDist"]/1.6093),1,PHP_ROUND_HALF_UP) . "Mi)";
		echo "</td>";
		echo "<td>";
			$today = date("Y-m-d");
			$time = strtotime($arrChronoRaceOpenShort["RaceDate"]);
			$computationformat = date("Y-m-d",$time);
			$readableformat = date('D jS F',$time);
			if($computationformat < $today) {
				echo "<strike>$readableformat</strike>";
			} else {
				echo "$readableformat";
			}
		echo "</td>";
		echo "<td>";
		echo $arrChronoRaceOpenShort["RaceOrganiser"];
		echo "</td>";
		echo "</tr>";
	}
	
	echo "</table>\n";

?>
			<h3>
				Long races <i>(10 miles and over)</i>
			</h3>
<?php
    // an array of chronological race dates, race names and race championship categories for the current year
    $arrChronologicalRacesOpenLong = array();
	$arrChronoRaceOpenLong = array();
	
	$sqlChronologicalRacesOpenLong = require('sql/viewChronologicalRacesOpenLong.php');

	// Get the races
	if(mysqli_multi_query($conn,$sqlChronologicalRacesOpenLong))
	{
		do{
			if($result=mysqli_store_result($conn)){
				while($row=mysqli_fetch_assoc($result)){
					array_push($arrChronologicalRacesOpenLong,
						array(
							  "RaceDate" => $row["RaceDate"],
							  "RaceDist" => $row["RaceDist"],
							  "RaceName" => $row["RaceName"],
							  "RaceOrganiser" => $row["RaceOrganiser"]
							 )
					);
				}
				mysqli_free_result($result);
			}
			if(mysqli_more_results($conn)) {
				// do nothing
			}
		} while(mysqli_more_results($conn) && mysqli_next_result($conn));
	}    
	else {
		echo "Oops! I couldn't find any Open Championship Long races for $RaceYear!";
	}

	echo "<table>\n";
	echo "<tr>\n";
	echo "<th>Race</th>\n";
	echo "<th>Distance</th>\n";
	echo "<th>Date</th>\n";
    echo "<th>Organiser</th>\n";
	echo "</tr>\n";
	
	//loop through the races array
	foreach($arrChronologicalRacesOpenLong as $arrChronoRaceOpenLong)
	{
		echo "<tr>";			
		echo "<td>";
		echo $arrChronoRaceOpenLong["RaceName"];
		echo "</td>";
		echo "<td>";
		echo round($arrChronoRaceOpenLong["RaceDist"],1,PHP_ROUND_HALF_UP) . "Km (" 
		   . round(($arrChronoRaceOpenLong["RaceDist"]/1.6093),1,PHP_ROUND_HALF_UP) . "Mi)";
		echo "</td>";
		echo "<td>";
			$today = date("Y-m-d");
			$time = strtotime($arrChronoRaceOpenLong["RaceDate"]);
			$computationformat = date("Y-m-d",$time);
			$readableformat = date('D jS F',$time);
			if($computationformat < $today) {
				echo "<strike>$readableformat</strike>";
			} else {
				echo "$readableformat";
			}
		echo "</td>";
		echo "<td>";
		echo $arrChronoRaceOpenLong["RaceOrganiser"];
		echo "</td>";
		echo "</tr>";
	}
	
	echo "</table>\n";

?>
			<h2>
				<a href="shortChampionshipResults.php">Short Championship</a>
			</h2>
<?php
    // an array of chronological race dates, race names and race championship categories for the current year
    $arrChronologicalRacesShort = array();
	$arrChronoRaceShort = array();
	
	$sqlChronologicalRacesShort = require('sql/viewChronologicalRacesShort.php');

	// Get the races
	if(mysqli_multi_query($conn,$sqlChronologicalRacesShort))
	{
		do{
			if($result=mysqli_store_result($conn)){
				while($row=mysqli_fetch_assoc($result)){
					array_push($arrChronologicalRacesShort,
						array(
							  "RaceDate" => $row["RaceDate"],
							  "RaceDist" => $row["RaceDist"],
							  "RaceName" => $row["RaceName"],
							  "RaceOrganiser" => $row["RaceOrganiser"]
							 )
					);
				}
				mysqli_free_result($result);
			}
			if(mysqli_more_results($conn)) {
				// do nothing
			}
		} while(mysqli_more_results($conn) && mysqli_next_result($conn));
	}    
	else {
		echo "Oops! I couldn't find any Short Championship races for $RaceYear!";
	}

	echo "<table>\n";
	echo "<tr>\n";
	echo "<th>Race</th>\n";
	echo "<th>Distance</th>\n";
	echo "<th>Date</th>\n";
    echo "<th>Organiser</th>\n";
	echo "</tr>\n";
	
	//loop through the races array
	foreach($arrChronologicalRacesShort as $arrChronoRaceShort)
	{
		echo "<tr>";			
		echo "<td>";
		echo $arrChronoRaceShort["RaceName"];
		echo "</td>";
		echo "<td>";
		echo round($arrChronoRaceShort["RaceDist"],1,PHP_ROUND_HALF_UP) . "Km (" 
		   . round(($arrChronoRaceShort["RaceDist"]/1.6093),1,PHP_ROUND_HALF_UP) . "Mi)";
		echo "</td>";
		echo "<td>";
			$today = date("Y-m-d");
			$time = strtotime($arrChronoRaceShort["RaceDate"]);
			$computationformat = date("Y-m-d",$time);
			$readableformat = date('D jS F',$time);
			if($computationformat < $today) {
				echo "<strike>$readableformat</strike>";
			} else {
				echo "$readableformat";
			}
		echo "</td>";
		echo "<td>";
		echo $arrChronoRaceShort["RaceOrganiser"];
		echo "</td>";
		echo "</tr>";
	}
	
	echo "</table>\n";

?>
			<h2>
				<a href="MTChallengeResults.php">Multi-Terrain Challenge</a>
			</h2>
<?php
    // an array of chronological race dates, race names and race championship categories for the current year
    $arrChronologicalRacesMT = array();
	$arrChronoRaceMT = array();
	
	$sqlChronologicalRacesMT = require('sql/viewChronologicalRacesMT.php');

	// Get the races
	if(mysqli_multi_query($conn,$sqlChronologicalRacesMT))
	{
		do{
			if($result=mysqli_store_result($conn)){
				while($row=mysqli_fetch_assoc($result)){
					array_push($arrChronologicalRacesMT,
						array(
							  "RaceDate" => $row["RaceDate"],
							  "RaceDist" => $row["RaceDist"],
							  "RaceName" => $row["RaceName"],
							  "RaceOrganiser" => $row["RaceOrganiser"]
							 )
					);
				}
				mysqli_free_result($result);
			}
			if(mysqli_more_results($conn)) {
				// do nothing
			}
		} while(mysqli_more_results($conn) && mysqli_next_result($conn));
	}    
	else {
		echo "Oops! I couldn't find any MT Challenge races for $RaceYear!";
	}

	echo "<table>\n";
	echo "<tr>\n";
	echo "<th>Race</th>\n";
	echo "<th>Distance</th>\n";
	echo "<th>Date</th>\n";
    echo "<th>Organiser</th>\n";
	echo "</tr>\n";
	
	//loop through the races array
	foreach($arrChronologicalRacesMT as $arrChronoRaceMT)
	{
		echo "<tr>";			
		echo "<td>";
		echo $arrChronoRaceMT["RaceName"];
		echo "</td>";
		echo "<td>";
		echo round($arrChronoRaceMT["RaceDist"],1,PHP_ROUND_HALF_UP) . "Km (" 
		   . round(($arrChronoRaceMT["RaceDist"]/1.6093),1,PHP_ROUND_HALF_UP) . "Mi)";
		echo "</td>";
		echo "<td>";
			$today = date("Y-m-d");
			$time = strtotime($arrChronoRaceMT["RaceDate"]);
			$computationformat = date("Y-m-d",$time);
			$readableformat = date('D jS F',$time);
			if($computationformat < $today) {
				echo "<strike>$readableformat</strike>";
			} else {
				echo "$readableformat";
			}
		echo "</td>";
		echo "<td>";
		echo $arrChronoRaceMT["RaceOrganiser"];
		echo "</td>";
		echo "</tr>";
	}
	
	echo "</table>\n";

?>
		</div>
	<div class="admin-login-banner">
		<a href="adminLanding.php">Admin Login</a>
	</div>
	</div>
    </body>
</html>