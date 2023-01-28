<?php
$sqlMTChallRaces = '
SELECT RaceCode
	,RaceDate
	,RaceID
FROM tblRaces
WHERE ChampYear = 2023
	AND RaceCode = 16
ORDER BY RaceCode DESC
	,RaceDate ASC;';

return $sqlMTChallRaces;
?>