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
        $RaceYear = 2022;//$_GET['champYear'];
    }
	
	$RaceYear = 2023;

    $ChampionshipName = "Age Graded Championship";

    // if it exists, empty the array of raceIDs
    unset($arrRaces);

    $sqlGetOpenChampSprintRaces = "select RaceID, RaceDate, RaceName from tblRaces WHERE RaceCode IN (1,9) AND RaceDate BETWEEN '$RaceYear-01-01' AND '$RaceYear-12-31' ORDER BY RaceDate";

    $sqlGetOpenChampSprintMedRaces = "select RaceID, RaceDate, RaceName from tblRaces WHERE RaceCode IN (32) AND RaceDate BETWEEN '$RaceYear-01-01' AND '$RaceYear-12-31' ORDER BY RaceDate";

    $sqlGetOpenChampMiddleRaces = "select RaceID, RaceDate, RaceName from tblRaces WHERE RaceCode IN (2) AND RaceDate BETWEEN '$RaceYear-01-01' AND '$RaceYear-12-31' ORDER BY RaceDate";

    $sqlGetOpenChampLongRaces = "select RaceID, RaceDate, RaceName from tblRaces WHERE RaceCode IN (4) AND RaceDate BETWEEN '$RaceYear-01-01' AND '$RaceYear-12-31' ORDER BY RaceDate";

    //run the queries and populate the multi-dimensional array $arrRaces
    $arrRaces = array();

    // a multi dimensional array of RunnerIDs, RunnerNames, RaceIDs, RaceTimes and RacePoints
    $arrRankedRaceTimes = array();

    // an array of all races
    $arrAllRaces = array();

        // Get the sprint races
        if(mysqli_multi_query($conn,$sqlGetOpenChampSprintRaces))
        {
            do{
                if($result=mysqli_store_result($conn)){
                    while($row=mysqli_fetch_assoc($result)){
                        array_push($arrRaces,
                            array(
                                  "RaceID" => $row["RaceID"],
                                  "RaceName" => $row["RaceName"],
                                  "RaceDate" => $row["RaceDate"],
                                  "RaceCategory" => "sprint",
                                  "RaceChampionship" => "open"
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
            echo "Oops! I couldn't find any open championship sprint distance races for $RaceYear!";
        }
        // Get the sprintMed races
        if(mysqli_multi_query($conn,$sqlGetOpenChampSprintMedRaces))
        {
            do{
                if($result=mysqli_store_result($conn)){
                    while($row=mysqli_fetch_assoc($result)){
                        array_push($arrRaces,
                            array(
                                  "RaceID" => $row["RaceID"],
                                  "RaceName" => $row["RaceName"],
                                  "RaceDate" => $row["RaceDate"],
                                  "RaceCategory" => "sprintMed",
                                  "RaceChampionship" => "open"
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
            echo "Oops! I couldn't find any open championship sprintMed distance races for $RaceYear!";
        }
        // Get the middle races
        if(mysqli_multi_query($conn,$sqlGetOpenChampMiddleRaces))
        {
            do{
                if($result=mysqli_store_result($conn)){
                    while($row=mysqli_fetch_assoc($result)){
                        array_push($arrRaces,
                            array(
                                  "RaceID" => $row["RaceID"],
                                  "RaceName" => $row["RaceName"],
                                  "RaceDate" => $row["RaceDate"],
                                  "RaceCategory" => "middle",
                                  "RaceChampionship" => "open"
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
            echo "Oops! I couldn't find any open championship middle distance races for $RaceYear!";
        }
        // Get the long races
        if(mysqli_multi_query($conn,$sqlGetOpenChampLongRaces))
        {
            do{
                if($result=mysqli_store_result($conn)){
                    while($row=mysqli_fetch_assoc($result)){
                        array_push($arrRaces,
                            array(
                                  "RaceID" => $row["RaceID"],
                                  "RaceName" => $row["RaceName"],
                                  "RaceDate" => $row["RaceDate"],
                                  "RaceCategory" => "long",
                                  "RaceChampionship" => "open"
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
            echo "Oops! I couldn't find any open championship long distance races for $RaceYear!";
        }

    // count the size of $arrRaces for use in the code
    $arrRaces_items = count($arrRaces);

	$sqlAgeGradedChamp = require('sql/viewAgeGradedChamp.php');
	
	// Get the Open Championship Race Times
	if(mysqli_multi_query($conn,$sqlAgeGradedChamp))
	{
		do{
			if($result=mysqli_store_result($conn)){
				while($row=mysqli_fetch_assoc($result)){
					array_push($arrRankedRaceTimes,
						array(
							  "ChampYear" => $row["ChampYear"],
							  "RunnerSex" => $row["RunnerSex"],
							  "ChampTotal" => $row["ChampTotal"],
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
		echo "Oops! I couldn't find any open championship race times for $RaceYear!";
	}

	//echo $arrRankedRaceTimes["RunnerName"];

	$sqlAgeGradedChampRaces = require('sql/viewAgeGradedChampRaces.php');
	
	// Get the Open Championship Races
	if(mysqli_multi_query($conn,$sqlAgeGradedChampRaces))
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
		echo "Oops! I couldn't find any open championship races for $RaceYear!";
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

    //loop through the $arrRaces array
    foreach($arrRaces as $arrRaceDetail)
    {
        echo "<td>";

        if($arrRaceDetail["RaceCategory"] == "sprint")
        {
            echo "<div class='fntSprint rotate90'>";   
        }
        if($arrRaceDetail["RaceCategory"] == "sprintMed")
        {
            echo "<div class='fntSprint rotate90'>";   
        }
        if($arrRaceDetail["RaceCategory"] == "middle")
        {
            echo "<div class='fntMiddle rotate90'>";   
        }
        if($arrRaceDetail["RaceCategory"] == "long")
        {
            echo "<div class='fntLong rotate90'>";   
        }

        echo $arrRaceDetail["RaceName"];
        echo "</div>";
        echo "</td>";              
    }

    //second row: Race Dates
    echo "<tr>";
    echo "<td class = \"tblTimesPointsOuterSpacer\"></td>";

    //loop through the $arrRaces array, this time for the dates
    foreach($arrRaces as $arrRaceDetail)
    {
        echo "<th>";

        if($arrRaceDetail["RaceCategory"] == "sprint")
        {
            echo "<div class='fntSprint'>";   
        }
        if($arrRaceDetail["RaceCategory"] == "sprintMed")
        {
            echo "<div class='fntSprint'>";   
        }
        if($arrRaceDetail["RaceCategory"] == "middle")
        {
            echo "<div class='fntMiddle'>";   
        }
        if($arrRaceDetail["RaceCategory"] == "long")
        {
            echo "<div class='fntLong'>";   
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

	$currentRunnerSex = "";
	
	// code to display races and runners
    foreach($arrRankedRaceTimes as $arrRT)
    {
		$raceCounter = 0;
		echo "<tr>";
		
		
		if($arrRT["RunnerSex"] <> $currentRunnerSex)
		{
			//mark the start of the sex
			$currentRunnerSex = $arrRT["RunnerSex"];
			echo "<td>$currentRunnerSex</td></tr>\n<tr>";
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