<?php
include('secure/DBconn.php');
	
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

if ($searchTerm) {
    $stmt = $conn->prepare("SELECT RunnerID, RunnerFirstName, RunnerSurname FROM tblRunners WHERE RunnerFirstName LIKE ? OR RunnerSurname LIKE ?");
    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
} else {
    $stmt = $conn->prepare("SELECT RunnerID, RunnerFirstName, RunnerSurname FROM tblRunners");
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo '<option>' . htmlspecialchars($row['RunnerID']) . ' ' . htmlspecialchars($row['RunnerFirstName']) . ' ' . htmlspecialchars($row['RunnerSurname']) . '</option>';
}

$stmt->close();
$conn->close();
?>