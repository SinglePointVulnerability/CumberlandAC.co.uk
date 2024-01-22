<?php
$sqlMTChallRaces = '
SELECT RaceCode
	,RaceDate
	,RaceID
FROM tblRaces
WHERE ChampYear = 2024
	AND RaceCode = 16
ORDER BY RaceCode DESC
	,RaceDate ASC;';

return $sqlMTChallRaces;
?>