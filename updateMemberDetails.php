<?php
	session_start();
    include('secure/authenticate.php');
	$RaceYear = "2024";
?>
<html lang="en">
<head>
  <title>Cumberland AC Member Details Admin, <?php echo $RaceYear; ?></title>
  <meta name="description" content="Cumberland AC Race Time Admin - Add Race Time">
  <meta name="author" content="Pixel District">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="css/styles.css<?php echo "?date=" . date("Y-m-d_H-i"); ?>">

  <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
  
</head>
	<body>
		<div class = "main-page-content">
			<form id = "member-details">
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data using $_POST
	$newData = $_POST['changes-list-hidden'];
	
	$columns = explode(', ', $newData);
	
	foreach ($columns as $column) {
		
		$MIDpattern = '/\d+/';
		if (preg_match($MIDpattern, $column, $matches)) {
			$MID = $matches[0];
		}

		$MDivpattern = '/\(\d+\)/';
		if (preg_match($MDivpattern, $column, $matches)) {
			$MDiv = $matches[0];
			$MDiv = trim($MDiv,'()');
		}
		
		// dual purpose; covers first and last name
		$MNamepattern = '/\([\p{L}\'-]+\)/';
		if (preg_match($MNamepattern, $column, $matches)) {
			$MName = $matches[0];
			$MName = trim($MName,'()');
		}
		
		// date pattern
		$Dpattern = '/(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}/';
		if (preg_match($Dpattern, $column, $matches)) {
			$MDoB = $matches[0];
		}
		
		$MStatuspattern = '/\([A-Za-z]+\)/';
		if (preg_match($MStatuspattern, $column, $matches)) {
			$MStatus = $matches[0];
			$MStatus = trim($MStatus,'()');
		}		
		
		switch (substr($column,0,3)) {
			case "div":
				echo "User: " . $MID . " | Division change to " . $MDiv . "<br>";
				break;
			case "mem":
				echo "User: " . $MID . " | Membership status change to " . $MStatus . "<br>";
				break;
			case "dat":
				echo "User: " . $MID . " | Date of birth change to " . $MDoB . "<br>";
				break;
			case "fir":
				echo "User: " . $MID . " | First name change to " . $MName . "<br>";
				break;
			case "sur":
				echo "User: " . $MID . " | Surname change to " . $MName . "<br>";
				break;
		}

	}
}

