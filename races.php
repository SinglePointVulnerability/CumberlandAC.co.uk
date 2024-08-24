<?php
	if(session_status() === PHP_SESSION_NONE) {
		session_start();
	}

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


    // an array of chronological race dates, race names and race championship categories for the current year
    $arrChronologicalRaces = array();
	$arrChronoRace = array();
	
	$sqlChronologicalRaces = require('sql/viewChronologicalRaces.php');

	// Get the races
	if(mysqli_multi_query($conn,$sqlChronologicalRaces))
	{
		do{
			if($result=mysqli_store_result($conn)){
				while($row=mysqli_fetch_assoc($result)){
					array_push($arrChronologicalRaces,
						array(
							  "RaceDate" => $row["RaceDate"],
							  "RaceDist" => $row["Race Distance"],
							  "RaceName" => $row["RaceName"],
							  "RaceChampionship" => $row["Championship Category"]
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
		echo "Oops! I couldn't find any open championship races for $RaceYear!";
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
			<!-- <img class="banner-marketing" src="media/marketing/2024-07-24_FoR-advert.png" onclick="location.href='media/marketing/2024-07-24_FoR-leaflet.pdf'"/> -->
		</div>

		<?php require 'modules/navButtonDiv2.php'; ?>
		
		<div id="lvl2-btns" class="nav-buttons">
		</div>
		<div id="txt-id" class="txt">
			<h1>
				Cumberland AC Championship, 2024
			</h1>
		</div>
		<div class="txt">
			<p>
				Below is the list of races in the club championships for the 
				2024 season (some dates still to be confirmed)
			</p>
			
			<table>
				<tr>
					<th>
						Date
					</th>
					<th>
						Distance
					</th>
					<th>
						Name
					</th>
					<th>
						Championship
					</th>
				</tr>
<?php
		//loop through the $arrRaces array
		foreach($arrChronologicalRaces as $arrChronoRace)
		{
			echo "<tr>";			
			echo "<td>";
				$today = date("Y-m-d");
				$time = strtotime($arrChronoRace["RaceDate"]);
				$computationformat = date("Y-m-d",$time);
				$readableformat = date('D jS F',$time);
				if($computationformat < $today) {
					echo "<strike>$readableformat</strike>";
				} else {
					echo "$readableformat";
				}

			echo "</td>";
			echo "<td>";
			echo round($arrChronoRace["RaceDist"],1,PHP_ROUND_HALF_UP) . "Km (" 
			   . round(($arrChronoRace["RaceDist"]/1.6093),1,PHP_ROUND_HALF_UP) . "Mi)";
			echo "</td>";
			echo "<td>";
			echo $arrChronoRace["RaceName"];
			echo "</td>";
			echo "<td>";
			echo $arrChronoRace["RaceChampionship"];
			echo "</td>";
			echo "</tr>";
		}
?>
			</table>
		</div>
<?php
	include 'modules/floatingMenu.php';
?>
	</div>
    </body>
</html>