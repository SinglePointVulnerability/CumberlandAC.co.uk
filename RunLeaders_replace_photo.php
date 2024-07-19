<?php
	$uploadDir = 'media/training/';
	$originalFileName = basename($_FILES['runleader_image']['name']);
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

			echo "Photo " . htmlspecialchars($originalFileName) . " for " . $GLOBALS['firstname'] . " " . $GLOBALS['surname'] . " has been uploaded successfully.";
		} else {
			echo "Error: There was an error uploading your run leader photo.";
		}
	}
	// check for delete photo last
	if (isset($_POST['delete_photo'])) {
		global $conn;
		$result_existingPhotoLink = $conn->query("SELECT RunLeaderPhotoLink FROM tblRunLeader WHERE RunLeaderID = " . $GLOBALS['runLeaderID']);
		if ($result_existingPhotoLink->num_rows > 0) {
			while($row = $result_existingPhotoLink->fetch_assoc()) {
				$existingPhotoLink = $row['RunLeaderPhotoLink'];
			}
		}
		
		$conn->query("UPDATE tblRunLeader SET RunLeaderPhotoLink = 'media/training/RunLeaderSilhouette.png' WHERE RunLeaderID = " . $GLOBALS['runLeaderID']);
		
		// Check if the file exists and delete it
		if (file_exists($existingPhotoLink)) {
			if (unlink($existingPhotoLink)) {
				echo "<br>File '$existingPhotoLink' has been deleted from the server.";
			} else {
				echo "<br>Error: Could not delete the file.";
			}
		} else {
			echo "<br>Error: File '$existingPhotoLink' does not exist.";
		}		

		echo "<br>Record of photo for " . $GLOBALS['firstname'] . " " . $GLOBALS['surname'] . " has been removed from the database";
	}	

?>