if($_SESSION["loggedin"]=='')
{
    // if the user isn't logged in, don't load the page
}
else if (str_contains($_SESSION['role'], 'site admin') || str_contains($_SESSION['role'], 'race admin') || str_contains($_SESSION['role'], 'membership admin'))
{
	echo '<div class = "txt">';
	echo '<br>';
	echo 'Hi <b>' . $_SESSION["name"] . '</b>,';
	echo '<br>';
	echo '<br>';
	echo 'As a quick guide, members in the table below, who have cells highlighted in red, cannot be selected when adding race times';
	echo '<br>';
	echo '<br>';
	echo 'Depending on your user permissions, you might be able to correct the red cells yourself. If not, please let the site admin know (my.pyne@gmail.com)';
	echo '<br>';
	echo '<br>';
	echo '</div>';
	
	$sqlGetAllRunners = "SELECT RunnerID, RunnerFullSocialMember, RunnerDiv, RunnerFirstName, RunnerSurname , RunnerDOB, RunnerSex FROM tblrunners order by RunnerSurname, RunnerFirstName";
	
    $result = mysqli_query($conn,$sqlGetAllRunners);

    if (mysqli_num_rows($result) > 0)
    {
		echo "<table>";
		echo "<tr>";
		echo "<th>First name</th>";
		echo "<th>Surname</th>";
		echo "<th>DoB</th>";
		echo "<th>Full / Social / Non-member</th>";
		echo "<th>Division</th>";
		echo "</tr>";
        // output data of each row
        while($row = $result->fetch_assoc())
        {
			$originalDoB = $row["RunnerDOB"];
			$reformatDoB = date("d/m/Y", strtotime($originalDoB));
			
			echo '<tr>';
			echo '<td>';
				echo '<input type = "text"';
				switch ($_SESSION['role']) {
					case 'site admin':
					  break;
					default:
					  echo ' disabled ';
				}			
				echo 'value = "' . $row["RunnerFirstName"] . '" id = "firstnametext' . $row["RunnerID"] . '" />';
			echo '</td>';			
			echo '<td>';
				echo '<input type = "text"';
				switch ($_SESSION['role']) {
					case 'site admin':
					  break;
					default:
					  echo ' disabled ';
				}				
				echo 'value = "' . $row["RunnerSurname"] . '" id = "surnametext' . $row["RunnerID"] . '" />';
			echo '</td>';			
			echo '<td>';
				echo '<input type = "text"';
				switch ($_SESSION['role']) {
					case 'site admin':
					  break;
					default:
					  echo ' disabled ';
				}
				echo 'value = "' . $reformatDoB . '" id = "datepicker' . $row["RunnerID"] . '"';
				switch ($reformatDoB) {
					case '30/11/-0001':
					  echo ' class = "txt-highlight-red"';
					  break;
					default:
					  break;
				}
				echo '/>';
			echo '</td>';
			echo '<td>';
				echo '<select id="memberstatuspicker' . $row["RunnerID"] . '\"';
				switch ($_SESSION['role']) {
					case 'site admin':
					case 'membership admin':
					  break;
					default:
					  echo ' disabled ';
				}
				switch ($row["RunnerFullSocialMember"]) {
					case '':
					  echo ' class = "txt-highlight-red"';
					  break;
					default:
					  break;
				}					
				echo '>';
					echo '<option value="Full"';			
					if ($row["RunnerFullSocialMember"] == "Full") {
						echo ' selected';
					}
					echo '>Full</option>';					
					echo '<option value="Social"';			
					if ($row["RunnerFullSocialMember"] == "Social") {
						echo ' selected';
					}
					echo '>Social</option>';
					echo '<option value="Non-"';			
					if ($row["RunnerFullSocialMember"] == "") {
						echo ' selected';
					}			
					echo ' class = "txt-highlight-red">Non-</option>';				
				echo '</select>';
			echo '</td>';
			echo '<td>';
				echo '<select id="divpicker' . $row["RunnerID"] . '"';
				switch ($_SESSION['role']) {
					case 'site admin':
					case 'membership admin':
					  break;
					default:
					  echo ' disabled ';
				}
				switch ($row["RunnerDiv"]) {
					case 0:
					  echo ' class = "txt-highlight-red"';
					  break;
					default:
					  break;
				}				
				echo '>';
					for ($i = 0; $i <= 6; $i++) {
						echo '<option value="' . $i . '"';
						if ($i == $row["RunnerDiv"]) {
							echo ' selected';
						}
						echo '>' . $i . '</option>';
					}
				echo '</select>';
			echo '</td>';
			echo "</tr>\n";
		}
		echo "</table>";
	}
}
?>
			</form>
		</div>
		<div class = "floating-content">
			<form id = "admin-actions" method = "post" action = "updateMemberDetails.php">
				<table>
					<tr>
						<td span = "2" class = "txt"><b>Actions</b></td>
					</tr>
					<tr>
						<td><button type = "submit" formaction = "adminLanding.php">Admin home</button></td>
						<td><button type = "submit" formaction = "logout.php">Logout</button></td>
					</tr>
					<tr>
						<td span = "2"><span id="errorBox"></span></td>
					</tr>
					<tr>
						<td span = "2" class = "txt">Fields changed:</td>
					</tr>
					<tr>
						<td span = "2"><textarea disabled rows = "10", cols = "20" class = "changes-list" id = "changes-list"></textarea></td>
					</tr>
					<tr>
						<td span = "2"><input type = "submit" value = "Save changes" /></td>
					</tr>
				</table>
				<input type = "hidden" value = "<?php echo $_SESSION["name"]; ?>" class = "admin-name" id = "admin-name">
				<input type = "hidden" id = "changes-list-hidden" name = "changes-list-hidden">
			</form>
		</div>
		
		<script>
			$('#member-details :input').on('change', function() {
				var changedField = $(this).attr('id');
				var changedValue = $(this).val();
				var changesList = $('#changes-list').val();
				var changesConcatenated = changedField + '(' + changedValue + '), ' + changesList;
				
				$('#changes-list').val(changesConcatenated);
				$('#changes-list-hidden').val(changesConcatenated);
			});	
		</script>
    </body>
</html>