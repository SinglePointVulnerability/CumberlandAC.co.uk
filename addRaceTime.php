<?php
	session_start();
    include('secure/authenticate.php');
	$RaceYear = "2024";
?>
<html lang="en">
<head>
  <title>Cumberland AC Race Time Admin, <?php echo $RaceYear; ?></title>
  <meta name="description" content="Cumberland AC Race Time Admin - Add Race Time">
  <meta name="author" content="Pixel District">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/styles.css<?php echo "?date=" . date("Y-m-d_H-i"); ?>">

  <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
</head>
    
<?php
if($_SESSION["loggedin"]=='')
{
    // if the user isn't logged in, don't load the page
}
else if (str_contains($_SESSION['name'], 'club_stat_tk') || str_contains($_SESSION['name'], 'my.pyne@gmail.com'))
{
	//load all open champ races from the current year into an array
    $sqlOpenChamp = "SELECT RaceID, RaceName, RaceCode FROM tblRaces WHERE RaceDate BETWEEN '" . $RaceYear . "-01-01' AND '" . $RaceYear . "-12-31' AND RaceCode IN(1,2,4,9,32) ORDER BY field(RaceCode,1,9,32,2,4), RaceDate ASC";

    $intOpenChampSprintCount=0;
    $intOpenChampSprintMedCount=0;
    $intOpenChampMiddleCount=0;
    $intOpenChampLongCount=0;

    //load all short champ races from the current year into an array
    $sqlShortChamp = "SELECT RaceID, RaceName FROM tblRaces " .
                    "WHERE RaceDate BETWEEN '" . $RaceYear . "-01-01' AND '" . $RaceYear . "-12-31' " .
                    "AND RaceCode IN (8,9) " .
                    "ORDER BY RaceDate ASC";
    $intShortChampCount=0;

    //load all MT Challenge races from the current year into an array
    $sqlMTChall = "SELECT RaceID, RaceName FROM tblRaces " .
                    "WHERE RaceDate BETWEEN '" . $RaceYear . "-01-01' AND '" . $RaceYear . "-12-31' " .
                    "AND RaceCode IN (16) " .
                    "ORDER BY RaceDate ASC";
    $intMTChallCount=0;
    
    //load all runners who have paid their subscriptions
    $sqlRunners = "SELECT RunnerID, RunnerFirstName, RunnerSurname, RunnerDiv FROM tblRunners WHERE RunnerDiv > 0 AND RunnerDOB <> '0000-00-00' AND RunnerFullSocialMember = 'Full' ORDER BY RunnerDiv ASC, RunnerSurname ASC";
    $intRunnerDiv1Count=0;
    $intRunnerDiv2Count=0;
    $intRunnerDiv3Count=0;
    $intRunnerDiv4Count=0;
    $intRunnerDiv5Count=0;
    $intRunnerDiv6Count=0;	

?>
    <body>
    <script type="text/javascript">
        var nestedVals_openRaces = {
            'open': {
<?php
    $result = mysqli_query($conn,$sqlOpenChamp);

    if (mysqli_num_rows($result) > 0)
    {
        // output data of each row
        while($row = $result->fetch_assoc())
        {
            if($row["RaceCode"]=='1' || $row["RaceCode"]=='9') {
                if($intOpenChampSprintCount==0){
                    // output the first line of JavaScript 
                    echo "'Sprint Distance': [\"" . $row["RaceID"] . "_" . $row["RaceName"] . "\"";
                }
                else{
                    // output the next line(s) of JavaScript
                    echo ", \"" . $row["RaceID"] . "_" . $row["RaceName"] . "\"";
                }
                $intOpenChampSprintCount++;
            }
            if($row["RaceCode"]=='32') {
                if($intOpenChampSprintMedCount==0){
                    // finish the previous line of JavaScript and output the first new line of JavaScript 
                    if($intOpenChampSprintCount>0){
						echo "], " .
                             "\n'SprintMed Distance': [\"" . $row["RaceID"] . "_" . $row["RaceName"] . "\"";
					}
					else{
                        echo "\n'SprintMed Distance': [\"" . $row["RaceID"] . "_" . $row["RaceName"] . "\"";
					}
                }
                else{
                    // output the next line(s) of JavaScript
                    echo ", \"" . $row["RaceID"] . "_" . $row["RaceName"] . "\"";
                }
                $intOpenChampSprintMedCount++;
            }
            if($row["RaceCode"]=='2') {
                if($intOpenChampMiddleCount==0){
                    // finish the previous line of JavaScript and output the first new line of JavaScript 
                    if($intOpenChampSprintMedCount>0 || $intOpenChampSprintCount>0){
						echo "], " .
                             "\n'Middle Distance': [\"" . $row["RaceID"] . "_" . $row["RaceName"] . "\"";
					}
					else{
                        echo "\n'Middle Distance': [\"" . $row["RaceID"] . "_" . $row["RaceName"] . "\"";
					}
                }
                else{
                    // output the next line(s) of JavaScript
                    echo ", \"" . $row["RaceID"] . "_" . $row["RaceName"] . "\"";
                }
                $intOpenChampMiddleCount++;
            }
            if($row["RaceCode"]=='4') {
                if($intOpenChampLongCount==0){
                    // finish the previous line of JavaScript and output the first new line of JavaScript 
                    if($intOpenChampMiddleCount>0 || $intOpenChampSprintMedCount>0 || $intOpenChampSprintCount>0){
						echo "], " .
						     "\n'Long Distance': [\"" . $row["RaceID"] . "_" . $row["RaceName"] . "\"";
					}
					else{
						echo "\n'Long Distance': [\"" . $row["RaceID"] . "_" . $row["RaceName"] . "\"";
					}
                }
                else{
                    // output the next line(s) of JavaScript
                    echo ", \"" . $row["RaceID"] . "_" . $row["RaceName"] . "\"";
                }
                $intOpenChampLongCount++;
            }
        }
    }
    //output the last line of JavaScript
    echo "] \n";

    //the output following this chunk of code should look like this:
    /*        
        'Sprint Distance': ['race1ID_name', 'race2ID_name'],
        'SprintMed Distance': ['race1ID_name', 'race2ID_name'],
        'Middle Distance': ['race1ID_name', 'race2ID_name'],
        'Long Distance': ['race1ID_name', 'race2ID_name']
    */
?>
            },
                
            'short': {
<?php
    $result = mysqli_query($conn,$sqlShortChamp);

    if (mysqli_num_rows($result) > 0)
    {
        // output data of each row
        while($row = $result->fetch_assoc())
        {
            if($intShortChampCount==0) {
                echo "\"" . $row["RaceID"] . "_" . $row["RaceName"] . "\": []";                
            }
            else{
                echo ", " .
                     "\n \"" . $row["RaceID"] . "_" . $row["RaceName"] . "\": []";                
            }
            $intShortChampCount++;
        }
    }
    //output the last line of JavaScript
    echo "\n";
    //the output following this chunk of code should look like this:
    /*
            'race1ID_name',
            'race2ID_name'    
    */
?>
        },
            'MT': {
<?php
    $result = mysqli_query($conn,$sqlMTChall);

    if (mysqli_num_rows($result) > 0)
    {
        // output data of each row
        while($row = $result->fetch_assoc())
        {
            if($intMTChallCount==0) {
                echo "\"" . $row["RaceID"] . "_" . $row["RaceName"] . "\": []";                
            }
            else{
                echo ", " .
                     "\n \"" . $row["RaceID"] . "_" . $row["RaceName"] . "\": []";                
            }
            $intMTChallCount++;
        }
    }
    //output the last line of JavaScript
    echo "\n";
    //the output following this chunk of code should look like this:
    /*
            'race1ID_name',
            'race2ID_name'    
    */
?>
        }
            
    }
        
        var nestedVals_runners = {
<?php
    $result = mysqli_query($conn,$sqlRunners);

    if (mysqli_num_rows($result) > 0)
    {
        // output data of each row
        while($row = $result->fetch_assoc())
        {
            if($row["RunnerDiv"]=='1') {
                if($intRunnerDiv1Count==0){
                    // output the first line of JavaScript 
                    echo "'1': {'" . $row["RunnerID"] . "_" . $row["RunnerFirstName"] . " " . $row["RunnerSurname"] . "': []";
                }
                else{
                    // output the next line(s) of JavaScript
                    echo ", '" . $row["RunnerID"] . "_" . $row["RunnerFirstName"] . " " . $row["RunnerSurname"] . "': []";
                }
                $intRunnerDiv1Count++;
            }
            if($row["RunnerDiv"]=='2') {
                if($intRunnerDiv2Count==0){
                    // finish the previous line of JavaScript and output the first new line of JavaScript 
                    echo "}, " .
                         "\n'2': {'" . $row["RunnerID"] . "_" . $row["RunnerFirstName"] . " " . $row["RunnerSurname"] . "': []";
                }
                else{
                    // output the next line(s) of JavaScript
                    echo ", '" . $row["RunnerID"] . "_" . $row["RunnerFirstName"] . " " . $row["RunnerSurname"] . "': []";
                }
                $intRunnerDiv2Count++;
            }
            if($row["RunnerDiv"]=='3') {
                if($intRunnerDiv3Count==0){
                    // finish the previous line of JavaScript and output the first new line of JavaScript 
                    echo "}, " .
                         "\n'3': {'" . $row["RunnerID"] . "_" . $row["RunnerFirstName"] . " " . $row["RunnerSurname"] . "': []";
                }
                else{
                    // output the next line(s) of JavaScript
                    echo ", '" . $row["RunnerID"] . "_" . $row["RunnerFirstName"] . " " . $row["RunnerSurname"] . "': []";
                }
                $intRunnerDiv3Count++;
            }
            if($row["RunnerDiv"]=='4') {
                if($intRunnerDiv4Count==0){
                    // finish the previous line of JavaScript and output the first new line of JavaScript 
                    echo "}, " .
                         "\n'4': {'" . $row["RunnerID"] . "_" . $row["RunnerFirstName"] . " " . $row["RunnerSurname"] . "': []";
                }
                else{
                    // output the next line(s) of JavaScript
                    echo ", '" . $row["RunnerID"] . "_" . $row["RunnerFirstName"] . " " . $row["RunnerSurname"] . "': []";
                }
                $intRunnerDiv4Count++;
            }
            if($row["RunnerDiv"]=='5') {
                if($intRunnerDiv5Count==0){
                    // finish the previous line of JavaScript and output the first new line of JavaScript 
                    echo "}, " .
                         "\n'5': {'" . $row["RunnerID"] . "_" . $row["RunnerFirstName"] . " " . $row["RunnerSurname"] . "': []";
                }
                else{
                    // output the next line(s) of JavaScript
                    echo ", '" . $row["RunnerID"] . "_" . $row["RunnerFirstName"] . " " . $row["RunnerSurname"] . "': []";
                }
                $intRunnerDiv5Count++;
            }
            if($row["RunnerDiv"]=='6') {
                if($intRunnerDiv6Count==0){
                    // finish the previous line of JavaScript and output the first new line of JavaScript 
                    echo "}, " .
                         "\n'6': {'" . $row["RunnerID"] . "_" . $row["RunnerFirstName"] . " " . $row["RunnerSurname"] . "': []";
                }
                else{
                    // output the next line(s) of JavaScript
                    echo ", '" . $row["RunnerID"] . "_" . $row["RunnerFirstName"] . " " . $row["RunnerSurname"] . "': []";
                }
                $intRunnerDiv6Count++;
            }
        }
    //output the last line of JavaScript - keep within 'if' statement to avoid php parse errors should the query return no results
    echo "} \n";     
    }
 
?>
    }

    function createOption(ddl, text, value) {
        var opt = document.createElement('option');
        opt.value = value;
        
        // if there's an underscore, the string will need to be split
        if (text.indexOf('_') > -1)
        {
            // split the 'ID_name' text into just text for the user
            var txtOpt = text.split("_");
            opt.text = txtOpt[1];
        }
        else{
            // normal output if no underscore is found
            opt.text = text;            
        }
        ddl.options.add(opt);
    }

    function createOptions(optionsArray, ddl) {
        for (i = 0; i < optionsArray.length; i++) {
            createOption(ddl, optionsArray[i], optionsArray[i]);
        }
    }
    function configureDDL2(champSelect, raceSelect1, raceSelect2) {
        raceSelect1.options.length = 0;
        raceSelect2.options.length = 0;
        createOption(raceSelect1, "Pick 2nd Option", "");
        var raceSelect1keys = Object.keys(nestedVals_openRaces[champSelect.value]);
        createOptions(raceSelect1keys, raceSelect1)
    }
    function configureDDL3(champSelect, raceSelect1, raceSelect2) {
        raceSelect2.options.length = 0;
        createOption(raceSelect2, "Pick 3rd Option", "");
        var raceSelect2keys = nestedVals_openRaces[champSelect.value][raceSelect1.value];
        createOptions(raceSelect2keys, raceSelect2);
    }

    function configureDDL_runners(champSelect, raceSelect1) {
        raceSelect1.options.length = 0;
        createOption(raceSelect1, "Pick Runner", "");
        var raceSelect1keys = Object.keys(nestedVals_runners[champSelect.value]);
        createOptions(raceSelect1keys, raceSelect1)
    }
        
    function clearDDLentries(ddlToClear) {
        document.getElementById("ddl1_runner_" + ddlToClear).selectedIndex = 0; //0 = option 1
        document.getElementById("ddl2_runner_" + ddlToClear).options.length = 0;
        document.getElementById("ddl2_runner_" + ddlToClear).style.border = "none";
        document.getElementById("race_time_hours_" + ddlToClear).selectedIndex = 0;
        document.getElementById("race_time_hours_" + ddlToClear).style.border = "none";
        document.getElementById("race_time_minutes_" + ddlToClear).selectedIndex = 0;
        document.getElementById("race_time_minutes_" + ddlToClear).style.border = "none";
        document.getElementById("race_time_seconds_" + ddlToClear).selectedIndex = 0;
        document.getElementById("race_time_seconds_" + ddlToClear).style.border = "none";
    }

    function validateForm(totalDDLRows) {
        var i = 0;
        var varErr = 0;

        document.getElementById("errorBox").value = "";
        document.getElementById("champSelect").style.border = "none";
        document.getElementById("raceSelect1").style.border = "none";
        document.getElementById("raceSelect2").style.border = "none";
        
        if(document.getElementById("champSelect").selectedIndex == 0) {
            varErr = 1;
            document.getElementById("champSelect").style.border = "3px solid red";
        }
        
        if(document.getElementById("raceSelect1").selectedIndex == 0) {
            varErr = 1;
            document.getElementById("raceSelect1").style.border = "3px solid red";
        }

        //because there aren't always options in the third drop down, there needs to be a count of items first
        if(document.getElementById("raceSelect2").length > 1) {
            if(document.getElementById("raceSelect2").selectedIndex == 0) {
            varErr = 1;
            document.getElementById("raceSelect2").style.border = "3px solid red";
            }
        }
        
        for (i = 1; i < (totalDDLRows+1); i++) {
            document.getElementById("ddl2_runner_" + i).style.border = "none";
            document.getElementById("race_time_hours_" + i).style.border = "none";
            document.getElementById("race_time_minutes_" + i).style.border = "none";
            document.getElementById("race_time_seconds_" + i).style.border = "none";
            
                // if a division has been selected, check a runner and time have also been selected
                if(document.getElementById("ddl1_runner_" + i).selectedIndex > 0) {
                    // if a runner hasn't been selected, highlight the field red
                    if(document.getElementById("ddl2_runner_" + i).selectedIndex == 0) {
                        varErr = 1;
                        document.getElementById("ddl2_runner_" + i).style.border = "3px solid red";
                    }
                    if(document.getElementById("race_time_hours_" + i).selectedIndex == 0) {
                        varErr = 1;
                        document.getElementById("race_time_hours_" + i).style.border = "3px solid red";
                    }
                    if(document.getElementById("race_time_minutes_" + i).selectedIndex == 0) {
                        varErr = 1;
                        document.getElementById("race_time_minutes_" + i).style.border = "3px solid red";
                    }
                    if(document.getElementById("race_time_seconds_" + i).selectedIndex == 0) {
                        varErr = 1;
                        document.getElementById("race_time_seconds_" + i).style.border = "3px solid red";
                    }
                }
            }
        if(varErr>0) {
            document.getElementById("errorBox").value = "Errors found! Please fix the highlighted box(es) before you submit the changes";
            varErr = 0;
        }
        else {
            document.getElementById("formRaceTimes").action = "formPost.php";
            document.getElementById("formRaceTimes").submit();
        }
    }
</script>
		<div class = "txt">
			<b>Hint:</b> If you can't see the runner you're looking for, check the member details page
		</div>
        <br><br>Select the race you're entering times for:<br>
        <form action="" method="post" name="formRaceTimes" id="formRaceTimes">
            <select id="champSelect" name="champSelect" onchange="configureDDL2(this, document.getElementById('raceSelect1'), document.getElementById('raceSelect2'))">
                <option value="">Pick Championship</option>
                <option value="open">Open Championship</option>
                <option value="short">Short Championship</option>
                <option value="MT">Multi-Terrain Challenge</option>
            </select>
            <select id="raceSelect1" name="raceSelect1" onchange="configureDDL3(document.getElementById('champSelect'), this, document.getElementById('raceSelect2'))">
            </select>
            <select id="raceSelect2" name="raceSelect2">
            </select>
        <br><br>Enter the details of each runner you're entering a time for:<br>
        <?php
            $ddl_runner_count=1;
            do {
                    echo "            <select id=\"ddl1_runner_$ddl_runner_count\" name=\"ddl1_runner_$ddl_runner_count\" onchange=\"configureDDL_runners(this, document.getElementById('ddl2_runner_$ddl_runner_count'))\">\n" .
                        "                <option value=\"\">Pick Runner Division</option>\n" .
                        "                <option value=\"1\">Division 1</option>\n" . 
                        "                <option value=\"2\">Division 2</option>\n" .
                        "                <option value=\"3\">Division 3</option>\n" .
                        "                <option value=\"4\">Division 4</option>\n" . 
                        "                <option value=\"5\">Division 5</option>\n" .
                        "                <option value=\"6\">Division 6</option>\n" .
                        "             </select>\n" .
                        "             <select id=\"ddl2_runner_$ddl_runner_count\" name=\"ddl2_runner_$ddl_runner_count\">\n" .
                        "             </select>\n";


                    echo "            Time:\n" .
                        "            <select id=\"race_time_hours_$ddl_runner_count\" name=\"race_time_hours_$ddl_runner_count\">\n" .
                        "                <option value=\"\">HH</option>\n";
                    $hourCounter=0;
                    do {
                        echo "<option value=\"" . $hourCounter . "\">$hourCounter</option>\n";
                        $hourCounter++;
                    } while ($hourCounter<8);
                    echo "            </select>\n" .
                        "            :\n" .
                        "            <select id=\"race_time_minutes_$ddl_runner_count\" name=\"race_time_minutes_$ddl_runner_count\">\n" .
                        "                <option value=\"\">MM</option>\n";
                    $minuteCounter=0;
                    do {
                        echo "<option value=\"" . $minuteCounter . "\">$minuteCounter</option>\n";
                        $minuteCounter++;
                    } while ($minuteCounter<60);
                    echo "            </select>\n" .
                        "            :\n" .
                        "            <select id=\"race_time_seconds_$ddl_runner_count\" name=\"race_time_seconds_$ddl_runner_count\">\n" .
                        "                <option value=\"\">SS</option>\n";
                    $secondCounter=0;
                    do {
                        echo "<option value=\"" . $secondCounter . "\">$secondCounter</option>\n";
                        $secondCounter++;
                    } while ($secondCounter<60);
                    echo "            </select>\n";
                    echo "            <input type=\"button\" id=\"btnClearOptions_$ddl_runner_count\" value=\"clear entries\" onClick=\"clearDDLentries($ddl_runner_count)\"><br>\n";
                    $ddl_runner_count++;
            } while ($ddl_runner_count<11);

        ?>
        <input type="hidden" name="fieldCount" id="fieldCount" value="<?php echo ($ddl_runner_count-1); ?>">
        <input type="hidden" name="parentPage" id="parentPage" value="addRaceTime">
        </form>
        <br>
<?php
        
} // end of $login_session user check
?>
		<div class = "floating-content">
			<form id = "admin-actions" method = "post" action = "updateMemberDetails.php">			
				<table>
					<tr>
						<td span = "2" class = "txt"><b><?php echo $_SESSION["name"]; ?></b></td>
					</tr>
					<tr>
						<td span = "2" class = "txt">Role: <b><?php echo $_SESSION["role"]; ?></b></td>
					</tr>
					<tr>
						<td span = "2" class = "txt"><br><b>Actions</b></td>
					</tr>
					<tr>
						<td><button type = "submit" formaction = "adminLanding.php">Admin home</button></td>
						<td><button type = "submit" formaction = "logout.php">Logout</button></td>
					</tr>
					<tr>
						<td span = "2"><button type = "submit" formaction = "updateMemberDetails.php">Member details</button></td>
					</tr>
					<tr>
						<td span = "2"><input type="button" onclick="validateForm(<?php echo ($ddl_runner_count - 1); ?>)" value="Submit" /></td>
					</tr>
					<tr>
						<td span = "2"><textarea id = "errorBox" name = "errorBox" rows="10", cols="20"></textarea></td>
					</tr>
				</table>
			</form>
		</div>
    </body>
</html>