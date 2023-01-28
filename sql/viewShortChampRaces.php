<?php
$sqlShortChampRaces = '
SELECT RaceCode
	,RaceDate
	,RaceID
FROM tblRaces
WHERE ChampYear = 2023
	AND RaceCode = 8
ORDER BY RaceCode DESC
	,RaceDate ASC;';

return $sqlShortChampRaces;
?>