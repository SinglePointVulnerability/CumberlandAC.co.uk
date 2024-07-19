<?php
/* Index*/

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
<?php

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
				2024 Training Plan
			</h1>
		</div>
		<div class="txt">
			<p>
				The club provide a social run that follows different routes but
				always leaves Workington Leisure Centre every Tuesday at 18:00.
				The run is open to all club members and every effort is made to
				accommodate runners of all paces. There are stops to gather
				everyone back together, which gives plenty opportunity for the
				faster runners to double back and get a bit of extra mileage in.
				If you are thinking about joining Cumberland AC the Tuesday
				social run is a great place to start. We're happy for
				non-members to come along for a few weeks to see whether
				Cumberland AC is a good fit for them.
			</p>
			<p>
				Occasionally throughout the year the social run is replaced by
				one of our 'special' events. This includes a number of handicap
				races and some gentle intro to off-road running (e.g. the
				Rannerdale Bluebell run). More info to follow
			</p>
			<p>
				Club training is run by our coaches and is arranged in blocks
				which focus on a different develop area. This includes
				threshold training, speed work and everybody's favourite winter
				hill training. The location varies throughout the year
				depending on the type of training. The location and plan for
				each session is currently sent as a weekly e-mail to all
				members.
			</p>
			<p>This week's run leaders:<br>
			<table>
<?php
	//loop through the run leader array
	foreach($arrRunLeaderDataFull as $arrRunLeaderDataIndividual)
	{
		echo "<tr><td><img src='" . $arrRunLeaderDataIndividual["RunLeaderPhotoLink"] . "' style='width:200px;height:250px;'/></td><td>" . $arrRunLeaderDataIndividual["RunnerName"] . "</td><tr>";
	}
?>
			</table>
			<p>This week's training route:<br>
			<table>
<?php
	//loop through the training array
	foreach($arrTrainingDataFull as $arrTrainingDataIndividual)
	{
		echo "<tr><td><img src='" . $arrTrainingDataIndividual["RouteImageLink"] . "' style='width:50%;height:50%;'/></td><td>" . $arrTrainingDataIndividual["RouteDescription"] . "</td><tr>";
	}
?>
			</table>
			</p>
			<p>
				Please check back here for training updates
			</p>

		</div>
	</div>
    </body>
</html>