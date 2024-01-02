<?php
$sqlChronologicalRacesOpenShort =
"SELECT r.RaceDate
	,(r.RaceDist/1000) AS RaceDist
	,r.RaceName
	,r.RaceOrganiser
FROM `tblRaces` AS r
WHERE r.ChampYear = 2023
AND r.RaceCode = 32
ORDER BY r.RaceDate ASC;";

return $sqlChronologicalRacesOpenShort;
?>