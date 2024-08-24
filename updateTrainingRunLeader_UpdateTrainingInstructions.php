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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	include('updateTrainingRunLeader_process_form.php');
} else {
?>
	<h1>Cumberland AC Training and Run Leader Admin | Update training instructions</h1>
	<form id="training-instructions-form" action="updateTrainingRunLeader_UpdateTrainingInstructions.php" method="post" enctype="multipart/form-data">
		<h2>Write a route or training session description in the box below</h2>
		<textarea class="training-instructions-textarea" id="trainingInstructions" placeholder="Enter route description or training instructions..."></textarea>
		<button id="btnSaveTrainingInstructions">Save</button>
		<input type="hidden" class="training-instructions-sanitised" name = "sanitisedTrainingInstructions" id="sanitisedTrainingInstructions"></div>
		<input type="hidden" name="form_url" value="updateTrainingRunLeader_UpdateTrainingInstructions.php">
	</form>
    <script>
        // Function to sanitise input to protect against SQL injection
        function sanitiseInput(str) {
            return str.replace(/'/g, "''")
                      .replace(/"/g, '""')
                      .replace(/\\/g, '\\\\')
                      .replace(/;/g, '\\;')
                      .replace(/--/g, '\\--')
					  .replace(/[\r\n]+/g, '<br>');
					  

			 // When sanitisation's complete, submit the form
			document.getElementById('training-instructions-form').submit();
        }

        // Event listener for the sanitise button
        document.getElementById('btnSaveTrainingInstructions').addEventListener('click', function() {
            const trainingInstructions = document.getElementById('trainingInstructions').value;
            const sanitisedTrainingInstructions = sanitiseInput(trainingInstructions);
            document.getElementById('sanitisedTrainingInstructions').value = sanitisedTrainingInstructions;
        });
		
		document.getElementById('training-instructions-form').addEventListener('submit', function(event) {
			// Prevent the default form submission
			event.preventDefault();

			 // If validation passes, submit the form
			this.submit();
		});
    </script>
<?php
	}
}

echo "<br><a href = 'updateTrainingRunLeader_Navigation.php'>Back to Run Leader home</a>" .
     "<br><a href = 'adminLanding.php'>Back to Admin home</a>" .
	 "<br><a href = 'training.php'>Back to Training page</a>" .
	 "<br><a href = 'logout.php'>Logout</a>";
?>
		</div>
		

    </body>
</html>