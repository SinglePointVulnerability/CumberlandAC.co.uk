<?php
$sqlChronologicalRacesOpenLong =
"SELECT r.RaceDate
	,(r.RaceDist/1000) AS RaceDist
	,r.RaceName
	,r.RaceOrganiser
FROM `tblRaces` AS r
WHERE r.ChampYear = 2024
AND r.RaceCode = 4
ORDER BY r.RaceDate ASC;";

return $sqlChronologicalRacesOpenLong;
?>