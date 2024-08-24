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


?>
	<h1>Cumberland AC Training and Run Leader Admin | Navigation</h1>

<div class="photo-grid-container">
<?php
	$folderPath = 'media/training'; // Update this path to your folder
	$images = glob($folderPath . '/RunLeader*.{jpg,jpeg,png,gif}', GLOB_BRACE);

	foreach($images as $image) {
		echo '<div class="photo-grid-item">';
		echo '<img src="' . $image . '" alt="Image">';
		echo '</div>';
	}
?>
</div>
	<h2>What do you want to do?</h2>
	<h3>Run leader admin</h3>
	<a href = "updateTrainingRunLeader_SetActiveRunLeader.php">Select who's run leading this week</a>
<!--	<a href = "updateTrainingRunLeader_NewRunLeader.php">Add a new run leader</a>
	<br><a href = "updateTrainingRunLeader_UpdateRunLeader.php">Update a run leader profile (add, change or remove a photo)</a>
	<br><a href = "updateTrainingRunLeader_RemoveRunLeader.php">Remove a run leader</a>
-->	<h3>Thursday Training Notes</h3>
<!--	<br><a href = "updateTrainingRunLeader_NewTrainingMap.php">Add a new training map (add, change or remove a map)</a> -->
	<br><a href = "updateTrainingRunLeader_UpdateTrainingInstructions.php">Enter location and training notes for this week's Thursday training session</a>
	<h3>Navigation</h3>
	<br><a href = "adminLanding.php">Back to Admin home</a>
	<br><a href = "logout.php">Logout</a>
<?php
}
	$conn->close();
?>
		</div>
		

    </body>
</html>