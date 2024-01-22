<?php
$sqlChronologicalRaces =
"SELECT r.RaceDate
	,r.RaceName
	,CASE r.RaceCode
		WHEN 1
			THEN 'Open Championship: Sprint'
		WHEN 2
			THEN 'Open Championship: Middle distance'
		WHEN 4
			THEN 'Open Championship: Long distance'
		WHEN 8
			THEN 'Short Championship'
		WHEN 16
			THEN 'Multi-Terrain Challenge'
		WHEN 32
			THEN 'Open Championship: Sprint to Middle distance'
		ELSE 'Uncategorised'
		END AS `Championship Category`
	,(r.RaceDist / 1000) AS `Race Distance`
FROM `tblRaces` AS r
WHERE r.ChampYear = 2023
ORDER BY r.RaceDate ASC
	,r.RaceDist ASC;";

return $sqlChronologicalRaces;
	
?>