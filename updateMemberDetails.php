<?php
	if(session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$callingPage = "updateMemberDetails.php";
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

<?php
$sqlMulti = '';
	
function formPostSwitch($data) {
	$MIDpattern = '/\d+/';
	$MemberID = RegExScraper($data,$MIDpattern);
	$sqlPart1 = 'UPDATE tblRunners SET [sql part 2], RecordUpdatedBy = "' . $_SESSION["name"] . '" WHERE RunnerID = ' . $MemberID . ';';
	$sqlPart2 = '';
	global $sqlMulti;
	
	switch (substr($data,0,3)) {
		case "div":
			$MDivpattern = '/\(\d+\)/';	
			$newDiv = RegExScraper($data,$MDivpattern);			
			$sqlPart2 = 'RunnerDiv = ' . trim($newDiv,'()');
			break;
		case "mem":
			$MStatuspattern = '/\([A-Za-z]+\)/';
			$newStatus = RegExScraper($data,$MStatuspattern);
			$sqlPart2 = 'RunnerFullSocialMember = "' . trim($newStatus,'()') . '"';
			break;
		case "dat":
			$Dpattern = '/(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}/';
			$newDate = RegExValidator($data,$Dpattern);
			if (substr($newDate,0,1) == ":") {
				echo "User: " . $MemberID . " | Date of birth change failed. Check format is dd/mm/yyyy. Date provided: " . substr($newDate,2,-2) . "<br>";
			} else {
				$ymd = DateTime::createFromFormat('d/m/Y', $newDate)->format('Y-m-d');
				$sqlPart2 = 'RunnerDOB = "' . $ymd . '"';
			}
			break;
		case "fir":
			$MNamepattern = '/\([\p{L}\'-]+\)/';
			$newName = RegExValidator($data,$MNamepattern);
			if (substr($newName,0,1) == ":") {
				echo "User: " . $MemberID . " | First name change failed. Check format only contains alphabet characters and the symbols ' or - | Name provided: " . substr($newName,2,-2) . "<br>";
			} else {
				$sqlPart2 = 'RunnerFirstName = "' . trim($newName, '()') . '"';				
			}			
			break;
		case "sur":
			$MNamepattern = '/\([\p{L}\'-]+\)/';
			$newName = RegExValidator($data,$MNamepattern);
			if (substr($newName,0,1) == ":") {
				echo "User: " . $MemberID . " | Surname change failed. Check format only contains alphabet characters and the symbols ' or - | Name provided: " . substr($newName,2,-2) . "<br>";
			} else {
				$sqlPart2 = 'RunnerSurname = "' . trim($newName, '()') . '"';	
			}			
			break;
	}
	
	if ($sqlPart2 <> '') {
		$sqlLine = str_replace('[sql part 2]',$sqlPart2,$sqlPart1);
		$sqlMulti .= $sqlLine;
	}
}
function RegExScraper($input, $pattern) {
	$output = '';
	if (preg_match($pattern, $input, $matches)) {
		$output = $matches[0];
	}
	return $output;
}
function RegExValidator($input, $pattern) {
	$output = '';
	if (preg_match($pattern, $input, $matches)) {
		$output = $matches[0];
	} else {
		$pattern = '/\([^\s]+\)/';
		if (preg_match($pattern, $input, $failed_matches)) {
			$output = $failed_matches[0];
		}
		$output = ":" . $failed_matches[0] . ":";
	}
	return $output;
}

function exMultiQueries() {
	global $sqlMulti;
	global $conn;

	// Execute multiple queries
	if ($conn->multi_query($sqlMulti)) {
		do {
			// Store and process the result set for the current query
			if ($result = $conn->store_result()) {
				while ($row = $result->fetch_row()) {
					// Process the result data
					print_r($row);
				}
				$result->free();
			}
			// Move to the next query in the multiple-query execution
		} while ($conn->next_result());
	} else {
		echo "Error executing multiple queries: " . $conn->error;
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST" and $_POST['changes-list-hidden'] <> '') {
    // Retrieve form data using $_POST
	$newData = $_POST['changes-list-hidden'];
	$columns = explode(', ', $newData);
	
	foreach ($columns as $column) {		
		formPostSwitch($column);
	}
	
	exMultiQueries();
	
	// sql executes, the page doesn't load any further; on refresh the SQL changes appear to have applied, but on going back to the admin menu,
	//    then revisiting the updateMemberDetails page, the SQL changes are undone
}

if($_SESSION["loggedin"]=='')
{
    // if the user isn't logged in, don't load the page
}
else if (str_contains($_SESSION['role'], 'site admin') || str_contains($_SESSION['role'], 'club stats') || str_contains($_SESSION['role'], 'race committee') || str_contains($_SESSION['role'], 'membership sec'))
{
	echo '<div class = "txt">';
	echo '<br>';
	echo 'Hi <b>' . $_SESSION["name"] . '</b>,';
	echo '<br>';
	echo '<br>';
	echo 'As a quick guide, members in the table below who have fields highlighted in red cannot be selected when adding race times';
	echo '<br>';
	echo '<br>';
	echo 'Depending on your user permissions, you might be able to correct some red fields yourself. If not, please let the site admin know (my.pyne@gmail.com)';
	echo '<br>';
	echo '<br>';
	echo '</div>';
	
	$sqlGetAllRunners = "SELECT RunnerID, RunnerFullSocialMember, RunnerDiv, RunnerFirstName, RunnerSurname , RunnerDOB, RunnerSex FROM tblRunners order by RunnerSurname, RunnerFirstName";
	
    $result = mysqli_query($conn,$sqlGetAllRunners);

    if (mysqli_num_rows($result) > 0)
    {
		echo 'Narrow down the list by starting to type the first or second name of the person you\'re looking for! (3 letters minimum)<br><br>';
		echo '<font class = "txt"><b>Type here:</b> </font><input type = "text" class = "txt" id = "filterInput" placeholder = "search names..."><br><br>';
		
		echo '<form id = "member-details">';
		echo '<table id = "membersTable">';
		echo '<thead>';
		echo "<tr>";
		echo "<th>First name</th>";
		echo "<th>Surname</th>";
		echo "<th>DoB</th>";
		echo "<th>Full / Social / Non-member</th>";
		echo "<th>Division</th>";
		echo "</tr>";
		echo '</thead>';
		echo '<tbody>';
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
					case 'membership sec':
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
					case 'membership sec':
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
					case 'race committee':
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
			echo '</tbody>';
		}
		echo "</table>";
	}
}

echo '</form>';

?>
		</div>
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
						<td span = "2"><span id="errorBox"></span></td>
					</tr>
					<tr>
						<td span = "2" class = "txt">Changes:</td>
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
				if($(this).attr('id') !== 'filterInput') {
					var changedField = $(this).attr('id');
					var changedValue = $(this).val();
					var changesList = $('#changes-list').val();
					var changesConcatenated = changedField + '(' + changedValue + '), ' + changesList;
					
					$('#changes-list').val(changesConcatenated);
					$('#changes-list-hidden').val(changesConcatenated);
				}
			});	
		</script>
		<script>			
			// handle filtering based on user input
			document.getElementById("filterInput").addEventListener("input",
				function() {
					var filterValue = this.value.toLowerCase();
					if(filterValue.length >= 3 || filterValue.length == 0) {
						var rows = document.getElementById("membersTable").querySelectorAll("tbody tr");
						
						rows.forEach(function(row) {
							var cells = row.getElementsByTagName("td");
							var display = false;
							
							for(var i = 0; i < cells.length; i++) {
								var cellText = cells[i].innerHTML.toLowerCase();
								
								
								if(cellText.indexOf(filterValue) > -1) {
									display = true;
									break;
								}
							}
							row.style.display = display ? "" : "none";
						});
					}
				});
	</script>		
    </body>
</html>