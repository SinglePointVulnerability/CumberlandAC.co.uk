<?php
	if(session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	
include('secure/DBconn.php');

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
		
<?php
	// Function to undo the sanitisation
	function undoSanitisedInput($str) {
		return str_replace(
			["''", '""', "\\\\", "\\;", "\\--"],
			["'", '"', "\\", ";", "--"],
			$str
		);
	}

	// training data
	$arrTrainingDataFull = array();
	$arrTrainingDataIndividual = array();

    // an array of run leader data
    $arrRunLeaderDataFull = array();
	$arrRunLeaderDataIndividual = array();
	
 $sqlRunLeader = 'SELECT * FROM `tblrunleader` LEFT JOIN tblrunners ON tblrunleader.RunnerID = tblrunners.RunnerID WHERE LeaderActive = 1';

 $sqlTraining = 'SELECT * FROM `tbltraining` WHERE RouteActive = 1';

	// Get the run leader data
	$resultRunLeader = mysqli_query($conn,$sqlRunLeader);
	
	if(mysqli_num_rows($resultRunLeader) > 0)
	{	
		while($row = $resultRunLeader->fetch_assoc())
		{
			array_push($arrRunLeaderDataFull,
				array(
					  "RunnerName" => $row["RunnerFirstName"],
					  "RunLeaderID" => $row["RunLeaderID"],
					  "RunLeaderPhotoLink" => $row["RunLeaderPhotoLink"]
					 )
			);

		}
	}    
	else {
		echo "Oops! I couldn't find any active run leaders for this week";
	}

	// Get the training data
	$resultTraining = mysqli_query($conn,$sqlTraining);
	
	if(mysqli_num_rows($resultTraining) > 0)
	{
		while($row = $resultTraining->fetch_assoc())
		{
			array_push($arrTrainingDataFull,
				array(
					  "RouteDescription" => $row["RouteDescription"],
					  "TrainingID" => $row["TrainingID"],
					  "RouteImageLink" => $row["RouteImageLink"]
					 )
			);
		}
	}    
	else {
		echo "Oops! I couldn't find any active training data for this week";
	}


?>
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
				2024 Training Plan
			</h1>
		</div>
		<div class="txt">
		<table>
		<tr>
		<td>
			<b>Tuesday's Social Run:</b>
			<p>
				The club provide a social run that follows different routes but
				always leaves Workington Leisure Centre <b>every Tuesday at 18:00</b>.
				The run is open to all club members and every effort is made to
				accommodate runners of all paces. There are stops to gather
				everyone back together, which gives plenty opportunity for the
				faster runners to double back and get a bit of extra mileage in.
				If you are thinking about joining Cumberland AC the Tuesday
				social run is a great place to start. We're happy for
				non-members to come along for a few weeks to see whether
				Cumberland AC is a good fit for them.
			</p>
		</td>
		<td>
			<b>This week's Run Leaders:</b><br>
			<?php
				echo "<div class='photo-grid-container'>";
				//loop through the run leader array
				foreach($arrRunLeaderDataFull as $arrRunLeaderDataIndividual)
				{
					// echo "<td><img src='" . $arrRunLeaderDataIndividual["RunLeaderPhotoLink"] . "' style='width:200px;height:250px;'/></td><td>" . $arrRunLeaderDataIndividual["RunnerName"] . "</td>";
					echo '<div class="photo-grid-item">';
					echo $arrRunLeaderDataIndividual["RunnerName"];
					echo '<br>';
					echo '<img src="' . $arrRunLeaderDataIndividual["RunLeaderPhotoLink"] . '" alt="Image">';
					echo '</div>';
				}
				echo "</div>";
			?>
		</td>
		</tr>
		<tr>
		<td>
			<p>
				Occasionally throughout the year the social run is replaced by
				one of our 'special' events. This includes a number of handicap
				races and some gentle intro to off-road running (e.g. the
				Rannerdale Bluebell run). More info to follow
			</p>
		</td>
		<td>
		</td>
		</tr>
		</table>

		<table>
		<tr>
		<td>
			<b>Thursday's Club Training</b>
			<p>
				Club training is run by our coaches and is arranged in blocks
				which focus on a different develop area. This includes
				threshold training, speed work and everybody's favourite winter
				hill training. Club training is <b>every Thursday at 18:00</b>.
				The location varies throughout the year	depending on the type
				of training. The location and plan for each session is
				currently sent as a weekly e-mail to all members.
			</p>
		</td>
		<td>
			<p><b>This week's Instructions:</b><br>
			<table>
<?php
	//loop through the training array
	foreach($arrTrainingDataFull as $arrTrainingDataIndividual)
	{
		$originalText = undoSanitisedInput($arrTrainingDataIndividual["RouteDescription"]);
		
		echo "<tr><td><img src='" . $arrTrainingDataIndividual["RouteImageLink"] . "' style='width:50%;height:50%;'/></td><td>" . $originalText . "</td><tr>";
	}
?>

			</table>
		</td>
		</tr>
		</table>
			</p>
			<p>
				Please check back here for training updates
			</p>

		</div>
<?php
	include 'modules/floatingMenu.php';
?>
	</div>
    </body>
</html>