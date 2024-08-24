<?php
	session_start();
    include('secure/authenticate.php');
	$RaceYear = "2024";
?>
<html lang="en">
<head>
  <title>Cumberland AC Training and Run Leader Admin, <?php echo $RaceYear; ?></title>
  <meta name="description" content="Cumberland AC Training Admin">
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
if($_SESSION["loggedin"]=='')
{
    // if the user isn't logged in, don't load the page
}
else if (str_contains($_SESSION['role'], 'site admin') || str_contains($_SESSION['role'], 'club stats') || str_contains($_SESSION['role'], 'race committee') || str_contains($_SESSION['role'], 'membership sec'))
{


	// Handle form submission
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// Reset all checkboxes to unchecked
		$conn->query("UPDATE tblRunLeader SET LeaderActive = 0");

		// Update checkboxes based on form submission
		if (isset($_POST['checked'])) {
			foreach ($_POST['checked'] as $id => $value) {
				$id = (int)$id;
				$conn->query("UPDATE tblRunLeader SET LeaderActive = 1 WHERE RunnerID = $id");
			}
		}

		echo "Active run leaders updated successfully.";
	} else {
		echo "No data submitted.";
	}

// Fetch run leader data from tblRunLeader
$sqlRunLeaderList = "SELECT RunLeaderPhotoLink, RunnerFirstName, RunnerSurname, tblRunners.RunnerID, LeaderActive FROM tblRunLeader LEFT JOIN tblRunners ON tblRunLeader.RunnerID = tblRunners.RunnerID";
$resultRunLeaderList = $conn->query($sqlRunLeaderList);

// Fetch training route data from tblTraining
$sqlTrainingList = "SELECT TrainingID, RouteDescription, RouteActive, RouteImageLink FROM tblTraining ORDER BY TrainingID DESC";
$resultTrainingList = $conn->query($sqlTrainingList);

?>
	<h1>Cumberland AC Training and Run Leader Admin</h1>

    <h2>Select who the active run leaders are for this week</h2>
    <form action="updateTrainingRunLeader_SetActiveRunLeader.php" method="post">
        <table>
            <tr>
                <th width = "200px">Photo</th>
				<th>Run Leader</th>
                <th>Active this week?</th>
            </tr>
            <?php
            if ($resultRunLeaderList->num_rows > 0) {
                while($row = $resultRunLeaderList->fetch_assoc()) {
                    echo "<tr>";
					echo "<td width='200px'><img src='" . $row['RunLeaderPhotoLink'] . "' style='width:25%;height:25%;'/></td>";
                    echo "<td>" . $row['RunnerFirstName'];
                    echo " " . $row['RunnerSurname'] . "</td>";
                    echo "<td><input type='checkbox' name='checked[" . $row['RunnerID'] . "]' value='1'" . ($row['LeaderActive']==1 ? " checked" : "") . "></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No records found</td></tr>";
            }
            ?>
        </table>
        <button type="submit">Submit</button>
    </form>

	<h3>Navigation</h3>
	<br><a href = "updateTrainingRunLeader_Navigation.php">Back to Run Leader home</a>
	<br><a href = "adminLanding.php">Back to Admin home</a>
	<br><a href = "logout.php">Logout</a>
	
<?php
}
	$conn->close();
?>
		</div>
		

    </body>
</html>