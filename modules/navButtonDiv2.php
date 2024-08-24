<?php
//case statement to adjust the target of the mem area button depending on log in status and who's logged in
	if(session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	if(!isset($_SESSION["loggedin"])) {
		$memAreaURL = 'secure/authenticate.php';
	}
	if(isset($_SESSION["role"])) {
		$role = $_SESSION["role"];
		
		switch($role) {
			case "club member":
				$memAreaURL = 'memberLanding.php';
				break;
			default:
				$memAreaURL = 'adminLanding.php';
				break;
		}
	}

	echo '<div class="nav-buttons">
			<!-- add modified timestamp (php) to file URLs to force cache refresh -->
			<img class = "btn btn-lvl1 btn-Med" src = "' . auto_version('img/btn-Med.png') . '" onClick="displayResult(\'btnMed\')">
			<img class = "btn btn-lvl1 btn-Cal" src = "' . auto_version('img/btn-Cal.png') . '" onClick="displayResult(\'btnCal\')">
			<img class = "btn btn-lvl1 btn-Champ" src = "' . auto_version('img/btn-Champ.png') . '" onClick="location.href=\'club-championship.php\'">
			<img class = "btn btn-lvl1 btn-Merch" src = "' . auto_version('img/btn-Merch.png') . '" onClick="location.href=\'merchandise.php\'">
			<img class = "btn btn-lvl1 btn-MemArea" src = "' . auto_version('img/btn-MemArea.png') . '" onClick="location.href=\'' . $memAreaURL . '\'">
		</div>';
?>