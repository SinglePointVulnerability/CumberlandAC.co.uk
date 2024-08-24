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
    <script>
		
        function loadImage() {
            var selectedItem = document.getElementById('RunLeaderNamesList').value;
			const delimiter = '_';
			// Split the string into an array
			const resultArray = selectedItem.split(delimiter);
			
			var RunLeaderPhotoLink = resultArray[2];
            document.getElementById('imageContainer').innerHTML = '<img src="' + RunLeaderPhotoLink + '" alt="No Photo" style="width:200px;height:250px;">';
			document.getElementById('imageSelectContainer').innerHTML = '<input type="file" name="runleader_image" accept="image/*"><br>' +
				'<br><input type="hidden" name="form_url" value="updateTrainingRunLeader_UpdateRunLeader.php">' +
				'<br>Delete my photo <input type="checkbox" id="delete_photo" name="delete_photo" value="1">' +
				'<br><button type="submit">Submit</button>';
        };
    </script>
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	include('updateTrainingRunLeader_process_form.php');
} else {
?>
	<form action="updateTrainingRunLeader_UpdateRunLeader.php" method="post" enctype="multipart/form-data">
	<h1>Cumberland AC Training and Run Leader Admin | Upload training map</h1>
    <h2>Search Run Leaders Database</h2>

	<div id="imageSelectContainer">
        <!-- Image selector and submit button will be displayed here -->
		<input type="file" name="trainingmap_image" accept="image/*"><br>
		<br><input type="hidden" name="form_url" value="updateTrainingRunLeader_NewTrainingMap.php">
		<!-- <br>Delete my photo <input type="checkbox" id="delete_photo" name="delete_photo" value="1"> -->
		<br><button type="submit">Submit</button>
	</div>
	</form>
<?php
	}
}

echo "<br><br><a href = 'updateTrainingRunLeader_Navigation.php'>Back to navigation screen</a>";
?>
		</div>
		

    </body>
</html>