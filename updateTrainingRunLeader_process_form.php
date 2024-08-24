<?php
include('secure/DBconn.php');
$GLOBALS['firstname'] = '';
$GLOBALS['surname'] = '';
$GLOBALS['photolink'] = '';
$GLOBALS['runnerID'] = '';
$GLOBALS['runLeaderID'] = '';
$GLOBALS['origin'] = '';
$GLOBALS['action'] = '';
$GLOBALS['sql_read'] = '';
$GLOBALS['sql_update'] = '';
$GLOBALS['sql_insert'] = '';
$GLOBALS['trainingInstructions'] = '';

// Function to sanitize data
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
function process_removeRunLeader() {
	if (isset($_POST['remove_run_leader'])) {
		include('RunLeader_remove.php');
	} else {
		echo "Run leader wasn't removed.";
	}
}
function process_updateRunLeader() {
	if (isset($_FILES['runleader_image']) || isset($_POST['delete_photo'])) {
		
		if ($_POST['delete_photo'] == 1) { $GLOBALS['action'] = "delete photo"; }
		
		include('RunLeader_replace_photo.php');
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
		$stmt = $conn->prepare("INSERT INTO tblRunLeader (RunnerID,LeaderActive,RunLeaderPhotoLink) VALUES (?,0,'media/training/Silhouette.jpg')");
		$stmt->bind_param("i", $GLOBALS['runnerID']);
		$stmt->execute();
		echo $GLOBALS['firstname'] . " " . $GLOBALS['surname'] . " added as a new Run Leader.";
	}
	$stmt->close();
	$conn->close();
}
function process_newTrainingInstructions() {
	global $conn;
	$stmt = $conn->prepare($GLOBALS['sql_update']);
	$stmt->execute();

	$stmt->close();


	$stmt = $conn->prepare($GLOBALS['sql_insert']);
	$stmt->bind_param("s",$GLOBALS['trainingInstructions']);
	$stmt->execute();

	echo "New training text added";

	$stmt->close();
	$conn->close();		
}
function process_newTRainingMap() {
	global $conn;
	
	include('RunLeader_replace_photo.php');	
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if(isset($_POST['form_url'])){
		$GLOBALS['origin'] = $_POST['form_url'];
		
		if(isset($_POST['RunLeaderNamesList'])) {
			
			// Get and sanitize posted data
			$concatenatedData = sanitize($_POST['RunLeaderNamesList']);
			
			// Split the concatenated data into variables
			list($var1, $var2, $var3) = explode('_', $concatenatedData);

			// Split the concatenated name(s) into variables
			list($var2_name1, $var2_name2, $var2_name3) = explode (' ', $var2);
		}	

		switch($GLOBALS['origin']) {
			case "updateTrainingRunLeader_RemoveRunLeader.php":
				$GLOBALS['runLeaderID'] = $var1;
				$GLOBALS['firstname'] = $var2_name1;
				$GLOBALS['surname'] = $var2_name2 . ' ' . $var2_name3;
				$GLOBALS['photolink'] = $var3;
				process_removeRunLeader();
				break;
			case "updateTrainingRunLeader_UpdateRunLeader.php":
				$GLOBALS['runLeaderID'] = $var1;
				$GLOBALS['firstname'] = $var2_name1;
				$GLOBALS['surname'] = $var2_name2 . ' ' . $var2_name3;
				$GLOBALS['photolink'] = $var3;
				process_updateRunLeader();
				break;
			case "updateTrainingRunLeader_NewRunLeader.php":
				$GLOBALS['runnerID'] = $var1;
				$GLOBALS['firstname'] = $var2_name1;
				$GLOBALS['surname'] = $var2_name2 . ' ' . $var2_name3;
				process_newRunLeader();
				break;
			case "updateTrainingRunLeader_UpdateTrainingInstructions.php":
				$GLOBALS['trainingInstructions'] = $_POST['sanitisedTrainingInstructions'];
				$GLOBALS['sql_update'] = 'UPDATE tblTraining SET RouteActive = 0';
				$GLOBALS['sql_insert'] = 'INSERT INTO tblTraining (RouteDescription,RouteActive) VALUES (?,1)';

				process_newTrainingInstructions();
				break;
			case "updateTrainingRunLeader_NewTrainingMap.php":

				process_newTrainingMap();
				break;
			default:
				break;
		}
	}
} else {
    echo "Invalid request method.";
}
?>