<?php
	$uploadDir = 'media/training/';
	if(isset($_FILES['runleader_image'])) {
		$originalFileName = basename($_FILES['runleader_image']['name']);
	} elseif(isset($_FILES['trainingmap_image'])) {
		$originalFileName = basename($_FILES['trainingmap_image']['name']);		
	}
	
	$fileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
	$allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

	if(isset($_FILES['runleader_image']) && $originalFileName <> "") {

		// Validate file type
		if (!in_array($fileType, $allowedTypes)) {
			echo "Error: Only JPG, JPEG, PNG, and GIF files are allowed.";
			echo $originalFileName;
			exit;
		}

		// Validate file size (max 5MB)
		if ($_FILES['runleader_image']['size'] > 5 * 1024 * 1024) {
			echo "Error: File size exceeds 5MB.";
			exit;
		}
		global $conn;
		// Uddate the database with the original file name
		$stmt = $conn->prepare("UPDATE tblRunLeader SET RunLeaderPhotoLink = ? WHERE RunLeaderID = ?");
		$stmt->bind_param("si", $originalFileName, $GLOBALS['runLeaderID']);
		$stmt->execute();
		$stmt->close();

		// Define the new file name with the auto-increment ID
		$newFileName = $uploadDir . 'RunLeader'. $GLOBALS['runLeaderID'] . '.' . $fileType;
		
		// Move the uploaded file to the target directory with the new name
		if (move_uploaded_file($_FILES['runleader_image']['tmp_name'], $newFileName)) {
			// Update the database entry with the new file name
			$stmt = $conn->prepare("UPDATE tblRunLeader SET RunLeaderPhotoLink = ? WHERE RunLeaderID = ?");
			$stmt->bind_param("si", $newFileName, $GLOBALS['runLeaderID']);
			$stmt->execute();
			$stmt->close();

			echo "<br><img src='" . $newFileName . "' alt='No Photo' style='width:200px;height:250px;'>";
			echo "<br>Photo " . htmlspecialchars($originalFileName) . " for " . $GLOBALS['firstname'] . " " . $GLOBALS['surname'] . " has been uploaded successfully.";
		} else {
			echo "Error: There was an error uploading your run leader photo.";
		}
	}
	// check for 'delete photo' last
	if ($GLOBALS['action'] == "delete photo") {
		global $conn;
		
		//case statement to build photo delete variables
		switch($GLOBALS['origin']) {
			case "updateTrainingRunLeader_UpdateRunLeader.php":
				$GLOBALS['sql_read'] = "SELECT RunLeaderPhotoLink FROM tblRunLeader WHERE RunLeaderID = ";
				$GLOBALS['sql_update'] = "UPDATE tblRunLeader SET RunLeaderPhotoLink = 'media/training/Silhouette.jpg' WHERE RunLeaderID = ";
				break;
			default:
				break;
		}		
		
		$result_existingPhotoLink = $conn->query($GLOBALS['sql_read'] . $GLOBALS['runLeaderID']);
		if ($result_existingPhotoLink->num_rows > 0) {
			while($row = $result_existingPhotoLink->fetch_assoc()) {
				$existingPhotoLink = $row['RunLeaderPhotoLink'];
			}
		}
		
		$conn->query($GLOBALS['sql_update'] . $GLOBALS['runLeaderID']);
		
		// Check if the file exists and delete it
		if (file_exists($existingPhotoLink)) {
			if (unlink($existingPhotoLink)) {
				echo "<br>Photo for " . $GLOBALS['firstname'] . " " . $GLOBALS['surname'] . " has been deleted from the server.";
				echo "<br><img src='media/training/Silhouette.jpg' alt='No Photo' style='width:200px;height:250px;'>";
			} else {
				echo "<br>Error: Could not delete the file.";
			}
		} else {
			echo "<br>Error: File '$existingPhotoLink' does not exist.";
		}		

		echo "<br>Record of photo for " . $GLOBALS['firstname'] . " " . $GLOBALS['surname'] . " has been removed from the database";
	}	

	if(isset($_FILES['trainingmap_image']) && $originalFileName <> "") {

		// Validate file type
		if (!in_array($fileType, $allowedTypes)) {
			echo "Error: Only JPG, JPEG, PNG, and GIF files are allowed.";
			echo $originalFileName;
			exit;
		}

		// Validate file size (max 5MB)
		if ($_FILES['trainingmap_image']['size'] > 5 * 1024 * 1024) {
			echo "Error: File size exceeds 5MB.";
			exit;
		}
		global $conn;
		// Insert the training map name and location into the database
		$stmt = $conn->prepare("INSERT INTO tblTraining (RouteImageLink, RouteActive) VALUES(?,1)");
		$stmt->bind_param("s", $originalFileName);
		$stmt->execute();
		$stmt->close();

		$timestamp = time();

		// Define the new file name with the auto-increment ID
		$newFileName = $uploadDir . 'TrainingMap'. $timestamp . '.' . $fileType;
		
		// Move the uploaded file to the target directory with the new name
		if (move_uploaded_file($_FILES['trainingmap_image']['tmp_name'], $newFileName)) {
			// Update the database entry with the new file name
			$stmt = $conn->prepare("UPDATE tblTraining SET RouteImageLink = ? WHERE RouteImageLink = ?");
			$stmt->bind_param("ss", $newFileName, $originalFileName);
			$stmt->execute();
			$stmt->close();

			echo "<br><img src='" . $newFileName . "' alt='No Photo' style='width:500px;height:400px;'>";
			echo "<br>Photo " . htmlspecialchars($originalFileName) . " has been uploaded successfully.";
		} else {
			echo "Error: There was an error uploading your training map image.";
		}
	}
?>