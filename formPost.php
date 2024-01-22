<?php
	session_start();
    include('secure/authenticate.php');
	$RaceYear = "2024";
?>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Cumberland AC DB Admin - Process Update</title>
  <meta name="description" content="Cumberland AC DB Admin - Update Database">
  <meta name="author" content="West Coast Web Design">

  <link rel="stylesheet" href="css/styles.css<?php echo "?date=" . date("Y-m-d_H-i"); ?>">

  <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
</head>
    
<?php   
	// create your log file and set to append (a+)
	$myLog = fopen("formPost_log.txt", "a+") or die("Unable to open file!");
	$tDateFormat = "Y-m-d_H-i T (\G\M\T O): ";

	$tLogWrite = "\n- - - Start of log entries for: " . date($tDateFormat) . "\n";
	fwrite($myLog,$tLogWrite);

    // Note: all dates are stored in reverse in SQL e.g. 2017-12-31 for 31st Dec 2017
    //       so convert dates on this page, before they are stored in the DB
    
if(empty($_POST["parentPage"]))  
{
        $whatInfo = "none";
        // DELETE THIS CODE ONCE TESTING IS FINISHED
    // rankRaceTies(1);
}
else {
        $whatInfo = htmlspecialchars($_POST["parentPage"]);

		$tLogWrite = date($tDateFormat) . "Parent page: " . $whatInfo . "\n";
		fwrite($myLog,$tLogWrite);
}

    
if($whatInfo == "addRaceTime")
{
    $runnersAndTimes = array();
    
    $i = 1;
    // how many rows of runner details to expect
    $fieldCount = htmlspecialchars($_POST['fieldCount']);
    
    $txtChamp = htmlspecialchars($_POST['champSelect']);
    
	$tLogWrite = date($tDateFormat) . "Field Count: " . $fieldCount . ". Championship: " . $txtChamp . "\n";
	fwrite($myLog,$tLogWrite);

    if ($txtChamp == "open") {
        $txtRaceID = explode("_",htmlspecialchars($_POST['raceSelect2']));
    }
    else {
        $txtRaceID = explode("_",htmlspecialchars($_POST['raceSelect1']));
    }
    
	$tLogWrite = date($tDateFormat) . "Race ID: " . $txtRaceID[0] . "\n";
	fwrite($myLog,$tLogWrite);

    
    while($i <= $fieldCount) {
        //populate the array, whilst ignoring blank values
        if(!empty(htmlspecialchars($_POST["ddl1_runner_$i"]))) {
            $raceTimeFormat = sprintf('%02d:%02d:%02d', htmlspecialchars($_POST["race_time_hours_$i"]), htmlspecialchars($_POST["race_time_minutes_$i"]), htmlspecialchars($_POST["race_time_seconds_$i"]));
            //array: runner division, runner id and name, race time
            array_push($runnersAndTimes,htmlspecialchars($_POST["ddl1_runner_$i"]), htmlspecialchars($_POST["ddl2_runner_$i"]), $raceTimeFormat);
			
			$tLogWrite = date($tDateFormat) . "Added to array: Runner Division: " . htmlspecialchars($_POST["ddl1_runner_$i"]);
			$tLogWrite .= ". Name and ID: " . htmlspecialchars($_POST["ddl2_runner_$i"]) . " Race Time: " . $raceTimeFormat . "\n";
			fwrite($myLog,$tLogWrite);
        }
    $i++;
    // print_r($runnersAndTimes);
    }

    // loop through the runners and times array and update the relevant race
    $j = 0;
    while($j < count($runnersAndTimes)) {
        
        $txtRunnerID = explode("_", $runnersAndTimes[($j+1)]);
        $raceTimeFormat = $runnersAndTimes[($j+2)];
        
        //if there's already a time for this race and runner combo, update instead of insert               
        $sql4 = "SELECT * FROM tblRaceTimes WHERE RaceID='" . $txtRaceID[0] . "' AND RunnerID='" . $txtRunnerID[0] . "'";
                
        $result4 = mysqli_query($conn,$sql4);


		//calculate and store runner age on race day
		$sql5 = "SELECT calc_runner_age_on_race_day($txtRunnerID[0], $txtRaceID[0]) AS age_on_race_day";
		$result5 = mysqli_query($conn,$sql5);
		// output data of each row
		while($row = $result5->fetch_assoc())
		{
			$runner_age_on_race_day = $row["age_on_race_day"];
		}

		//calculate and store runner age on 1st Jan
		$sql6 = "SELECT calc_runner_age_on_1st_jan($txtRunnerID[0], $txtRaceID[0]) AS age_on_1st_jan";
		$result6 = mysqli_query($conn,$sql6);
		// output data of each row
		while($row = $result6->fetch_assoc())
		{
			$runner_age_on_1st_jan = $row["age_on_1st_jan"];
		}		
		
		
        if(mysqli_num_rows($result4) > 0)
        {
            $sql2 = "UPDATE tblRaceTimes SET RaceTime='" . $raceTimeFormat . "', RunnerAgeOnRaceDay=$runner_age_on_race_day, RunnerAgeOn1stJan = $runner_age_on_1st_jan WHERE RaceID='" . $txtRaceID[0] . "' AND RunnerID='" . $txtRunnerID[0] . "'";
        }           
        else
        {
            //insert if there isn't already a time for this race and runner combo
            $sql2 = "INSERT INTO tblRaceTimes (RaceID,RunnerID, RaceTime, RunnerAgeOnRaceDay, RunnerAgeOn1stJan) " .
                "VALUES ('" . $txtRaceID[0] . "', " .
                "'" . $txtRunnerID[0] . "', " .
                "'" . $raceTimeFormat . "', " .
				"'" . $runner_age_on_race_day . "', " .
				"'" . $runner_age_on_1st_jan . "')";
        }

		$tLogWrite = date($tDateFormat) . "SQL: " . $sql4 . ". Rows returned: " . mysqli_num_rows($result4) . "\n";
		fwrite($myLog,$tLogWrite);
		
        if (mysqli_query($conn,$sql2) === TRUE)
        {
            echo "<br>$raceTimeFormat added for $txtRunnerID[1]!<br>";
			$tLogWrite = date($tDateFormat) . "SQL SUCCESS: " . $sql2 . "\n";
			fwrite($myLog,$tLogWrite);
        }
        else
        {
			echo "<br><br>Oops! There was a problem adding the race time $raceTimeFormat for $txtRunnerID[1]. The error has been saved to the error log. <b><u>Please let Stu know and he'll try to fix it</u></b> :)";

			$tLogWrite = date($tDateFormat) . "SQL ERROR: " . $conn->error . "\n";
			fwrite($myLog,$tLogWrite);
        }


        // need to check the runner is 35 or over on 1st Jan of current championship year

		if($runner_age_on_1st_jan >= 35)
		{
			// update tblRaceTimes with Masters Time based on age on race day

			$tLogWrite = date($tDateFormat) . "Runner >=35 on race day." . "\n";
			fwrite($myLog,$tLogWrite);

			// calculate masters time and store in variable
			$sql_calc_masters_time = "SELECT calc_masters_time($txtRunnerID[0], $runner_age_on_race_day, $txtRaceID[0], '$raceTimeFormat') AS masters_time";
			$result = mysqli_query($conn, $sql_calc_masters_time);

			$tLogWrite = date($tDateFormat) . "SQL: $sql_calc_masters_time" . "\n";
			fwrite($myLog,$tLogWrite);

			while($row = $result->fetch_assoc())
			{
				$masters_time = $row["masters_time"];
			}				

			// update MastersRaceTime column for this runner and race
			$sql_update_masters_time = "UPDATE tblRaceTimes SET MastersRaceTime = '$masters_time' WHERE RaceID='" . $txtRaceID[0] . "' AND RunnerID='" . $txtRunnerID[0] . "'";

			if (mysqli_query($conn,$sql_update_masters_time) === TRUE)
			{
				echo "<br>Masters race time $masters_time added for $txtRunnerID[1]!<br>";
				$tLogWrite = date($tDateFormat) . "SQL SUCCESS: " . $sql_update_masters_time . "\n";
				fwrite($myLog,$tLogWrite);
			}
		}
		if($runner_age_on_1st_jan < 35)
		{
			// don't update tblRaceTimes with Masters Time based on age on race day
			$tLogWrite = date($tDateFormat) . "Runner not >=35 on race day." . "\n";
			fwrite($myLog,$tLogWrite);			
		}
    $j+=3;        
    }
}
$tLogWrite = "- - - End of log entries for: " . date("Y-m-d_H-i") . "\n";
fwrite($myLog,$tLogWrite);
fclose($myLog);
?>
<body>
<br><br>
    Tables updated...
<br><br>
<table>
    <tr>
        <td><a href="addRaceTime.php">Back</a></td>
    </tr>
</table>
</body>
</html>