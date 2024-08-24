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
        function fetchNames() {
            var searchTerm = document.getElementById('searchBox').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'RunLeaders_fetch_names.php?search=' + searchTerm, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('RunLeaderNamesList').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        window.onload = function() {
            fetchNames(); // Load names on page load
        };
		
        function loadImage() {
            var selectedItem = document.getElementById('RunLeaderNamesList').value;
			const delimiter = '_';
			// Split the string into an array
			const resultArray = selectedItem.split(delimiter);
			
			var RunLeaderPhotoLink = resultArray[2];
            document.getElementById('imageContainer').innerHTML = '<img src="' + RunLeaderPhotoLink + '" alt="No Photo" style="width:200px;height:250px;">';
			document.getElementById('imageSelectContainer').innerHTML = '<input type="hidden" name="form_url" value="updateTrainingRunLeader_RemoveRunLeader.php">' +
				'<br>Remove this run leader <input type="checkbox" id="remove_run_leader" name="remove_run_leader" value="1">' +
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
	<form action="updateTrainingRunLeader_RemoveRunLeader.php" method="post" enctype="multipart/form-data">
	<h1>Cumberland AC Training and Run Leader Admin | Remove a run leader profile</h1>
    <h2>Search Run Leaders Database</h2>
     <input type="text" id="searchBox" onkeyup="fetchNames()" placeholder="Search...">
    <h2>Pick a name from the list</h2>
	<select id="RunLeaderNamesList" name = "RunLeaderNamesList" size="10" onclick="loadImage()">
        <!-- Options will be populated dynamically -->
    </select>
	<div id="imageContainer" >
        <!-- Image will be displayed here -->
	</div>
	<div id="imageSelectContainer">
        <!-- Image selector and submit button will be displayed here -->
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