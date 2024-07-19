<?php
include('secure/DBconn.php');
	
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

if ($searchTerm) {
    $stmt = $conn->prepare("SELECT RunnerFirstName, RunnerSurname, RunLeaderPhotoLink, RunLeaderID FROM tblRunLeader LEFT JOIN tblRunners ON tblRunLeader.RunnerID = tblRunners.RunnerID WHERE RunnerFirstName LIKE ? OR RunnerSurname LIKE ?");
    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
} else {
    $stmt = $conn->prepare("SELECT RunnerFirstName, RunnerSurname, RunLeaderPhotoLink, RunLeaderID FROM tblRunLeader LEFT JOIN tblRunners ON tblRunLeader.RunnerID = tblRunners.RunnerID");
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo '<option>' . htmlspecialchars($row['RunnerFirstName']) . ' ' . htmlspecialchars($row['RunnerSurname']) . ' ' . htmlspecialchars($row['RunLeaderPhotoLink']) . ' ' . htmlspecialchars($row['RunLeaderID']) . '</option>';
}

$stmt->close();
$conn->close();
?>