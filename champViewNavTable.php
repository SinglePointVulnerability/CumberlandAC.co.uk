<?php
    $url=strtok($_SERVER["REQUEST_URI"],'?');

    // to access archived championship records
    if(isset($_GET['champYear'])) {
        $RaceYear = $_GET['champYear'];
    }
    else {
		// commented out to have manual year entry in session variable - mitigates year end bug of all race times disappearing
		//$RaceYear = date("Y");
		$RaceYear = 2021;
    }
?>
<div class='tblNavBar'>
    <table>
        <tr>
            <td><div class='fontBack'><a href="club-championship.php">BACK</a></div></td>
        </tr>
    </table>
</div>