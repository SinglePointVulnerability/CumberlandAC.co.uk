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

	// Check if an image file is uploaded
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['route_image'])) {
		$uploadDir = 'media/training/';
		$originalFileName = basename($_FILES['route_image']['name']);
		$fileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
		$allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

		// Validate file type
		if (!in_array($fileType, $allowedTypes)) {
			echo "Error: Only JPG, JPEG, PNG, and GIF files are allowed.";
			exit;
		}

		// Validate file size (max 5MB)
		if ($_FILES['route_image']['size'] > 5 * 1024 * 1024) {
			echo "Error: File size exceeds 5MB.";
			exit;
		}

		// Insert the original file name into the database
		$stmt = $conn->prepare("INSERT INTO tblTraining (RouteImageLink) VALUES (?)");
		$stmt->bind_param("s", $originalFileName);
		$stmt->execute();
		$lastId = $stmt->insert_id;
		$stmt->close();

		// Define the new file name with the auto-increment ID
		$newFileName = $uploadDir . 'Route'. $lastId . '.' . $fileType;

		// Check if upload directory exists, if not, create it
		if (!is_dir($uploadDir)) {
			mkdir($uploadDir, 0777, true);
		}

		// Move the uploaded file to the target directory with the new name
		if (move_uploaded_file($_FILES['route_image']['tmp_name'], $newFileName)) {
			// Update the database entry with the new file name
			$stmt = $conn->prepare("UPDATE tblTraining SET RouteImageLink = ? WHERE TrainingID = ?");
			$stmt->bind_param("si", $newFileName, $lastId);
			$stmt->execute();
			$stmt->close();

			echo "The file " . htmlspecialchars($originalFileName) . " has been uploaded successfully.";
		} else {
			echo "Error: There was an error uploading your route image.";
		}
	} else {
		echo "No route image was uploaded.";
	}


	// Check if an image file is uploaded
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['runleader_image'])) {
		$uploadDir = 'media/training/';
		$originalFileName = basename($_FILES['runleader_image']['name']);
		$fileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
		$allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

		// Validate file type
		if (!in_array($fileType, $allowedTypes)) {
			echo "Error: Only JPG, JPEG, PNG, and GIF files are allowed.";
			exit;
		}

		// Validate file size (max 5MB)
		if ($_FILES['runleader_image']['size'] > 5 * 1024 * 1024) {
			echo "Error: File size exceeds 5MB.";
			exit;
		}

		// Insert the original file name into the database
		$stmt = $conn->prepare("INSERT INTO tblRunLeader (RunLeaderPhotoLink, RunnerID) VALUES (?,1)");
		$stmt->bind_param("s", $originalFileName);
		$stmt->execute();
		$lastId = $stmt->insert_id;
		$stmt->close();

		// Define the new file name with the auto-increment ID
		$newFileName = $uploadDir . 'RunLeader'. $lastId . '.' . $fileType;

		// Check if upload directory exists, if not, create it
		if (!is_dir($uploadDir)) {
			mkdir($uploadDir, 0777, true);
		}

		// Move the uploaded file to the target directory with the new name
		if (move_uploaded_file($_FILES['runleader_image']['tmp_name'], $newFileName)) {
			// Update the database entry with the new file name
			$stmt = $conn->prepare("UPDATE tblRunLeader SET RunLeaderPhotoLink = ? WHERE RunLeaderID = ?");
			$stmt->bind_param("si", $newFileName, $lastId);
			$stmt->execute();
			$stmt->close();

			echo "The file " . htmlspecialchars($originalFileName) . " has been uploaded successfully.";
		} else {
			echo "Error: There was an error uploading your run leader image.";
		}
	} else {
		echo "No run leader image was uploaded.";
	}



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


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Reset all radio buttons to unselected
    $conn->query("UPDATE tblTraining SET RouteActive = 0");

    // Update selected radio button based on form submission
    if (isset($_POST['selected'])) {
        $selectedId = (int)$_POST['selected'];
        $stmt = $conn->prepare("UPDATE tblTraining SET RouteActive = 1 WHERE TrainingID = ?");
        $stmt->bind_param("i", $selectedId);
        $stmt->execute();
        $stmt->close();
        
        echo "Active training route updated successfully.";
    } else {
        echo "No selection made.";
    }
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
    <h2>Upload a screenshot of the route</h2>
    <form action="updateTrainingRunLeader.php" method="post" enctype="multipart/form-data">
        <input type="file" name="route_image" accept="image/*" required>
        <button type="submit">Upload Image</button>
    </form>

    <form action="updateTrainingRunLeader.php" method="post">
        <table>
            <tr>
				<th width = "200px">Route Map</th>
                <th>Route Description</th>
                <th>Route Active?</th>
            </tr>
            <?php
            if ($resultTrainingList->num_rows > 0) {
                while($row = $resultTrainingList->fetch_assoc()) {
                    echo "<tr>";
					echo "<td><img src='" . $row['RouteImageLink'] . "' style='width:40%;height:40%;'/></td>";
                    echo "<td width='250px'>" . $row['RouteDescription'] . "</td>";
                    echo "<td>" . $row['RouteActive'] . "</td>";
                    echo "<td><input type='radio' name='selected' value='" . $row['TrainingID'] . "'" . ($row['RouteActive']==1 ? " checked" : "") . "></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No records found</td></tr>";
            }
            ?>
        </table>
        <button type="submit">Submit</button>
    </form>
	
	
    <h2>Upload a run leader photo</h2>
    <form action="updateTrainingRunLeader.php" method="post" enctype="multipart/form-data">
        <input type="file" name="runleader_image" accept="image/*" required>
        <button type="submit">Upload Image</button>
    </form>

    <form action="updateTrainingRunLeader.php" method="post">
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

<?php
}
	$conn->close();
?>
		</div>
		

    </body>
</html>