<?php
	session_start();
    include('secure/authenticate.php');
	$RaceYear = "2024";
?>
<html lang="en">
<head>
  <title>Cumberland AC Masters Distance Admin, <?php echo $RaceYear; ?></title>
  <meta name="description" content="Cumberland AC Masters Distance Admin - Add New Distance">
  <meta name="author" content="Pixel District">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/styles.css<?php echo "?date=" . date("Y-m-d_H-i"); ?>">

  <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
<script type="text/javascript">
	// Javascript to validate against pre-loaded list of existing masters distances
	var js_existing_master_distances = new Array();

<?php
if($_SESSION["loggedin"]=='')
{
    // if the user isn't logged in, don't load the page content
}
else if (str_contains($_SESSION['name'], 'my.pyne@gmail.com'))
{
	//load all open champ races from the current year into an array
    $sql_existing_masters_distances = "SELECT DISTINCT WMADistance FROM tblwma ORDER BY WMADistance ASC";

    $result = mysqli_query($conn,$sql_existing_masters_distances);

    if (mysqli_num_rows($result) > 0)
    {
        // output data of each row
        while($row = $result->fetch_assoc())
		{
			echo "\n\tjs_existing_master_distances.push(Number(" . $row["WMADistance"] . "));";
		}
	}

?>

	
function validateForm() {
	var proposed_distance = Number(document.getElementById('ten').value + document.getElementById('unit').value + '.' + document.getElementById('decimal_1').value + document.getElementById('decimal_2').value + document.getElementById('decimal_3').value);
	
	const index_of_proposed_distance = js_existing_master_distances.indexOf(proposed_distance);

	if (index_of_proposed_distance > -1) {
		document.getElementById('feedback').value = 'An entry for ' + proposed_distance + 'km already exists';
		document.getElementById('btnGetRefs').disabled = true;
	} else {
		if (proposed_distance == 0) {
			document.getElementById('feedback').value = 'Can\'t create a new entry for ' + proposed_distance + 'km';
			document.getElementById('btnGetRefs').disabled = true;
		} else {
			document.getElementById('feedback').value = 'There isn\'t an entry for ' + proposed_distance + 'km yet';
			document.getElementById('btnGetRefs').disabled = false;
		}
	}
}
</script>
</head>
    <body>
        <br><br>Enter the new Masters race distance (in km):<br>
        <form action="" method="post" name="formRaceTimes" id="formRaceTimes">
		<select name="ten" id="ten" onclick="document.getElementById('feedback').value = ''; document.getElementById('btnGetRefs').disabled = true; validateForm();">
			<option value="0">0</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
		</select>
		<select name="unit" id="unit" onclick="document.getElementById('feedback').value = ''; document.getElementById('btnGetRefs').disabled = true; validateForm();">
			<option value="0">0</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
		</select>
		.
		<select name="decimal_1" id="decimal_1" onclick="document.getElementById('feedback').value = ''; document.getElementById('btnGetRefs').disabled = true; validateForm();">
			<option value="0">0</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
		</select>
		<select name="decimal_2" id="decimal_2" onclick="document.getElementById('feedback').value = ''; document.getElementById('btnGetRefs').disabled = true; validateForm();">
			<option value="0">0</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
		</select>
		<select name="decimal_3" id="decimal_3" onclick="document.getElementById('feedback').value = ''; document.getElementById('btnGetRefs').disabled = true; validateForm();">
			<option value="0">0</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
		</select>
		<br />
		<input type="text" disabled name="feedback" id="feedback" size="48"/> 
		<br />
		<table>
			<tr>
				<td>Nearest lower reference</td>
				<td>Nearest upper reference</td>
				<td>Difference</td>
			</tr>
			<tr>
				<td><input type="text"  name="nearest_lower_reference" id="nearest_lower_reference" size="12"/></td>
				<td><input type="text"  name="nearest_upper_reference" id="nearest_upper_reference" size="12"/></td>
				<td><input type="text"  name="reference_difference" id="reference_difference" size="12" /></td>
			</tr>
		</table>
		<br />
        <input type="button" disabled value="Get lower and upper reference points" name="btnGetRefs" id="btnGetRefs"/>
        <input type="hidden" name="parentPage" id="parentPage" value="addCustomMastersDistance">
        </form>
        <br>
<?php
        
} // end of $login_session user check

?>
 <div class = "container" > 
    <h3><u>Masters distances already available</u></h3>
    <p><strong>Click on 'show me' button to display</strong></p> 
    
    <div id="records"></div> 
    
    <p>
        <input type="button" id = "getmastersdistances" value = "show me" />
    </p>
  
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> 
<script type="text/javascript"> 
	
	// getmastersdistances on click function
	$(function() {
	    $("#getmastersdistances").on('click', function() {

	        $.ajax({
	            method: "GET",
	            url: "getrecords_ajax.php",
	        }).done(function(data) {
	            var result = $.parseJSON(data);
	            var string = '<table width="20%"><tr> <th>Masters distances already available</th><tr>';

	            $.each(result, function(key, value) {
	                string += "<tr> <td>" + (value['WMADistance'] * 1).toFixed(3) + 'km (' + (value['WMADistance'] / 1.609).toFixed(1) + ' mi)' + "</tr>";
	            });

	            string += '</table>';
	            $('#records').html(string);
	        });
	    });
	});
				
	// btnsubmit on click function
	$(function() {
	    $("#btnGetRefs").on('click', function() {

	        var proposed_distance = Number(document.getElementById('ten').value + document.getElementById('unit').value + '.' + document.getElementById('decimal_1').value + document.getElementById('decimal_2').value + document.getElementById('decimal_3').value);


	        // ajax to get next lower reference point
	        $.ajax({
	            method: "POST",
	            data: {
	                "user_proposed_distance": proposed_distance
	            },
	            url: "get_next_lower_ajax.php",
	        }).done(function(data) {
	            var result = $.parseJSON(data);
	            var lower_result = '';

	            $.each(result, function(key, value) {
	                lower_result += (value['WMADistance'] * 1).toFixed(3);
	            });

	            document.getElementById("nearest_lower_reference").value = lower_result;
	        });

	        // ajax to get next upper reference point
	        $.ajax({
	            method: "POST",
	            data: {
	                "user_proposed_distance": proposed_distance
	            },
	            url: "get_next_upper_ajax.php",
	        }).done(function(data) {
	            var result = $.parseJSON(data);
	            var upper_result = '';

	            $.each(result, function(key, value) {
	                upper_result += (value['WMADistance'] * 1).toFixed(3);
	            });

	            document.getElementById("nearest_upper_reference").value = upper_result;
				document.getElementById("reference_difference").value = "click to calculate";
	        });
		});
	});

	$(function() {
	    $("#reference_difference").on('click', function() {
			document.getElementById("reference_difference").value = (document.getElementById("nearest_upper_reference").value - document.getElementById("nearest_lower_reference").value).toFixed(3);
		});
	});
	
// race condition was causing some weird difference calculations using values that hadn't refreshed yet or from prevous execution of the script
// - possible answer here: https://stackoverflow.com/questions/46748991/input-field-not-refresh-after-receiving-value

</script>
        <table>
			<tr>
				<td><a href="adminLanding.php">Back</a></td>
				<td><a href="logout.php">Logout</a></td>
			</tr>
            <span id="errorBox"></span>
		</table>
    </body>
</html>