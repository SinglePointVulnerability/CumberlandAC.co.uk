<?php
	if(session_status() === PHP_SESSION_NONE) {
		session_start();
	}

function auto_version($file='')
{
	// script to force refresh of a file if it's been modified
	// since it was last cache'd in the user's browser
    if(!file_exists($file))
        return $file;
 
    $mtime = filemtime($file);
    return $file.'?'.$mtime;
}
?>

<?php

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize the title
    $title = preg_replace("/[^a-zA-Z0-9\s\.\,\-\!\?]/", "", $_POST['title']);

    // Get the month and year
    $month = $_POST['month'];
    $year = $_POST['year'];

    // Get the file details
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileError = $file['error'];

    // Check if a file was uploaded and there were no errors
    if ($fileError === 0) {
        // Rename the file based on the selected month, year, and title
        $newFileName = 'CAC-Newsletter-' . $year . '-' . $month . '-' . str_replace(' ', '_', $title) . '.pdf';
        $fileDestination = 'media/docs/' . $newFileName;

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($fileTmpName, $fileDestination)) {
			echo "Newsletter uploaded successfully!";
        } else {
            echo "Error uploading the file.";
        }
    } else {
        echo "There was an error with the file upload.";
    }
}

?>

<html>
    <head>
        <!--<meta http-equiv="refresh" content="15; url='http://cumberland-ac.weebly.com/'" />-->
		<link rel="stylesheet" type="text/css" href="<?php echo auto_version('css/styles.css'); ?>" media="screen" />

		<?php require 'modules/navButtonScript.php'; ?>
		
    </head>
    <body>
	<div class="parent-container">
		<div class="page-banner">
			<img class="banner" src="img/main-banner.png" onclick="location.href='index.php'"/>
		</div>
		
		<?php require 'modules/navButtonDiv2.php'; ?>
		
		<div id="lvl2-btns" class="nav-buttons">
		</div>
		<div id="txt-id" class="txt">
			<h1>
				Cumberland AC Newsletter
			</h1>
			<i>Reminders, experiences, funny stories and some serious stuff that we feel needs to be broadcast to you, our members</i>
		</div>
		<div class="txt">
			<p>
				Find archived copies of published newsletters right here, instead of having to rifle through your emails

			</p>
<?php
if (str_contains($_SESSION['role'], 'site admin') || str_contains($_SESSION['role'], 'jog journalist'))
{
?>
<hr>
		<h2>Add a new newsletter</h2>
			<form action="" method="POST" enctype="multipart/form-data">
				<label for="title">Title:</label>
				<input type="text" name="title" id="title" required maxlength="100" pattern="[a-zA-Z0-9\s\.\,\-\!\?]+"><br><br>

				<label for="month">Month:</label>
				<select name="month" id="month" required>
					<?php
					$months = [
						'01' => 'January',
						'02' => 'February',
						'03' => 'March',
						'04' => 'April',
						'05' => 'May',
						'06' => 'June',
						'07' => 'July',
						'08' => 'August',
						'09' => 'September',
						'10' => 'October',
						'11' => 'November',
						'12' => 'December'
					];
					foreach ($months as $key => $value) {
						echo "<option value=\"$key\">$value</option>";
					}
					?>
				</select><br><br>

				<label for="year">Year:</label>
				<select name="year" id="year" required>
					<?php
					$currentYear = date('Y');
					echo "<option value=\"$currentYear\">$currentYear</option>";
					echo "<option value=\"" . ($currentYear + 1) . "\">" . ($currentYear + 1) . "</option>";
					?>
				</select><br><br>

				<label for="file">Upload PDF:</label>
				<input type="file" name="file" id="file" accept="application/pdf" required><br><br>

				<button type="submit">Upload</button>
			</form>
<hr>
<?php
}

// Set the directory path
$directory = 'media/docs/';

// Get all files in the directory that start with "CAC-Newsletter"
$files = glob($directory . 'CAC-Newsletter*.pdf');

// Initialize an array to store file details
$newsletters = [];

// Loop through each file
foreach ($files as $file) {
    // Extract the filename from the full path
    $filename = basename($file);

    // Extract the year, month, and title from the filename
    if (preg_match('/CAC-Newsletter-(\d{4})-(\d{2})(?:-(.+))?\.pdf$/', $filename, $matches)) {
        $year = $matches[1];
        $month = $matches[2];
        $title = isset($matches[3]) ? str_replace('-', ' ', $matches[3]) : null;

        // If title is missing, create a default title
        if (empty($title)) {
            $title = "CAC Newsletter " . date('F Y', strtotime("$year-$month"));
        }

        // Add the file details to the array
        $newsletters[] = [
            'year' => $year,
            'month' => $month,
            'title' => $title,
            'file' => $file
        ];
    }
}

// Sort the array by year and month in descending order
usort($newsletters, function ($a, $b) {
    return ($b['year'] . $b['month']) <=> ($a['year'] . $a['month']);
});


?>
			<table>
				<thead>
					<tr>
						<th>Date</th>
						<th>Title</th>
						<th>Link</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($newsletters as $newsletter): ?>
						<tr>
							<td><?php echo date('F Y', strtotime($newsletter['year'] . '-' . $newsletter['month'])); ?></td>
							<td><?php echo htmlspecialchars($newsletter['title']); ?></td>
							<td><a href="<?php echo $newsletter['file']; ?>" target="_blank">View Newsletter</a></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
<?php
	include 'modules/floatingMenu.php';
?>
	</div>
    </body>
</html>