<?php
$sqlAgeGradedChampRaces = '
SELECT RaceCode
	,RaceDate
	,RaceID
FROM tblRaces
WHERE ChampYear = 2024
	AND RaceCode IN (
		4
		,32
		)
ORDER BY RaceCode DESC
	,RaceDate ASC;';

return $sqlAgeGradedChampRaces;
?>