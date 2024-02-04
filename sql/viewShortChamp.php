<?php
$sqlShortChamp = "
SELECT DISTINCT races.ChampYear
	,tblDivPoints.RunnerDiv
	,tblDivPoints.`Champ Total` AS ChampTotal
	,tblDivPoints.RunnerID
	,tblDivPoints.RunnerName
FROM tblRaceTimes
INNER JOIN (
	SELECT DISTINCT rt.RunnerID
		,CONCAT (
			run.RunnerFirstName
			,' '
			,run.RunnerSurname
			) AS RunnerName
		,run.RunnerDiv
		,COALESCE(topXPoints_division_split(rt.RunnerID, 8, 2024, 6), 0) AS 'Champ Total'
	FROM tblRaceTimes rt
	INNER JOIN tblRaces rac ON rac.RaceID = rt.RaceID
	INNER JOIN tblRunners run ON run.RunnerID = rt.RunnerID
	WHERE rac.ChampYear = 2024
	ORDER BY run.RunnerDiv DESC
		,COALESCE(topXPoints_division_split(rt.RunnerID, 8, 2024, 6), 0) DESC
	) tblDivPoints ON tblRaceTimes.RunnerID = tblDivPoints.RunnerID
INNER JOIN (
	SELECT tblRaces.RaceID
		,tblRaces.RaceDate
		,tblRaces.RaceCode
		,tblRaces.ChampYear
	FROM tblRaces
	) races ON tblRaceTimes.RaceID = races.RaceID
WHERE races.ChampYear = 2024
	AND `Champ Total` > 0
ORDER BY races.ChampYear
	,tblDivPoints.RunnerDiv DESC
	,tblDivPoints.`Champ Total` DESC";

return $sqlShortChamp;
?>