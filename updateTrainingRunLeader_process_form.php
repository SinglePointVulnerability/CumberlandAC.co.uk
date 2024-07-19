<?php
include('secure/DBconn.php');
$GLOBALS['firstname'] = '';
$GLOBALS['surname'] = '';
$GLOBALS['photolink'] = '';
$GLOBALS['runnerID'] = '';
$GLOBALS['runLeaderID'] = '';

// Function to sanitize data
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
function process_updateRunLeader() {
	if (isset($_FILES['runleader_image']) || isset($_POST['delete_photo'])) {
		include('RunLeaders_replace_photo.php');
	} else {
		echo "Run leader photo wasn't changed.";
	}
}
function process_newRunLeader() {
	global $conn;
	// Check if the value already exists in the database
	$stmt = $conn->prepare("SELECT * FROM tblRunLeader WHERE RunnerID = ?");
	$stmt->bind_param("i", $GLOBALS['runnerID']);
	$stmt->execute();
	$result = $stmt->get_result();
	
	if ($result->num_rows > 0) {
		// If exists, let the user know
		echo $GLOBALS['firstname'] . " " . $GLOBALS['surname'] . " is already a Run Leader. New Run Leader not added.";
	} else {
		// If does not exist, perform an INSERT
		$stmt = $conn->prepare("INSERT INTO tblRunLeader (RunnerID,LeaderActive,RunLeaderPhotoLink) VALUES (?,0,'media/training/RunLeaderSilhouette.png')");
		$stmt->bind_param("i", $GLOBALS['runnerID']);
		$stmt->execute();
		echo $GLOBALS['firstname'] . " " . $GLOBALS['surname'] . " added as a new Run Leader.";
	}
	$stmt->close();
	$conn->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['RunLeaderNamesList'])) {
		if(isset($_POST['form_url'])){
			$form_url = $_POST['form_url'];
			
			// Get and sanitize posted data
			$concatenatedData = sanitize($_POST['RunLeaderNamesList']);
			
			// Split the concatenated data into variables
			list($var1, $var2, $var3, $var4) = explode(' ', $concatenatedData);
			
			switch($form_url) {
				case "updateTrainingRunLeader_UpdateRunLeader.php":
					$GLOBALS['firstname'] = $var1;
					$GLOBALS['surname'] = $var2;
					$GLOBALS['photolink'] = $var3;
					$GLOBALS['runLeaderID'] = $var4;
					process_updateRunLeader();
					break;
				case "updateTrainingRunLeader_NewRunLeader.php":
					$GLOBALS['firstname'] = $var2;
					$GLOBALS['surname'] = $var3;
					$GLOBALS['runnerID'] = $var1;
					process_newRunLeader();
					break;
				default:
					break;
			}
		}
	}
} else {
    echo "Invalid request method.";
}
?>