<?php
	session_start();
	
	include('secure/DBconn.php');

    // Storing Session
    if(isset($_SESSION['login_user'])) {
        $user_check=$_SESSION['login_user'];
    }
    else {
        $user_check='';
    }

    if(isset($_GET['display'])) {
        $champDisplay=$_GET['display'];
    }
    else {
        $champDisplay='times';
    }
    // to access archived championship records
    if(isset($_GET['champYear'])) {
        $RaceYear = $_GET['champYear'];
    }

	$RaceYear = 2023;

    $ChampionshipName = "Short Championship";

    // if it exists, empty the array of raceIDs
    unset($arrRaceDetails);

    // if it exists, empty the array of Div 3 RunnerIDs, FirstNames, Surnames, and ChampionshipIDs
    unset($arrDiv3RunnerIDsNamesChampIDs);

    // if it exists, empty the array of Div 2 RunnerIDs, FirstNames, Surnames, and ChampionshipIDs
    unset($arrDiv2RunnerIDsNamesChampIDs);

    // if it exists, empty the array of Div 1 RunnerIDs, FirstNames, Surnames, and ChampionshipIDs
    unset($arrDiv1RunnerIDsNamesChampIDs);

    $sqlGetShortChampRaces = "select RaceID, RaceDate, RaceName from tblRaces WHERE RaceCode IN (8,9) AND RaceDate BETWEEN '$RaceYear-01-01' AND '$RaceYear-12-31' ORDER BY RaceDate";

    //run the queries and populate the multi-dimensional array $arrRaceDetails
    $arrRaceDetails = array();

    //run the queries and populate the multi-dimensional array $arrRaces
    $arrRaces = array();

    // a multi dimensional array of RunnerIDs, RunnerNames, RaceIDs, RaceTimes and RacePoints
    $arrRankedRaceTimes = array();

    // an array of all races
    $arrAllRaces = array();

    // Get the short champ races
    if(mysqli_multi_query($conn,$sqlGetShortChampRaces))
    {
        do{
            if($result=mysqli_store_result($conn)){
                while($row=mysqli_fetch_assoc($result)){
                    array_push($arrRaceDetails,
                        array(
                              "RaceID" => $row["RaceID"],
                              "RaceName" => $row["RaceName"],
                              "RaceDate" => $row["RaceDate"],
                              "RaceCategory" => "sprint",
                              "RaceChampionship" => "short"
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
        echo "Oops! I couldn't find any short championship races for $RaceYear!";
    }

    // count the size of $arrRaceDetails for use in the code
    $arrRaceDetails_items = count($arrRaceDetails);

	$sqlShortChamp = require('sql/viewShortChamp.php');
	
	// Get the Short Championship Race Times
	if(mysqli_multi_query($conn,$sqlShortChamp))
	{
		do{
			if($result=mysqli_store_result($conn)){
				while($row=mysqli_fetch_assoc($result)){
					array_push($arrRankedRaceTimes,
						array(
							  "ChampYear" => $row["ChampYear"],
							  "RaceCode" => $row["RaceCode"],
							  "RaceDate" => $row["RaceDate"],
							  "RunnerDiv" => $row["RunnerDiv"],
							  "RunnerSex" => $row["RunnerSex"],
							  "ChampTotal" => $row["ChampTotal"],
							  "RaceTime" => $row["RaceTime"],
							  "RacePoints" => $row["RacePoints"],
							  "RunnerID" => $row["RunnerID"],
							  "RunnerName" => $row["RunnerName"],
							  "RunnerSex" => $row["RunnerSex"]
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
		echo "Oops! I couldn't find any short championship races for $RaceYear!";
	}


	$sqlShortChampRaces = require('sql/viewShortChampRaces.php');
	
	// Get the Short Championship Races
	if(mysqli_multi_query($conn,$sqlShortChampRaces))
	{
		do{
			if($result=mysqli_store_result($conn)){
				while($row=mysqli_fetch_assoc($result)){
					array_push($arrAllRaces,
						array(
							  "RaceCode" => $row["RaceCode"],
							  "RaceDate" => $row["RaceDate"],
							  "RaceID" => $row["RaceID"]
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
		echo "Oops! I couldn't find any short championship races for $RaceYear!";
	}

?>

<html lang="en">
<head>
  <meta charset="utf-8">
    <title>Cumberland AC - <?php echo $ChampionshipName . " " . $RaceYear; ?></title>
    <meta name="description" content="Cumberland AC <?php echo $ChampionshipName . " " . $RaceYear; ?>">
  <link rel="stylesheet" href="css/styles-20190528.css<?php echo "?date=" . date("Y-m-d_H-i"); ?>">

  <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]--> 
</head>
<body>
    <h1>Cumberland AC <?php echo $ChampionshipName . " " . $RaceYear; ?></h1>

<?php

require 'champViewNavTable.php';

echo "<br>";
    
echo "<table class=\"tblTimesPointsOuterOpen\">";

    // first row: Race Names
    echo "<tr height=\"250px\">";
    echo "<td class = \"tblTimesPointsOuterSpacer\"></td>";

    //loop through the $arrRaceDetails array
    foreach($arrRaceDetails as $arrRaceDetail)
    {
        echo "<td>";

        if($arrRaceDetail["RaceCategory"] == "sprint")
        {
            echo "<div class='fntSprint rotate90'>";   
        }

        echo $arrRaceDetail["RaceName"];
        echo "</div>";
        echo "</td>";              
    }

    //second row: Race Dates
    echo "<tr>";
    echo "<td class = \"tblTimesPointsOuterSpacer\"></td>";

    //loop through the $arrRaceDetails array, this time for the dates
    foreach($arrRaceDetails as $arrRaceDetail)
    {
        echo "<th>";

        if($arrRaceDetail["RaceCategory"] == "sprint")
        {
            echo "<div class='fntSprint'>";   
        }

        $createDate = date_create($arrRaceDetail["RaceDate"]);
        echo date_format($createDate,"D");
        echo "<br>";
        echo date_format($createDate,"d");        
        echo "<br>";
        echo date_format($createDate,"M");
        echo "</div>";
        echo "</th>";
    }

    echo "<th style='background-color:black;'></th>";
    echo "</tr>";
    
	$currentRunnerDiv = 0;
	$currentRunnerSex = "";
	
	// code to display races and runners
    foreach($arrRankedRaceTimes as $arrRT)
    {
		$raceCounter = 0;
		echo "<tr>";
		
		if($arrRT["RunnerDiv"] <> $currentRunnerDiv or $arrRT["RunnerSex"] <> $currentRunnerSex)
		{
			//mark the start of the division and sex
			$currentRunnerDiv = $arrRT["RunnerDiv"];
			$currentRunnerSex = $arrRT["RunnerSex"];
			echo "<td>Div $currentRunnerDiv : $currentRunnerSex</td></tr>\n<tr>";
		}


		//output the runner name in the first cell only
		if($raceCounter == 0) 
		{
			echo "<td class=\"tblTimesPointsInnerNames\">" . $arrRT["RunnerName"] . "</td>";
		}

		foreach($arrAllRaces as $arrRace)
		{
			$raceCounter = $raceCounter + 1;
			//open the race times cell
			echo "<td class=\"tblTimesPointsInner\">";
			
			// Get the race times
			if(mysqli_multi_query($conn,"SELECT RunnerID, RaceID, RaceTime, (101 - rank_division_gender_split(RunnerID, RaceID, RaceTime)) AS RacePoints FROM `tblRaceTimes` WHERE RunnerID = " . $arrRT["RunnerID"] . " AND RaceID = " . $arrRace["RaceID"]))
			{
				do{
					if($result=mysqli_store_result($conn)){
						while($row=mysqli_fetch_assoc($result)){
							//output runner details associated with that race
							echo "<div class=\"fontRaceTime\" style='display:inline'>" . $row["RaceTime"] . "</div>\n";
							echo "<div class=\"fontRacePoints\" style='display:inline'>(" . $row["RacePoints"] . ")</div>\n";
						}
						mysqli_free_result($result);
					}
					if(mysqli_more_results($conn)) {
						// do nothing
					}
				} while(mysqli_more_results($conn) && mysqli_next_result($conn));
			}
			echo "</td>";
		}
		echo "<td class=\"fontRacePoints\">" . $arrRT["ChampTotal"] . "</td>";
		echo "</tr>";
	}
    
    echo "</table>";
    echo "<br>";

include 'champViewNavTable.php';

 
?>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
	$('[data-toggle="toggle"]').change(function(){
		$(this).parents().next('.hide').toggle();
	});
});
</script>
</body>
</html>
