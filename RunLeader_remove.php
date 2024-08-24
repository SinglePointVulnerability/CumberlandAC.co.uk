<?php
	$uploadDir = 'media/training/';
	$originalFileName = basename($_FILES['runleader_image']['name']);
	$fileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
	$allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

	// check for delete photo last
	if (isset($_POST['remove_run_leader'])) {
		global $conn;
		
		$conn->query("DELETE FROM tblRunLeader WHERE RunLeaderID = " . $GLOBALS['runLeaderID']);
		
		// Check if the file exists and delete it, as long as it isn't the generic silhouette
		if($GLOBALS['photolink'] <> "media/training/Silhouette.jpg") {
			if (file_exists($GLOBALS['photolink'])) {
				if (unlink($GLOBALS['photolink'])) {
					echo "<br>Photo for " . $GLOBALS['firstname'] . " " . $GLOBALS['surname'] . " has been deleted from the server.";
					echo "<br><img src='media/training/Silhouette.jpg' alt='No Photo' style='width:200px;height:250px;'>";
				} else {
					echo "<br>Error: Could not delete the file.";
				}
			} else {
				echo "<br>Error: File '" . $GLOBALS['photolink'] . "' does not exist.";
			}
		}		

		echo "<br>Record for " . $GLOBALS['firstname'] . " " . $GLOBALS['surname'] . " has been removed from the database";
	}	

?>